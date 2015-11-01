<?php
/**
 * PROCDN
 */

class PROCDN {
    /**
     * pseudo-constructor
     */

    public static function instance() {
        new self();
    }

    /**
     * constructor
     */
    public function __construct() {
        // rewriter hook
        add_action(
            'template_redirect',
            array(__CLASS__, 'handle_rewrite_hook')
        );

        /* Filter */
        if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR (defined('DOING_CRON') && DOING_CRON) OR (defined('DOING_AJAX') && DOING_AJAX) OR (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) {
            //return;
        }

        /* BE only */
        if ( ! is_admin() ) {
            // return;
        }

        /* Hooks */
        add_action('admin_init', array('PROCDN_Settings', 'register_settings'));
        add_action('admin_menu', array('PROCDN_Settings', 'add_settings_page'));
        add_filter('plugin_action_links_' .PROCDN_BASE,
            array(__CLASS__, 'add_action_link')
        );

        /* admin notices */
        add_action('all_admin_notices',
            array(__CLASS__, 'procdn_requirements_check')
        );
    }

    /**
     * add action links
     *
     * @param   array  $data  alreay existing links
     * @return  array  $data  extended array with links
     */

    public static function add_action_link($data) {
        // check permission
        if ( ! current_user_can('manage_options') ) {
            return $data;
        }

        return array_merge(
            $data,
            array(
                sprintf(
                    '<a href="%s">%s</a>',
                    add_query_arg(
                        array(
                            'page' => 'procdn'
                        ),
                        admin_url('options-general.php')
                    ),
                    __("Settings")
                )
            )
        );
    }

    /**
     * run uninstall hook
     */

    public static function handle_uninstall_hook() {
        delete_option('procdn');
    }

    /**
     * run activation hook
     */
    public static function handle_activation_hook() {
        add_option(
            'procdn',
            array(
                'url' => get_option('siteurl'),
                'dirs' => 'wp-content,wp-includes',
                'excludes' => '.php',
                'relative' => '1',
                'https' => ''
            )
        );
    }

    /**
     * check plugin requirements
     */
    public static function procdn_requirements_check() {
        // WordPress version check
        if ( version_compare($GLOBALS['wp_version'], PROCDN_MIN_WP.'alpha', '<') ) {
            show_message(
                sprintf(
                    '<div class="error"><p>%s</p></div>',
                    sprintf(
                        __("PROCDN is optimized for WordPress %s. Please disable the plugin or upgrade your WordPress installation (recommended).", "cdn"),
                        PROCDN_MIN_WP
                    )
                )
            );
        }
    }

    /**
     * return plugin options
     * 
     * @return  array  $diff  data pairs
     */
    public static function get_options() {
        return wp_parse_args(
            get_option('procdn'),
            array(
                'url' => get_option('siteurl'),
                'dirs' => 'wp-content,wp-includes',
                'excludes' => '.php',
                'relative' => 1,
                'https' => 0)
        );
    }

    /**
     * run rewrite hook
     */
    public static function handle_rewrite_hook() {
        $options = self::get_options();

        // check if origin equals cdn url
        if (get_option('siteurl') == $options['url']) {
            return;
    	}

        $excludes = array_map('trim', explode(',', $options['excludes']));

    	$rewriter = new PROCDN_Rewriter(
            get_option('siteurl'),
            $options['url'],
            $options['dirs'],
            $excludes,
            $options['relative'],
            $options['https']
    	);
    	ob_start(array(&$rewriter, 'rewrite'));
    }
}
