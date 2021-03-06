<?php

class Cominovel_Shortcode {

	protected static $instance;

	public static function register( $attrs, $content ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		if ( empty( $attrs['type'] ) ) {
			return __( 'Cominovel type must be specify.', 'cominovel' );
		}
		return self::$instance->load_shortcodes( $attrs, $content );
	}

	public function __construct() {
		$this->include_shortcodes();
	}

	public function include_shortcodes() {
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-daily.php';
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-genre.php';
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-tax-posts.php';
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-ranking.php';
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-hot.php';
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-featured.php';
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-popular.php';
		require_once dirname( __FILE__ ) . '/class-cominovel-shortcode-madara.php';
	}

	public function load_shortcodes( $attrs, $content ) {
		$shortcodes = apply_filters(
			'cominovel_shortcodes',
			array(
				'post'      => Cominovel_Shortcode_Post::class,
				'daily'     => Cominovel_Shortcode_Daily::class,
				'featured'  => Cominovel_Shortcode_Featured::class,
				'genres'    => Cominovel_Shortcode_Genre::class,
				'hot'       => Cominovel_Shortcode_Hot::class,
				'popular'   => Cominovel_Shortcode_Popular::class,
				'ranking'   => Cominovel_Shortcode_Ranking::class,
				'tax_posts' => Cominovel_Shortcode_Tax_Posts::class,
				'madara'    => Cominovel_Shortcode_Madara::class,
			)
		);

		if ( empty( $shortcodes[ $attrs['type'] ] ) ) {
			return sprintf( __( 'Cominovel is not support type %s.', 'cominovel' ), $attrs['type'] );
		}

		$class_name = $shortcodes[ $attrs['type'] ];
		$shortcode  = new $class_name( $attrs, $content );
		if ( $shortcode instanceof Cominovel_Shortcode_Abstract ) {
			ob_start();
			$shortcode->render();
			return ob_get_clean();
		} else {
			return sprintf(
				__( 'Cominovel type %1$s must be instance of %2$s', 'cominovel' ),
				$attrs['type'],
				Cominovel_Shortcode_Abstract::class
			);
		}
	}
}
