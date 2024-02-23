# TIA
TIA es una Tía de Inteligencia Artificial que genera memes con Piolines y frases bonitas

## Requerimientos

- PHP 7.0 or higher
- GD Library

## Instructions

- Add bg folder with backgrounds
- Add fonts folder with web safe fonts
- Add img folder with transparent pngs of piolines
- Add chayanne folder with transparent pngs of chayanne
- Add rbd/bg with square rbd backgrounds
- Add rbd/item with transparent pngs of rbd

### .htaccess
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^/?(.*)$ /index.php?route=/$1 [L,QSA]
```

## Changelog

v14.2.1

- Replaced OpenAI for OpenRouter to increase model availability
- Added function to check if text is overlapping
- 
