<?php
// based on https://github.com/easydigitaldownloads/EDD-Extension-Boilerplate
/**
 * Plugin Name: Easy Digital Downloads Visual Composer Integration
 * Plugin URI:  https://github.com/nwoetzel/edd-vc-integration
 * Description: This plugin maps easy-digital-download shortcodes to WPBakery Visual Composer elements.
 * Version:     1.0.0
 * Author:      Nils Woetzel
 * Author URI:  https://github.com/nwoetzel
 * Text Domain: edd-vc-integration
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_VC_Integration' ) ) {

/**
 * Main EDD_VC_Integration class
 *
 * @since 1.0.0
 */
class Edd_VC_Integration {

    /**
     * @var Edd_VC_Integration $instance The one true EDD_Plugin_Name
     * @since       1.0.0
     */
    private static $instance;

    /**
     * Get active instance
     *
     * @access      public
     * @since       1.0.0
     * @return      object self::$instance The one true EDD_VC_Integration
     */
    public static function instance() {
        if( !self::$instance ) {
            self::$instance = new EDD_VC_Integration();
            self::$instance->setup_constants();
            self::$instance->includes();
//            self::$instance->load_textdomain();
            self::$instance->hooks();
        }
        return self::$instance;
    }

    /**
     * Setup plugin constants
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function setup_constants() {
        // Plugin version
        define( 'EDD_VC_INTERGATION_VER', '1.0.0' );
        // Plugin path
        define( 'EDD_VC_INTERGATION_DIR', plugin_dir_path( __FILE__ ) );
        // Plugin URL
        define( 'EDD_VC_INTERGATION_URL', plugin_dir_url( __FILE__ ) );
    }

    /**
     * Include necessary files
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function includes() {
        // Include scripts
//        require_once EDD_PLUGIN_NAME_DIR . 'includes/scripts.php';
//        require_once EDD_PLUGIN_NAME_DIR . 'includes/functions.php';
        /**
         * @todo        The following files are not included in the boilerplate, but
         *              the referenced locations are listed for the purpose of ensuring
         *              path standardization in EDD extensions. Uncomment any that are
         *              relevant to your extension, and remove the rest.
         */
//        require_once EDD_PLUGIN_NAME_DIR . 'includes/shortcodes.php';
//        require_once EDD_PLUGIN_NAME_DIR . 'includes/widgets.php';
    }

    /**
     * Run action and filter hooks
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     *
     */
    private function hooks() {
        // Register settings
//        add_filter( 'edd_settings_extensions', array( $this, 'settings' ), 1 );

        // map shortcodes
        if( function_exists( 'vc_map' ) ) { 
            add_action( 'vc_before_init', array( $this, 'vcMap' ) );
        }

        // Handle licensing
//        if( class_exists( 'EDD_License' ) ) {
//            $license = new EDD_License( __FILE__, 'VC Integration', EDD_VC_INTEGRATION_VER, 'Nils Woetzel' );
//        }
    }

    /**
     * Add settings
     *
     * @access      public
     * @since       1.0.0
     * @param       array $settings The existing EDD settings array
     * @return      array The modified EDD settings array
     */
    public function settings( $settings ) {
        $new_settings = array(
            array(
                'id'    => 'edd_vc_integration_settings',
                'name'  => '<strong>' . __( 'VC Integration Settings', 'edd-vc-integration' ) . '</strong>',
                'desc'  => __( 'Configure VC Integration Settings', 'edd-vc-integration' ),
                'type'  => 'header',
            )
        );
        return array_merge( $settings, $new_settings );
    }

    /**
     * map shortcodes to visual composer elements
     *
     * @access      public since it is registered as an action
     * @since       1.0.0
     * @return      void
     */
    public function vcMap() {
        // https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
        vc_map( array(
            'name' => __( 'Downloads', 'easy-digital-downloads' ),
            'base' => 'downloads',
            'icon' => 'dashicons dashicons-download',
            'category' => 'EDD',
            'params' => array(
                self::categoryParam(),
                self::tagParam(),
                self::excludeCategoryParam(),
                self::excludeTagParam(),
                self::relationParam(),
                self::fullContentParam(),
                self::excerptParam(),
                self::buyButtonParam(),
                self::columnsParam(),
            ),
        ));
    }

    /**
     * This is a shortcode parameter allowing to choose multiple download categories.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function categoryParam() {
        return array(
            'param_name' => 'category',
            'heading' => __( 'Categories', 'js_composer' ),
            'type' => 'autocomplete',
            'settings' => array(
                'multiple' => 'true',
                'sortable' => true,
                'min_length' => 1,
                'no_hide' => true,
                'unique_values' => true,
                'display_inline' => true,
                'values' => self::downloadCategoryNames(),
            ),
            'description' => 'Show downloads of particular download categories.',
            'admin_label' => true,
            'group' => 'Data',
        );
    }

    /**
     * This is a shortcode parameter allowing to choose multiple download tags.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function tagParam() {
        return array(
            'param_name' => 'tag',
            'heading' => __( 'Tags', 'js_composer' ),
            'type' => 'autocomplete',
            'settings' => array(
                'multiple' => 'true',
                'sortable' => true,
                'min_length' => 1,
                'no_hide' => true,
                'unique_values' => true,
                'display_inline' => true,
                'values' => self::downloadTagNames(),
            ),
            'description' => 'Show downloads of particular download tags.',
            'admin_label' => true,
            'group' => 'Data',
        );
    }

    /**
     * This is a shortcode parameter allowing to exclude multiple download categories.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function excludeCategoryParam() {
        // simply modify the categoryParam
        $param = self::categoryParam();
        $param['param_name'] = 'exclude_category';
        $param['heading'] = 'Exclude Categories';
        $param['description'] = 'Exclude downloads of particular download categories';

        return $param;
    }

    /**
     * This is a shortcode parameter allowing to exclude multiple download tags.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function excludeTagParam() {
        // simply modify the tagParam
        $param = self::tagParam();
        $param['param_name'] = 'exclude_tag';
        $param['heading'] = 'Exclude Tags';
        $param['description'] = 'Exclude downloads of particular download tags';

        return $param;
    }

    protected static function relationParam() {
        return array(
            'param_name' => 'relation',
            'heading' => 'Category and Tag relation',
            'description' => 'Specify whether the downloads displayed have to be in ALL the categories/tags provided ("AND"), or just in at least one ("OR").',
            'value' => array('OR' => 'OR','AND' => 'AND',),
            'type' => 'dropdown',
            'admin_label' => true,
            'group' => 'Data',
        );
    }

    /**
     * This is a shortcode parameter allowing to select showing the full download post content instead of just the excerpt.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function fullContentParam() {
        return array(
            'param_name' => 'full_content',
            'heading' => 'Full content',
            'description' => 'Display the full content of the download or just the excerpt.',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'type' => 'checkbox',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter allowing to select showing the excerpt of a download post instead of the full content.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function excerptParam() {
        return array(
            'param_name' => 'excerpt',
            'heading' => 'Excerpt',
            'description' => 'Display just the excerpt.',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'type' => 'checkbox',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter allowing to display/hdie the buy button with the download.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function buyButtonParam() {
        return array(
            'param_name' => 'buy_button',
            'heading' => 'Buy button',
            'description' => 'Display the buy button for each download.',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes', __( 'No', 'js_composer' ) => 'no' ),
            'save_always' => true,
            'type' => 'dropdown',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter to select the number of columns in which download previews are displayed.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */    
    protected static function columnsParam() {
        return array(
            'param_name' => 'columns',
            'heading' => 'Columns',
            'description' => 'Display the downloads in that many columns.',
            'value' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4',),
            'type' => 'dropdown',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This collects all download_category names.
     * Helper for the categoryParam().
     *
     * @access       protected
     * @since        1.0.0
     * @return       string[] names of all download_category terms
     */
    protected static function downloadCategoryNames() {
        $term_names = get_terms( array(
            'taxonomy' => 'download_category',
            'fields' => 'names',
        ));

        $values = array();
        foreach( $term_names as $term) {
            $values[] = array( 'label' => $term, 'value' => $term);
        }

        return $values;
    }

    /**
     * This collects all download_tag names.
     * Helper for the tagParam().
     *
     * @access       protected
     * @since        1.0.0
     * @return       string[] names of all download_tag terms
     */
    protected static function downloadTagNames() {
        $term_names = get_terms( array(
            'taxonomy' => 'download_tag',
            'fields' => 'names',
        ));

        $values = array();
        foreach( $term_names as $term) {
            $values[] = array( 'label' => $term, 'value' => $term);
        }

        return $values;
    }

}

} // End if class_exists check

/**
 * The main function responsible for returning the one true EDD_VC_Integration
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \EDD_VC_Integration The one true EDD_VC_Integration
 */
function EDD_VC_Integration_load() {
    if( ! class_exists( 'Easy_Digital_Downloads' ) ) {
        if( ! class_exists( 'EDD_Extension_Activation' ) ) {
            require_once 'includes/class.extension-activation.php';
        }
        $activation = new EDD_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
    } else {
        return EDD_VC_Integration::instance();
    }
}
add_action( 'plugins_loaded', 'EDD_VC_Integration_load' );

/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function edd_vc_integration_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'edd_vc_integration_activation' );

/**
 * A nice function name to retrieve the instance that's created on plugins loaded
 *
 * @since 2.2.3
 * @return object EDD_Simple_Shipping
 */
function edd_vc_integration() {
	return edd_vc_integration_load();
}
