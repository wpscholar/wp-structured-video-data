<?php

namespace wpscholar\WordPress;

/**
 * Class StructuredVideoData
 *
 * @package wpscholar\WordPress
 */
class StructuredVideoData {

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * StructuredVideoData constructor.
	 *
	 * @param string            $videoUrl Video URL
	 * @param \WP_Post|int|null $post WP Post
	 * @param array             $args Args that will overwrite existing structured data key/value pairs.
	 */
	public function __construct( $videoUrl, $post = null, array $args = [] ) {

		$post = get_post( $post );

		$embed = new \WP_oEmbed();
		$data  = $embed->get_data( $videoUrl );

		if ( 'video' === $data->type ) {

			// Setup default arguments
			$defaults = [
				'name'         => ! empty( $data->title ) ? $data->title : get_the_title( $post ),
				'description'  => ! empty( $data->title ) ? $data->title : get_the_title( $post ),
				'thumbnailUrl' => ! empty( $data->thumbnail_url ) ? $data->thumbnail_url : (string) get_the_post_thumbnail_url( $post ),
				'uploadDate'   => date( 'c', strtotime( $post->post_date_gmt ) ),
			];

			// Extract embed URL from iframe
			if ( preg_match( '/src=(\'|")(.*?)(\'|")/', $data->html, $matches ) && isset( $matches[2] ) ) {
				$defaults['embedUrl'] = $matches[2];
			}

			$this->data = array_merge( $defaults, $args );

		}
	}

	/**
	 * Check if structured data could be found.
	 *
	 * @return bool
	 */
	public function hasStructuredData() {
		return ! empty( $this->data );
	}

	/**
	 * Render structured data using JSON Linking Data
	 *
	 * @param string $markup Video embed markup
	 *
	 * @return string
	 */
	public function render( $markup = '' ) {
		if ( $this->hasStructuredData() ) {
			$data = array_merge( $this->data, [
				'@context' => 'http://schema.org/',
				'@type'    => 'VideoObject',
			] );

			return '<script type="application/ld+json">' . wp_json_encode( (object) $data ) . '</script>' . $markup;
		}

		return $markup;
	}

	/**
	 * Render structured data in microdata format.
	 *
	 * @param string $markup Video embed markup
	 *
	 * @return string
	 */
	public function renderAsMicrodata( $markup = '' ) {
		if ( $this->hasStructuredData() ) {
			ob_start();
			?>
			<div itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
				<?php foreach ( $this->data as $key => $value ): ?>
					<meta itemprop="<?= esc_attr( $key ) ?>" content="<?= esc_attr( $value ); ?>"/>
				<?php endforeach; ?>
				<?php echo $markup; ?>
			</div>
			<?php
			return ob_get_clean();
		}

		return $markup;
	}

}
