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
class EDD_VC_Integration {

    /**
     * @var EDD_VC_Integration $instance The one true EDD_Plugin_Name
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
        define( 'EDD_VC_INTERGATION_VER', '1.0.2' );
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
     * map easy-digital-downloads shortcodes to visual composer elements
     * http://docs.easydigitaldownloads.com/category/219-short-codes
     *
     * @access      public since it is registered as an action
     * @since       1.0.0
     * @return      void
     */
    public function vcMap() {
        // https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
        // http://docs.easydigitaldownloads.com/article/224-downloads
        vc_map( array(
            'name' => __( 'Downloads', 'easy-digital-downloads' ),
            'base' => 'downloads',
            'description' => 'Output a list or grid of downloadable products.',
            'icon' => 'dashicons dashicons-download',
            'category' => 'EDD',
            'params' => array(
                self::categoryParam(),
                self::tagParam(),
                self::excludeCategoryParam(),
                self::excludeTagParam(),
                self::relationParam(),
                self::numberParam(),
                self::priceParam(),
                self::fullContentParam(),
                self::excerptParam(),
                self::buyButtonParam(),
                self::columnsParam(),
                self::thumbnailsParam(),
                self::orderbyParam(),
                self::orderParam(),
                self::idsParam(),
            ),
        ) );

        // http://docs.easydigitaldownloads.com/article/1194-purchasecollection-shortcode
        $purchase_collection_params = array();
        $purchase_collection_params[] = array(
            'param_name' => 'taxonomy',
            'heading' => 'Taxonomy',
            'description' => 'Category or Tag.',
            'type' => 'dropdown',
            'value' => array('Category' => 'download_category', 'Tag' => 'download_tag',),
            'save_always' => true,
            'admin_label' => true,
            'group' => 'Data',
        );
        $purchase_collection_category_param = self::categoryParam();
        $purchase_collection_category_param['dependency'] = array(
            'element' => 'taxonomy',
            'value' => 'download_category',
        );
        $purchase_collection_params[] = $purchase_collection_category_param;
        $purchase_collection_tag_param = self::tagParam();
        $purchase_collection_tag_param['dependency'] = array(
            'element' => 'taxonomy',
            'value' => 'download_tag',
        );
        $purchase_collection_params[] = $purchase_collection_tag_param;
        $purchase_collection_params[] = self::textParam();
        $purchase_collection_params[] = self::styleParam();
        $purchase_collection_params[] = self::colorParam();

        vc_map( array(
            'name' => __( 'Purchase Collection', 'easy-digital-downloads' ),
            'base' => 'purchase_collection',
            'description' => 'Make a unique category-based collection of products to be sold as a package.',
            'category' => 'EDD',
            'params' => $purchase_collection_params,
        ) );

        // http://docs.easydigitaldownloads.com/article/223-downloaddiscounts
        vc_map( array(
            'name' => __( 'Discounts', 'easy-digital-downloads' ),
            'base' => 'download_discounts',
            'description' => 'Display a list of all active discounts in an unordered list of discount code and amount.',
            'category' => 'EDD',
        ) );

        // http://docs.easydigitaldownloads.com/article/220-downloadhistory
        vc_map( array(
            'name' => __( 'History', 'easy-digital-downloads' ),
            'base' => 'download_history',
            'description' => 'The user’s download history with product names and all associated download links.',
            'category' => 'EDD',
        ) );

        // http://docs.easydigitaldownloads.com/article/228-purchasehistory
        vc_map( array(
            'name' => __( 'Purchase History', 'easy-digital-downloads' ),
            'base' => 'purchase_history',
            'description' => 'The user’s purchase history with date, amount of each purchase, email and download links.',
            'category' => 'EDD',
        ) );

        // http://docs.easydigitaldownloads.com/article/227-downloadcheckout
        vc_map( array(
            'name' => __( 'Checkout', 'easy-digital-downloads' ),
            'base' => 'download_checkout',
            'description' => 'Display the checkout form.',
            'category' => 'EDD',
        ) );

        // http://docs.easydigitaldownloads.com/article/229-purchaselink
        $purchase_links_params = array();
        $purchase_links_params[] = self::idParam();
        if ( edd_use_skus()) {
            $purchase_links_params[] = self::skuParam();
        }
        $purchase_links_params[] = self::priceParam();
        $purchase_links_params[] = self::textParam();
        $purchase_links_params[] = self::styleParam();
        $purchase_links_params[] = self::colorParam();
        $purchase_links_params[] = self::classParam();
        $purchase_links_params[] = self::priceIdParam();
        $purchase_links_params[] = self::directParam();

        vc_map( array(
            'name' => __( 'Purchase Link', 'easy-digital-downloads' ),
            'base' => 'purchase_link',
            'description' => 'Display a purchase button for any download.',
            'category' => 'EDD',
            'params' => $purchase_links_params,
        ) );

        // http://docs.easydigitaldownloads.com/article/226-downloadcart-shortcode
        vc_map( array(
            'name' => __( 'Cart', 'easy-digital-downloads' ),
            'base' => 'download_cart',
            'description' => 'Display the cart.',
            'category' => 'EDD',
        ) );

        // http://docs.easydigitaldownloads.com/article/233-eddprofileeditor
        vc_map( array(
            'name' => __( 'Profile Editor', 'easy-digital-downloads' ),
            'base' => 'edd_profile_editor',
            'description' => 'Profile editor for logged-in customer.',
            'category' => 'EDD',
        ) );

        // http://docs.easydigitaldownloads.com/article/222-eddlogin
        vc_map( array(
            'name' => __( 'Login', 'easy-digital-downloads' ),
            'base' => 'edd_login',
            'description' => 'Login Form.',
            'category' => 'EDD',
            'params' => array(
                self::redirectParam(),
            ),
        ) );

        // http://docs.easydigitaldownloads.com/article/889-register-form
        vc_map( array(
            'name' => __( 'Register', 'easy-digital-downloads' ),
            'base' => 'edd_register',
            'description' => 'Account Registration Form.',
            'category' => 'EDD',
            'params' => array(
                self::redirectParam(),
            ),
        ) );

        // http://docs.easydigitaldownloads.com/article/1193-eddprice-shortcode
        vc_map( array(
            'name' => __( 'Price', 'easy-digital-downloads' ),
            'base' => 'edd_price',
            'description' => 'Shwo price of a download.',
            'category' => 'EDD',
            'params' => array(
                self::idParam(),
                self::priceIdParam(),
            ),
        ) );

        // http://docs.easydigitaldownloads.com/article/221-eddreceipt
        vc_map( array(
            'name' => __( 'Receipt', 'easy-digital-downloads' ),
            'base' => 'edd_receipt',
            'description' => 'Detailed breakdown of the purchased items.',
            'category' => 'EDD',
            'params' => array(
                array(
                    'param_name' => 'error',
                    'heading' => 'Error message',
                    'description' => 'Change the default error message, if an error occurs.',
                    'type' => 'textfield',
                    'admin_label' => true,
                    'group' => 'Layout',
                ),
                self::PriceParam(),
                array(
                    'param_name' => 'discount',
                    'heading' => 'Discount',
                    'description' => 'Display the discount codes used.',
                    'type' => 'checkbox',
                    'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
                    'admin_label' => true,
                    'group' => 'Layout',
                ),
                array(
                    'param_name' => 'products',
                    'heading' => 'Products',
                    'description' => 'Display the products purchased.',
                    'type' => 'checkbox',
                    'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
                    'admin_label' => true,
                    'group' => 'Layout',
                ),
                array(
                    'param_name' => 'date',
                    'heading' => 'Date',
                    'description' => 'Display the date of the purchase.',
                    'type' => 'checkbox',
                    'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
                    'admin_label' => true,
                    'group' => 'Layout',
                ),
                array(
                    'param_name' => 'payment_key',
                    'heading' => 'Purchase Identifier',
                    'description' => 'Display the unique identifier for the order.',
                    'type' => 'checkbox',
                    'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
                    'admin_label' => true,
                    'group' => 'Layout',
                ),
                array(
                    'param_name' => 'payment_method',
                    'heading' => 'Payment method',
                    'description' => 'Display the method of payment for the order.',
                    'type' => 'checkbox',
                    'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
                    'admin_label' => true,
                    'group' => 'Layout',
                ),
                array(
                    'param_name' => 'payment_id',
                    'heading' => 'Payment Number',
                    'description' => 'Display the payment number of the order.',
                    'type' => 'checkbox',
                    'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
                    'admin_label' => true,
                    'group' => 'Layout',
                ),
            ),
        ) );
    }

    protected static function redirectParam() {
        return array(
            'param_name' => 'redirect',
            'heading' => __( 'Redirect', 'easy-digital-downloads' ),
            'description' => 'Redirect user after successful Login',
            'type' => 'textfield',
            'admin_label' => true,
            'group' => 'Function',
        );
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
            'description' => 'Show downloads of particular download categories.',
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
            'description' => 'Show downloads of particular download tags.',
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

    /**
     * This is a shortcode parameter allowing to define the relation between the category and tag selections.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
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
     * This is a shortcode parameter to specify the number of downloads to display.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function numberParam() {
        return array(
            'param_name' => 'number',
            'heading' => 'Number of downloads',
            'description' => 'Specify the maximum number of downloads you want to output.',
            'type' => 'textfield',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter selecting if the price should be displayed.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function priceParam() {
        return array(
            'param_name' => 'price',
            'heading' => 'Show price',
            'description' => 'Display the price of the downloads.',
            'type' => 'checkbox',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'admin_label' => true,
            'group' => 'Layout',
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
            'type' => 'checkbox',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
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
            'type' => 'checkbox',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
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
     * This is a shortcode parameter to enable the display of download thumbnails.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function thumbnailsParam() {
        return array(
            'param_name' => 'thumbnails',
            'heading' => 'Show Thumbnails',
            'description' => 'Display thumbnails of the downloads.',
            'type' => 'checkbox',
            'value' => array( __( 'Yes', 'js_composer' ) => 'true' ),
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter to select the download attribute to order by.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function orderbyParam() {
        return array(
            'param_name' => 'orderby',
            'heading' => 'Order by download attribute',
            'description' => 'Order the downloads by the selected attribute.',
            'value' => array('id','price','post_date','random','title'),
            'type' => 'dropdown',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter to define the direction of the orderby param.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function orderParam() {
        return array(
            'param_name' => 'order',
            'heading' => 'Order direction for the selected download attribute',
            'description' => 'Order the downloads by the selected attribute in that direction.',
            'value' => array('ASC','DESC'),
            'type' => 'dropdown',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter to define a list of downloads by their ids.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function idsParam() {
        return array(
            'param_name' => 'ids',
            'heading' => 'Specific downloads',
            'description' => 'You can specify multiple downloads.',
            'type' => 'autocomplete',
            'settings' => array(
                'multiple' => true,
                'sortable' => true,
                'min_length' => 1,
                'no_hide' => true,
                'unique_values' => true,
                'display_inline' => true,
                'values' => self::downloads(),
            ),
            'admin_label' => true,
            'group' => 'Data',
        );
    }

    /*
     * params related to the purchase_link shortcode
     */

    /**
     * This is a shortcode parameter to select a download by its id.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function idParam() {
        return array(
            'param_name' => 'id',
            'heading' => 'Download',
            'description' => 'Select a download.',
            'type' => 'autocomplete',
            'settings' => array(
                'sortable' => true,
                'min_length' => 1,
                'display_inline' => true,
                'values' => self::downloads(),
            ),
            'admin_label' => true,
            'group' => 'Data',
        );
    }

    /**
     * This is a shortcode parameter to select a download by its sku.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function skuParam() {
        return array(
            'param_name' => 'sku',
            'heading' => 'Download by SKU',
            'description' => 'SKU of the download - use this instead of selecting a download.',
            'type' => 'textfield',
            'admin_label' => true,
            'group' => 'Data',
        );
    }

    /**
     * This is a shortcode parameter to define the text for a purchase link.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function textParam() {
        return array(
            'param_name' => 'text',
            'heading' => 'Text on Button',
            'description' => 'Specify the text that is diplayed on the button.',
            'type' => 'textfield',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

     /**
     * This is a shortcode parameter to define the style for a purchase link (text or button).
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function styleParam() {
        return array(
            'param_name' => 'style',
            'heading' => 'Style',
            'description' => 'Select the style of the purchase link.',
            'type' => 'dropdown',
            'value' => array('Default' => edd_get_option( 'button_style', 'button' ), 'Button' => 'button', 'Text' => 'text',),
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter to define the color for a purchase button.
     * http://docs.easydigitaldownloads.com/article/867-style-settings
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function colorParam() {
        return array(
            'param_name' => 'color',
            'heading' => 'Color',
            'description' => 'Select the color of the button.',
            'type' => 'dropdown',
            'value' => array('default' => edd_get_option( 'checkout_color', 'blue' ),'Inherit' => 'inherit', 'Gray' => 'gray', 'Blue' => 'blue', 'Green' => 'green', 'Dark gray' => 'dark gray', 'Yellow' => 'yellow',),
            'dependency' => array(
                'element' => 'style',
                'value' => 'button',
            ),
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter to define additional classes for a purchase link.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function classParam() {
        return array(
            'param_name' => 'class',
            'heading' => 'Class',
            'description' => 'Add classes to the link.',
            'type' => 'textfield',
            'admin_label' => true,
            'group' => 'Layout',
        );
    }

    /**
     * This is a shortcode parameter to define the varibale price id for a purchase link.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function priceIdParam() {
        return array(
            'param_name' => 'price_id',
            'heading' => 'Variable Price Id',
            'description' => 'The varibale price id to use - first one is default.',
            'type' => 'textfield',
            'admin_label' => true,
            'group' => 'Data',
        );
    }

    /**
     * This is a shortcode parameter to define if the purchse links should lead to the checkout.
     *
     * @access       protected
     * @since        1.0.0
     * @return       array describing a shortcode parameter
     */
    protected static function directParam() {
        return array(
            'param_name' => 'direct',
            'heading' => 'Direct checkout',
            'description' => 'Send the user directly to the checkout.',
            'type' => 'checkbox',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'admin_label' => true,
            'group' => 'Function',
        );
    }

    /*
     * other helper functions
     */

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

    protected static function downloads() {
        $posts_array = get_posts(array(
            'post_type' => 'download',
            'numberposts' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC',
            'fields' => array('ID','post_title')
        ));

        $downloads = array();
        foreach($posts_array as $post) {
            $downloads[] = array( 'label' => $post->post_title, 'value' => $post->ID);            
        }

        return $downloads;
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
