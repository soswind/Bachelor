# Bachelor News Integration English site
Code from bachelor project in WordPress

## Beskrivelse
Dette repository indeholder koden til en WordPress-integration på det engelske site, der henter og viser nyheder og olietillæg fra virksomhedens danske udviklingssite til andre sites via REST API.

## Projekt Struktur
- `/functions/api-integration.php`: Indeholder API integration og shortcodes
- `/css/style.css`: Stylesheet for nyhedsvisning og olietillæg

## Funktionalitet
- Henter nyheder fra hovedsitet via WordPress REST API
- Henter og viser olietillæg
- Håndterer fejl ved API-kald
- Formaterer data til visning
- Responsivt design med grid layout
- Hover effekter og moderne visuel præsentation

## Anvendelse
Koden bruger to hovedfunktioner:
1. `[vis_nyheder cat="news-dk"]` - shortcode til visning af nyheder
2. `[vis_olietillaeg]` - shortcode til visning af olietillæg

## Installation
1. Kopier koden til dit WordPress-tema
2. Opdater API-credentials
3. Brug shortcodes på de ønskede sider

## Teknologier
- WordPress
- PHP
- REST API
- WordPress Shortcodes

## Udvikler
Søs Wind
