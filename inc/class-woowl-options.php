<?php

class WOOWLPageOptions
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'WOOWL ' . __('Settings'), 
            'WOOWL ' . __('Settings'), 
            'manage_options', 
            'woowl-page-options', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'woowl_fields' );
        include WOOWL_PATH . 'templates/woowl-page-options.php';
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'woowl_option_group', // Option group
            'woowl_fields', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'WOO Waiting List', // Title
            '', // Callback
            'woowl-page-options' // Page
        );  

        // add_settings_field(
        //     'title', 
        //     'Title', 
        //     array( $this, 'title_callback' ), 
        //     'woowl-page-options', 
        //     'setting_section_id'
        // );  

        add_settings_field(
            'out_of_stock', // ID
            'Out of Stock', // Title 
            '', //callback
            'woowl-page-options', // Page
            'setting_section_id' // Section           
        );    

        add_settings_field(
            'custom_code', // ID
            'Use a custom code', // Title 
            '', //callback
            'woowl-page-options', // Page
            'setting_section_id' // Section           
        );    
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        
        // if( isset( $input['title'] ) )
        //     $new_input['title'] = sanitize_text_field( $input['title'] );

        if( isset( $input['out_of_stock'] ) )
            $new_input['out_of_stock'] = $input['out_of_stock'];

        if( isset( $input['custom_code'] ) )
            $new_input['custom_code'] = $input['custom_code'];

        return $new_input;
    }

    /** 
     * Get the settings option array and print one of its values
     *
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="woowl_fields[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
    */
}

if( is_admin() )
    $woowl_page_options = new WOOWLPageOptions();