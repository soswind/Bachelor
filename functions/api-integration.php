<?php
 /** Henter data fra hovedsitet via REST API */

function hent_data_fra_api($category_id) {

    $url = 'https://www.lundhjemmesider-udvikling.dk/jumbotransport_dk/wp-json/wp/v2/posts?order=desc&orderby=date&_embed&categories=' . $category_id;

    $args = array(
        'timeout' => 5,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('USERNAME:PASSWORD'),
        ),
    );

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
        return 'Fejl ved hentning af data: ' . $response->get_error_message();
    }

    $data = wp_remote_retrieve_body($response);
    
    if (empty($data)) {
        return 'Ingen data modtaget.';
    }

    return json_decode($data);
}

/**
 * Viser nyheder fra hovedsitet via shortcode [vis_nyheder]
 * 
 * Understøtter forskellige kategorier via kategori-mapping:
 * - Dansk (news-dk): kategori 13
 * - Engelsk (news-eng): kategori 14
 * - Norsk (news-no): kategori 15
 * - Svensk (news-se): kategori 16
 * - Finsk (news-fi): kategori 17
 */

function vis_nyheder($_atts) {

	$arr_cats = array('news-dk'=>13,'news-eng'=>14,'news-no'=>15,'news-se'=>16,'news-fi'=>17);
	$cat = $_atts['cat'];
	$cat_id = $arr_cats[$cat];
	

	
    $data = hent_data_fra_api($cat_id);

    if (is_string($data)) {
        return $data;
    }

    $output = '<ul class="nyheder-list">';
    
    foreach ($data as $item) {
        $post_id = $item->id;
        $post_url = get_permalink($post_id);

     
        $output .= '<li class="nyheder-item"';

        if (!empty($item->featured_media)) {
            $image_sizes = isset($item->_embedded->{'wp:featuredmedia'}) ? $item->_embedded->{'wp:featuredmedia'}[0]->media_details->sizes : null;
            if ($image_sizes && isset($image_sizes->full->source_url)) {
                $image_url = esc_url($image_sizes->full->source_url);
                $output .= ' style="background-image: url(' . $image_url . ');"';
            }
        }

        $output .= '>';

        $output .= '<a href="' . esc_url($post_url) . '" class="nyheder-link">';

        $output .= '<div class="nyheder-title-box"><h3 class="nyheder-title-item">' . esc_html($item->title->rendered) . '</h3></div>';

        $output .= '<div class="nyheder-media-content-container">';
        
        // Dato
        setlocale(LC_TIME, 'da_DK.UTF-8', 'da_DK', 'danish');
        $date = $item->date;
        $formatted_date = strftime('%e. %B %Y', strtotime($date));
        $output .= '<p class="nyheder-date">' . esc_html($formatted_date) . '</p>';
        
        // Uddrag
        $output .= '<p class="nyheder-excerpt">' . wp_kses_post(wp_trim_words($item->excerpt->rendered, 10)) . '</p>';

        $output .= '</div></a></li>';
    }
    
    $output .= '</ul>';

    return $output;
}

add_shortcode('vis_nyheder', 'vis_nyheder');

/**
 * Viser olietillæg fra hovedsitet via shortcode [vis_olietillaeg]
 * Henter og formaterer olietillæg fra kategori 20.
 * Inkluderer titel, indhold og opdateringsdato for olietillægget.
 */

function vis_olietillaeg() {
    $data = hent_data_fra_api(20);

    if (is_string($data)) {
        return $data; // Returnerer fejlmeddelelse
    }

    $output = '<div>'; 
    foreach ($data as $item) {
      
        
        $output .= '<h2 class="oil-title">' . esc_html($item->title->rendered) . '</h2>';

        $content = wp_kses_post($item->content->rendered); 
        $content = preg_replace('/<p>/i', '<p class="oil-content">', $content); 

        $output .= $content;
        
        setlocale(LC_TIME, 'da_DK.UTF-8', 'da_DK', 'danish');
        $date = $item->date;
        $formatted_date = strftime('%e. %B %Y', strtotime($date));
		    $formatted_date = date ("d/m/Y", strtotime($date));
        if (isset($item->date)) {
            $output .= '<p class="publish-date">Updated: ' . esc_html($formatted_date) . '</p>';
        }
    }
    $output .= '</div>';

    return $output;
}

add_shortcode('vis_olietillaeg', 'vis_olietillaeg');

/**
 * Modificerer news post type slug
 */

function wpnw_modify_news_post_slug( $slug ){
$slug = 'news'; 
return $slug;
}
add_filter( 'wpnw_news_post_slug', 'wpnw_modify_news_post_slug' );
?>
