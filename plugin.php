<?php
/**
 * Plugin Name: Quick Info
 * Description: Adds a quick info widget to the WordPress dashboard (e.g. company details)
 * Author: Dirk Pennings<dirkpennings@gmail.com>
 * Author URI: https://dirk.me
 * Version: 0.1
 * Plugin URI: https://github.com/dirkpennings/quick-info
 */

class QuickInfoWidget {

	/**
	 * Translations array (maybe a future update will contain translation files)
	 */
	const translations = array(
		"add_another_row" => "Add another row",
		"key" => "Key",
		"value" => "Value",
		"widget_title" => "Quick Info"
	);

	/**
	 * Settings array
	 */
	const settings = array(
		'id' => 'quick_info_widget',
		'version' => '0.1',
		'css' => array(
			array(
				'id' => 'custom-style',
				'url' => '/css/main.css'
			),
			array(
				'id' => 'font-awesome',
				'url' => '/css/font-awesome.min.css'
			)
		),
		'js' => array(
			array(
				'id' => 'custom-script',
				'url' => '/js/main.js'
			)
		)
	);

	/**
	 * Adds the styling for the dashboard widget.
	 *
	 * This function is hooked into the 'admin_enqueue_scripts' action below.
	 */
	public static function load_dashboard_css()
	{
		foreach(self::settings['css'] as $css) {
			wp_register_style( $css['id'], plugins_url( $css['url'], __FILE__ ), array(), self::settings['version'], 'all' );
		    wp_enqueue_style( $css['id'] );
		}
	}

	/**
	 * Adds the scripts for the dashboard widget.
	 *
	 * This function is hooked into the 'admin_enqueue_scripts' action below.
	 */
	public static function load_dashboard_js()
	{
		foreach(self::settings['js'] as $js) {
			wp_register_script( $js['id'], plugins_url( $js['url'], __FILE__ ), array(), self::settings['version'], 'all' );
		    wp_enqueue_script( $js['id'] );
		}
	}

	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action below.
	 */
	public static function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			self::settings['id'],
			self::translations['widget_title'],
			array('QuickInfoWidget','widget'),
			array('QuickInfoWidget','config')
		);
	}

	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	public static function dashboard_widget_callback() {
		echo self::settings['description'];
	}

    /**
     * Load the widget code
     */
    public static function widget() {
        require_once( 'includes/widget.php' );
    }

    /**
     * Load widget config code.
     *
     * This is what will display when an admin clicks
     */
    public static function config() {
        require_once( 'includes/widget-config.php' );
    }

    /**
     * Gets the options for a widget of the specified name.
     *
     * @param string $widget_id Optional. If provided, will only get options for the specified widget.
     * @return array An associative array containing the widget's options and values. False if no opts.
     */
    public static function get_dashboard_widget_options( $widget_id='' )
    {
        //Fetch ALL dashboard widget options from the db...
        $opts = get_option( 'dashboard_widget_options' );

        //If no widget is specified, return everything
        if ( empty( $widget_id ) )
            return $opts;

        //If we request a widget and it exists, return it
        if ( isset( $opts[$widget_id] ) )
            return $opts[$widget_id];

        //Something went wrong...
        return false;
    }

    /**
     * Gets one specific option for the specified widget.
     * @param $widget_id
     * @param $option
     * @param null $default
     *
     * @return string
     */
    public static function get_dashboard_widget_option( $widget_id, $option, $default=NULL ) {

        $opts = self::get_dashboard_widget_options($widget_id);

        //If widget opts dont exist, return false
        if ( ! $opts )
            return false;

        //Otherwise fetch the option or use default
        if ( isset( $opts[$option] ) && ! empty($opts[$option]) )
            return $opts[$option];
        else
            return ( isset($default) ) ? $default : false;

    }

    /**
     * Saves an array of options for a single dashboard widget to the database.
     * Can also be used to define default values for a widget.
     *
     * @param string $widget_id The name of the widget being updated
     * @param array $args An associative array of options being saved.
     * @param bool $add_only If true, options will not be added if widget options already exist
     */
    public static function update_dashboard_widget_options( $widget_id , $args=array(), $add_only=false )
    {
        //Fetch ALL dashboard widget options from the db...
        $opts = get_option( 'dashboard_widget_options' );

        //Get just our widget's options, or set empty array
        $w_opts = ( isset( $opts[$widget_id] ) ) ? $opts[$widget_id] : array();

        if ( $add_only ) {
            //Flesh out any missing options (existing ones overwrite new ones)
            $opts[$widget_id] = array_merge($args,$w_opts);
        }
        else {
            //Merge new options with existing ones, and add it back to the widgets array
            $opts[$widget_id] = array_merge($w_opts,$args);
        }

        //Save the entire widgets array back to the db
        return update_option('dashboard_widget_options', $opts);
    }
}

/**
 * Hook into the Wordpress actions
 */
add_action( 'admin_enqueue_scripts', array('QuickInfoWidget', 'load_dashboard_css') );
add_action( 'admin_enqueue_scripts', array('QuickInfoWidget', 'load_dashboard_js') );
add_action( 'wp_dashboard_setup', array('QuickInfoWidget', 'add_dashboard_widgets') );