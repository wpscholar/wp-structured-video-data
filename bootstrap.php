<?php

if ( function_exists( 'add_filter' ) ) {

	add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) {

		$structuredData = new \wpscholar\WordPress\StructuredVideoData( $url, $post_id );
		if ( $structuredData->hasStructuredData() ) {
			$html = $structuredData->render( $html );
		}

		return $html;
	}, 99, 4 );

}
