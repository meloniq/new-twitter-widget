<?php
/*
	Plugin Name: New Twitter Widget
	Plugin URI: http://blog.meloniq.net/
	Description: WordPress plugin that adds customizable Twitter widget.
	Author: MELONIQ.NET
	Version: 1.0
	Author URI: http://blog.meloniq.net
*/


/**
 * Avoid calling file directly
 */
if ( ! function_exists( 'add_action' ) )
	die( 'Whoops! You shouldn\'t be doing that.' );


/**
 * Plugin version and textdomain constants
 */
define( 'NTW_VERSION', '1.0' );
define( 'NTW_TD', 'new-twitter-widget' );


/**
 * Load Text-Domain
 */
load_plugin_textdomain( NTW_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Load front-end scripts
 */
function ntw_enqueue_scripts() {

}
add_action( 'wp_enqueue_scripts', 'ntw_enqueue_scripts' );


/**
 * Load front-end styles
 */
function ntw_enqueue_styles() {
	wp_enqueue_style( 'ntw_style', plugins_url( 'style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'ntw_enqueue_styles' );


/**
 * Initialize widgets
 */
function ntw_widgets_init() {
	register_widget( 'NTW_Twitter_Widget' );
}
add_action( 'widgets_init', 'ntw_widgets_init' );


/**
 * Twitter Widget class.
 */
class NTW_Twitter_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __( 'Display your tweets from Twitter.', NTW_TD ), 'classname' => 'widget_twitter' );
		parent::__construct( 'widget-featured-ads', __( 'NTW Twitter Feed', NTW_TD ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Twitter Feed', NTW_TD ) : $instance['title'] );
		$username = empty( $instance['username'] ) ? 'meloniq_net' : $instance['username'];

		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$service = 'http://www.twitter-rss.com/user_timeline.php';
		$feed_url = add_query_arg( array( 'screen_name' => $username ), $service );

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;

		wp_widget_rss_output( $feed_url, array( 'items' => $number, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 0 ) );

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = preg_replace( '/[^a-z0-9_]/i', '', $new_instance['username'] );
		$instance['number'] = (int) $new_instance['number'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'username' => 'meloniq_net', 'number' => 10 ) );
		$title = esc_attr( $instance['title'] );
		$username = esc_attr( $instance['username'] );
		$number = absint( $instance['number'] );
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', NTW_TD ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e( 'Twitter username:', NTW_TD ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of tweets to show:', NTW_TD ); ?></label>
				<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="2" />
			</p>
		<?php
	}
}


/**
 * Initialize WP App Store Installer
 */
function ntw_wpappstore_init() {

	if ( class_exists( 'WP_App_Store_Installer' ) )
		return;

	require_once( 'includes/wp-app-store.php' );
	$wp_app_store_installer = new WP_App_Store_Installer( 3788 );
}
add_action( 'admin_init', 'ntw_wpappstore_init', 9 );


