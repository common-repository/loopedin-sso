<?php


class LoopedInSSO_Settings
{
    private $LoopedInSSO_options;
    private $LoopedInSSO_plugin_page;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->LoopedInSSO_plugin_page = 'LoopedInSSO_settings';
        add_action( 'admin_menu', array( $this, 'LoopedInSSO_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'LoopedInSSO_page_init' ) );
        add_filter('plugin_action_links_'.LoopedinSSO_PLUGIN_BASENAME, array( $this, 'LoopedInSSO_plugin_page_settings_link' ));

    }

    function LoopedInSSO_plugin_page_settings_link( $links ) {
        $links[] = '<a href="' .
            admin_url( 'options-general.php?page='.$this->LoopedInSSO_plugin_page) .
            '">' . __('Settings') . '</a>';
        return $links;
    }

    /**
     * Add options page
     */
    public function LoopedInSSO_add_plugin_page()
    {
        add_submenu_page(
            'options-general.php',
            'LoopedIn SSO Settings',
            'LoopedIn SSO',
            'manage_options',
            $this->LoopedInSSO_plugin_page,
            array( $this, 'LoopedInSSO_settings_page' )
        );
        
    }

    function LoopedInSSO_settings_page() {
        $this->LoopedInSSO_options = get_option( 'LoopedInSSO_settings_name' );

        if(!isset($this->LoopedInSSO_options['LoopedInSSO_wordpress_url'])){
            $this->LoopedInSSO_options['LoopedInSSO_wordpress_url'] = wp_login_url();
        }
        ?>
        <h1> <?php esc_html_e( 'LoopedIn SSO Settings'); ?> </h1>
        <form method="POST" action="options.php">
        <?php
            settings_fields( 'LoopedInSSO_settings_group' );
            do_settings_sections( 'LoopedInSSO_settings_admin' );
            submit_button();
        ?>
        </form>
        <?php
    }

    function LoopedInSSO_page_init() {

		register_setting(
            'LoopedInSSO_settings_group', // Option group
            'LoopedInSSO_settings_name',
            array( $this, 'LoopedInSSO_sanitize' ) // Sanitize
         );


        add_settings_section(
            'LoopedInSSO_settings_section', // ID
            '', // Title
            '', // Callback
            'LoopedInSSO_settings_admin' // Page
        );  

        add_settings_field(
            'LoopedInSSO_key', // ID
            'SSO Key', // Title 
            array( $this, 'LoopedInSSO_key_callback' ),
            'LoopedInSSO_settings_admin', // Page
            'LoopedInSSO_settings_section' // Section           
        );  


        add_settings_field(
            'LoopedInSSO_public_url', 
            'LoopedIn Public URL', 
            array( $this, 'LoopedInSSO_public_url_callback' ),
            'LoopedInSSO_settings_admin', 
            'LoopedInSSO_settings_section'
        );  

        add_settings_field(
            'LoopedInSSO_wordpress_url', 
            'WordPress Login URL', 
            array( $this, 'LoopedInSSO_wordpress_url_callback' ),
            'LoopedInSSO_settings_admin', 
            'LoopedInSSO_settings_section'
        );  
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function LoopedInSSO_sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['LoopedInSSO_key'] ) )
            $new_input['LoopedInSSO_key'] = sanitize_text_field( $input['LoopedInSSO_key'] );

        if( isset( $input['LoopedInSSO_public_url'] ) )
            $new_input['LoopedInSSO_public_url'] = sanitize_url( $input['LoopedInSSO_public_url'] );

        if( isset( $input['LoopedInSSO_wordpress_url'] ) )
            $new_input['LoopedInSSO_wordpress_url'] = sanitize_url( $input['LoopedInSSO_wordpress_url'] );


        return $new_input;
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function LoopedInSSO_key_callback()
    {
        printf(
            '<input type="text" id="LoopedInSSO_key" name="LoopedInSSO_settings_name[LoopedInSSO_key]" value="%s" />',
            isset( $this->LoopedInSSO_options['LoopedInSSO_key'] ) ? esc_attr( $this->LoopedInSSO_options['LoopedInSSO_key']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function LoopedInSSO_public_url_callback()
    {
        printf(
            '<input type="text" id="LoopedInSSO_public_url" name="LoopedInSSO_settings_name[LoopedInSSO_public_url]" value="%s" />',
            isset( $this->LoopedInSSO_options['LoopedInSSO_public_url'] ) ? esc_attr( $this->LoopedInSSO_options['LoopedInSSO_public_url']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function LoopedInSSO_wordpress_url_callback()
    {
        printf(
            '<input type="text" id="LoopedInSSO_wordpress_url" name="LoopedInSSO_settings_name[LoopedInSSO_wordpress_url]" value="%s" />',
            isset( $this->LoopedInSSO_options['LoopedInSSO_wordpress_url'] ) ? esc_attr( $this->LoopedInSSO_options['LoopedInSSO_wordpress_url']) : ''
        );
    }



}