# WP Structured Video Data

Structured data for video embeds in WordPress.

Not familiar with structured data? Take a look at these resources:

- [Google Developers - Structured Video Data Reference](https://developers.google.com/search/docs/data-types/video)
- [Schema.org - VideoObject Reference](https://schema.org/VideoObject)
- [Google Structured Data Testing Tool](https://search.google.com/structured-data/testing-tool/u/0/)

## Requirements

PHP 5.6+
WordPress 4.8+

## Prerequisites

Install [Composer](https://getcomposer.org/)

## Installation

- Add the module to your WordPress plugin or theme project via Composer:

```bash
composer require wpscholar/wp-structured-video-data
```

- Make sure you have added the Composer autoloader to your project:

```php
require __DIR__ . '/vendor/autoload.php';

``` 

## Usage

By default, all video embeds in WordPress will automatically have the appropriate structured data injected.

## Advanced Usage

If you want to use this for specific video URLs that aren't within the WordPress content:

```php
<?php

$videoUrl = 'https://www.youtube.com/watch?v=V9I1-c9o1LM';
$embed = wp_oembed_get( $videoUrl );
$structuredData = new \wpscholar\WordPress\StructuredVideoData( $videoUrl );
echo $structuredData->render( $embed ); // For JSON-LD format

// OR

echo $structuredData->renderAsMicrodata( $embed ); // For Microdata format
```

Note that you will be responsible for also rendering the video embed. This can be done using [`wp_oembed_get()`](https://developer.wordpress.org/reference/functions/wp_oembed_get/).
