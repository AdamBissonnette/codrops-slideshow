<?php
/*
Plugin Name: Codrops Slideshow
Plugin URI: https://www.mediamanifesto.com/
Description: Allows you to unlock the power of the codrops slideshow
Version: 1.0.0
Author: Adam Bissonnette
Author URI: https://www.mediamanifesto.com/
*/
define( 'CODROPSSLIDESHOW_OPTIONS', 'codrops_slideshow' );
define( 'CODROPSSLIDESHOW__DOMAIN', 'codrops_slideshow_plugin' );
define( 'CODROPSSLIDESHOW__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CODROPSSLIDESHOW__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

class CodropsSlideshow {
	protected static $class_config 				= array();
	protected $plugin_ajax_nonce				= 'codrops_slideshow-ajax-nonce';
	protected $plugin_path						= CODROPSSLIDESHOW__PLUGIN_DIR;
	protected $plugin_url						= CODROPSSLIDESHOW__PLUGIN_URL;
	protected $plugin_textdomain				= CODROPSSLIDESHOW__DOMAIN;
	protected $plugin_options					= CODROPSSLIDESHOW_OPTIONS;

	function __construct( $config = array() ) {
		//Cache plugin congif options
		self::$class_config = $config;

		//Set textdomain
		add_action( 'after_setup_theme', array($this, 'plugin_textdomain') );
		
		//Init plugin
		add_action( 'init', array($this, 'init_plugin') );
		add_action( 'admin_init', array($this, 'admin_init_plugin') );
		//add_action( 'current_screen', array($this, 'current_screen_init_plugin') );
		// add_action('wp_ajax_codrops_slideshow', array($this, 'codrops_slideshow_ajax') );
		// add_action('wp_ajax_nopriv_codrops_slideshow', array($this, 'codrops_slideshow_ajax') );
		
		// if (CodropsSlideshow::isready($config))
		// {
		// 	add_action( 'init', array($this, 'reviews_endpoint') );
		// 	add_action( 'template_redirect', array($this, 'reviews_endpoint_data') );
		// }

		add_shortcode( "codrops_slideshow" , array($this, 'codrops_slideshow_shortcode') );

		// register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
		// register_activation_hook( __FILE__, '_flush_rewrites' );
	}

	// public function _flush_rewrites() {
	// 	flush_rewrite_rules();
	// }

	public static function plugin_activation() {}
	public function plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_textdomain, FALSE, $this->plugin_path . '/languages/' );		
	}

	//AJAX Functions
	public function codrops_slideshow_ajax()
	{
		// if( is_admin() ) {
		// 	switch($_REQUEST['fn']){
		// 		case 'get':
		// 			CodropsSlideshow::get_reviews();
		// 			die;
		// 		break;
		// 	}
		// }
	}

	public function init_plugin() {
		$options 		= self::$class_config;

		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'wp_enqueue_styles', array($this, 'enqueue_styles') );
	}

	public function admin_init_plugin() {
		
		//Init vars
		$options 		= self::$class_config;
		
		if( is_admin() ) {
			
			//Enqueue admin scripts
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') );	
		}
	}

	public function enqueue_scripts() {
		$js_inc_path 	= $this->plugin_url . 'js/';
		$css_inc_path 	= $this->plugin_url . 'css/';
		
		//Enqueue script
		wp_register_script( 'imagesloaded',
			$js_inc_path . 'imagesloaded.pkgd.min.js',
			array(),
			'1.0',
			TRUE
		);
		wp_register_script( 'animejs',
			$js_inc_path . 'anime.min.js',
			array(),
			'1.0',
			TRUE
		);
		wp_register_script( 'demo-1',
			$js_inc_path . 'demo1.js',
			array(),
			'1.0',
			TRUE
		);
		wp_register_script( 'demo-2',
			$js_inc_path . 'demo2.js',
			array(),
			'1.0',
			TRUE
		);
		wp_register_script( 'demo-3',
			$js_inc_path . 'demo3.js',
			array(),
			'1.0',
			TRUE
		);
		wp_register_script( 'demo-4',
			$js_inc_path . 'demo4.js',
			array(),
			'1.0',
			TRUE
		);
		wp_register_script( 'demo-5',
			$js_inc_path . 'demo5.js',
			array(),
			'1.0',
			TRUE
		);
		wp_register_script( 'demo-6',
			$js_inc_path . 'demo6.js',
			array(),
			'1.0',
			TRUE
		);

		wp_register_style( 'base', $css_inc_path . 'base.css' );
		//Enqueue script
		// wp_register_script( 'googleplaces',
		// 	'https://maps.googleapis.com/maps/api/js?key='.self::$class_config["places-key"].'&libraries=places',
		// 	array( 'jquery' ),
		// 	'1.0',
		// 	TRUE
		// );

		// if (self::$class_config["analytics"]) {
		// 	//include gtm
		// }
	}

	public function enqueue_admin_scripts() {
		$js_inc_path 	= $this->plugin_url . 'js/';
		// wp_register_script( 'vml_adminajax',
		// 	$js_inc_path . 'admin-ajax.js',
		// 	array( 'jquery' ),
		// 	'1.0',
		// 	TRUE
		// );
		// wp_enqueue_script( 'vml_adminajax' );
	}

	private function _update_option($key, $value)
	{
		$options = get_option($this->plugin_options);
		if ($options)
		{
			$options[$key] = $value;
			update_option($this->plugin_options, $options);
		}
	}

	function codrops_slideshow_shortcode($atts, $content=null)
	{
		extract(shortcode_atts(array(
		       'class' => '',
		       'id' => '',
		       'height' => '100vh',
		       'view' => 'demo-1',
		       'type' => 'normal',
		       'background_image' => '',
		       'autoplay' => 1,
		       'speed' => 2000
		    ), $atts) );

		$slides = json_decode(trim(str_replace("[", "\"", $content)));

		// echo $count;
		// var_dump(json_decode(trim($json)));
		// die;

		//enqueue registered scripts
		$nav_template = '<nav class="slidenav"><div class="slidenav__item slidenav__item--prev"></div><div class="slidenav__item slidenav__item--next"></div></nav>';
		$slides_wrapper = sprintf('<div id="slideshow-container" class="%s loading"><div class="slideshow" style="height:%s;">%s%s</div></div>', $view, $height, "%s", $nav_template);

		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'animejs' );
		wp_enqueue_style( 'base' );

		wp_enqueue_script( $view );

		//scripts
		$output = "";

		switch ($type) {
			case 'split':
				$slides_wrapper = sprintf($slides_wrapper, "%s");
				$titles = "";
				$images = "";
				foreach ($slides as $slide) {
					$arr = $this->_create_split_slide($slide);
					$images .= $arr[0];
					$titles .= $arr[1];
				}
				$output = sprintf('<div class="slides slides--images">%s</div><div class="slides slides--titles">%s</div>', $images, $titles);
				break;
			
			default:
				$slides_wrapper = sprintf($slides_wrapper, '<div class="slides">%s</div>');
				foreach ($slides as $slide) {
					$output .= $this->_create_normal_slide($slide);
				}
				break;
		}

		if (!empty($background_image))
		{
			$output .= sprintf('<input type="hidden" id="codrops-background-image" value="%s" />', $background_image);
		}

		if (!empty($autoplay))
		{
			$output .= sprintf('<input type="hidden" id="codrops-autoplay" value="%s" />', $autoplay);
		}

		if (!empty($speed))
		{
			$output .= sprintf('<input type="hidden" id="codrops-speed" value="%s" />', $speed);
		}
		

		return sprintf($slides_wrapper, $output);
	}

	private function _create_split_slide($slide)
	{
		extract(shortcode_atts(array(
		       'image' => '',
		       'title' => '',
		       'description' => '100vh',
		       'link_url' => '',
		       'link_text' => '',
		       'isfirst' => 'false',
		       'type' => 'normal',
		    ), $slide) );

		$container_template = '<div class="slide%s">%s</div>';
		$image_template = '<div class="slide__img" style="background-image: url(\'%s\')"></div>';
		$title_template = '<h2 class="slide__title">%s</h2>';
		$description_template = '<p class="slide__desc">%s</p>';
		$link_template = '<a class="slide__link" href="%s">%s</a>';

		$slide_content = $title_template;

		if (!empty($description))
		{
			$slide_content .= $description_template;
		}

		if (!empty($link_url))
		{
			$slide_content .= $link_template;
		}

		$slide_class = ($isfirst=="true")?" slide--current":"";
		
		$image_slide_template = sprintf($container_template,$slide_class, $image_template);
		$title_slide_template = sprintf($container_template,$slide_class, $slide_content);

		$image_slide = sprintf($image_slide_template, $image);
		$title_slide = sprintf($title_slide_template, $title, $description, $link_url, $link_text);

		return array($image_slide, $title_slide);
	}

	private function _create_normal_slide($slide)
	{
		extract(shortcode_atts(array(
		       'image' => '',
		       'title' => '',
		       'description' => '100vh',
		       'link_url' => '',
		       'link_text' => '',
		       'isfirst' => 'false',
		       'type' => 'normal',
		    ), $slide) );

		$container_template = '<div class="slide%s">%s</div>';
		$image_template = '<div class="slide__img" style="background-image: url(\'%1$s\')"></div>';
		$title_template = '<h2 class="slide__title">%2$s</h2>';
		$description_template = '<p class="slide__desc">%3$s</p>';
		$link_template = '<a class="slide__link" href="%4$s">%5$s</a>';

		$slide_content = $image_template . $title_template;

		if (!empty($description))
		{
			$slide_content .= $description_template;
		}

		if (!empty($link_url))
		{
			$slide_content .= $link_template;
		}

		$slide_class = ($isfirst=="true")?" slide--current":"";
		$slide_template = sprintf($container_template,$slide_class, $slide_content);

		return sprintf($slide_template,	$image, $title, $description, $link_url, $link_text);
	}
}

// register_activation_hook( __FILE__, array( 'CodropsSlideshow', 'plugin_activation' ) );
// include('admin/admin-init.php');

codrops_slideshow_init();
function codrops_slideshow_init() {

	//Init vars
	global $codrops_slideshow_options;
	
	//Set plugin config
	$config_options = array(
		'setting_template' => "",
	);
	
	//Cache plugin options array
	$codrops_slideshow_options = get_option( CODROPSSLIDESHOW_OPTIONS );

	if( isset($codrops_slideshow_options['setting_template']) ) {
		$config_options['setting_template'] =  $codrops_slideshow_options['setting_template'];
	}

	new CodropsSlideshow( $config_options );
}