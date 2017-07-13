<?php

namespace BokkaWP\BDX;

require_once(BGBDX_DIR . 'admin/HtmlGenerator.php');

use BokkaWP\BDX\Admin\HtmlGenerator;

class Admin  {
    public function __construct()
    {

        // create custom plugin settings menu
        add_action('admin_menu', array($this, 'bokka_bdx_create_menu'));
        //call register settings function
        add_action( 'admin_init', array($this, 'register_settings'));

    }


    public function bokka_bdx_create_menu()
    {
        //create new top-level menu
        add_menu_page('Catalyst', 'Catalyst', 'unknown', "catalyst", false , plugins_url('/images/icon.png', __FILE__) );
        add_submenu_page('catalyst', 'BDX Configuration', 'BDX', 'administrator', 'bdx', array($this, 'bokka_bdx_settings_page'));
    }

    /**
     * register our field name
     */
    public function register_settings()
    {
            register_setting( 'bokka-bdx-settings-group', BGBDX_PREFIX );
    }


    /**
     * Echos our HTML for each field in our form
     * @param $configs
     * @param array $options
     */
    private function create_form_html($configs, $options = [])
    {
        foreach($configs as $config){
            $prefix = isset($options['prefix']) ? $options['prefix'] : false;
            echo "<tr valign=\"top\">";
                echo "<th><label>${config['label']}</label></th>";
                echo "<td>";
                    $new_options['indices'] = $prefix ? $prefix : [];
                    $field = HtmlGenerator::create_field($config, array_merge($options, $new_options));
                    echo $field->html;

                    if(isset($config['fields'])){
                        echo "<table>";

                            is_array($prefix) ? array_push($prefix, $config['name']) : $prefix = [$config['name']];
                            $new_options['prefix'] = $prefix;
                            if($config['type'] == 'post_type'){
                                $new_options['post_type'] = $field->value ? $field->value : $options['post_type'];
                            }

                            $this->create_form_html($config['fields'], array_merge($options, $new_options));
                        echo "</table>";
                    }
                echo "</td>";
            echo "</tr>";
        }
    }


    public function bokka_bdx_settings_page()
    {
        $fields = require(BGBDX_DIR . '/fields.php'); ?>
        <div class="wrap">
        <h1>BDX Feed Configuration</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'bokka-bdx-settings-group' ); ?>
            <?php do_settings_sections( 'bokka-bdx-settings-group' ); ?>
            <table class="form-table">
                <?php $this->create_form_html($fields); ?>
            </table>
            <?php submit_button(); ?>
        </form>
        </div>
    <?php }
}
