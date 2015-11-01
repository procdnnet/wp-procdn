<?php

/**
 * PROCDN_Settings
 */

class PROCDN_Settings {
    /**
     * register settings
     */
    public static function register_settings() {
        register_setting(
            'procdn',
            'procdn',
            array(__CLASS__, 'validate_settings')
        );
    }

    /**
     * validation of settings
     *
     * @param   array  $data  array with form data
     * @return  array         array with validated values
     */
    public static function validate_settings($data) {
        return [
            'url' => esc_url($data['url']),
            'dirs' => esc_attr($data['dirs']),
            'excludes' => esc_attr($data['excludes']),
            'relative' => (int)($data['relative']),
            'https' => (int)($data['https'])
        ];
    }

    /**
     * add settings page
     */
    public static function add_settings_page() {
        $page = add_options_page(
            'ProCDN',
            'ProCDN',
            'manage_options',
            'procdn',
            array(__CLASS__, 'settings_page')
        );
    }

    /**
     * settings page
     *
     * @return  void
     */
    public static function settings_page() {
        echo '<div class="wrap">';
        echo '<h2>' . _e("ProCDN Settings", "cdn") . '</h2>';
        echo '<img src="http://procdn.net/static/images/logo/procdn-logo-64.png" alt="ProCDN.net" border=0 />';
        echo '<form method="post" action="options.php">';
        echo settings_fields('procdn');

        $options = PROCDN::get_options();

        echo '<table class="form-table">';
        echo '<tr valign="top">';
        echo '<th scope="row">' . _e("CDN URL", "cdn") . '</th>';
        echo '<td>';
        echo '<fieldset>';
        echo '<label for="procdn_url">';
        echo '<input type="text" name="procdn[url]" id="procdn_url" value="'. $options['url'].'" size="64" class="regular-text code" />';
        echo _e("", "cdn");
        echo '</label>';

        echo '<p class="description">';
        echo _e("Enter the CDN URL without trailing", "cdn") . '<code>/</code>';
        echo '</p>';
        echo '</fieldset>';
        echo '</td>';
        echo '</tr>';

        echo '<tr valign="top">';
        echo '<th scope="row">';
        echo _e("Included Directories", "cdn");
        echo '</th>';
        echo '<td>';
        echo '<fieldset>';
        
        echo '<label for="procdn_dirs">
        <input type="text" name="procdn[dirs]" id="procdn_dirs" value="'. $options['dirs'] . '" size="64" class="regular-text code" />
        '. _e("Default: <code>wp-content,wp-includes</code>", "cdn").'</label>

        <p class="description">'. _e("Assets in these directories will be pointed to the CDN URL. Enter the directories separated by", "cdn").' <code>,</code>
        </p>
        </fieldset>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">'. _e("Excluded Extensions", "cdn").'</th>
        <td>
        <fieldset>
        <label for="procdn_excludes">
        <input type="text" name="procdn[excludes]" id="procdn_excludes" value="'. $options['excludes'] .'" size="64" class="regular-text code" />
        '. _e("Default: <code>.php</code>", "cdn") .'
        </label>

        <p class="description">'. _e("Enter the exclusions separated by", "cdn") .' <code>,</code>
        </p>
        </fieldset>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">'. _e("Relative Path", "cdn").'</th>
        <td>
        <fieldset>
        <label for="procdn_relative">
        <input type="checkbox" name="procdn[relative]" id="procdn_relative" value="1" '. checked(1, $options['relative']) .' />
        '. _e("Enable CDN for relative paths (default: enabled).", "cdn") .'
        </label>

        <p class="description">'. _e("", "cdn") .'</p>
        </fieldset>
        </td>
        </tr>';
        echo '<tr valign="top">';
        echo '<th scope="row">'._e("CDN HTTPS", "cdn").'</th>';
        echo '<td>';
        echo '<fieldset>';
        echo '<label for="procdn_https">';
        echo '<input type="checkbox" name="procdn[https]" id="procdn_https" value="1" '. checked(1, $options['https']) .' />';
        echo _e("Enable CDN for HTTPS connections (default: disabled).", "cdn");
        echo '</label>';
        
        echo '<p class="description">'._e("", "cdn").'</p>';
        echo '</fieldset>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo submit_button();
        echo '</form>';
        echo '</div>';
    }
}
