<?php
/*
Plugin Name: Bokka BDX
Plugin URI: http://github.com
Description: Provides Configuration Pages to map data to BDX service. Creates cron that sends data to BDX service.
Author: Codewizard
Version: 0.0.1
Author URI: http://bokkagroup.com
*/

namespace BokkaWP;

use BokkaWP\BDX\Adapter;
use BokkaWP\BDX\Admin;
use BokkaWP\BDX\Commander;

define("BGBDX_DIR", plugin_dir_path(__FILE__) );
define("BGBDX_PREFIX", "bdx-data" );

/**
 * Allows us to add filters in filters.php
 */
require_once(BGBDX_DIR . "Commander.php");

function bdx_add_filter($namespace, $callable) {
    $commander = Commander::get_instance();
    $commander->addCommand($namespace, $callable);
}

function bdx_apply_filters($namespace, $configration, $value) {
    $commander = Commander::get_instance();
    return $commander->doCommand($namespace, $configration, $value);
}


/**
 * BokkaBuilder
 * @version 0.0.1 Singleton
 */
class BDX {

	private static $instance;
    private $IN_PROGRESS = false;

	public function __construct()
    {

        add_filter( 'query_vars', array($this, 'add_query_vars_filter'));
        add_filter('wp_headers', array($this, 'changeHeaders'));
        $this->pluginDir =  plugin_dir_path( __FILE__ );

		//load base classes
        if(is_admin()){
            require_once(BGBDX_DIR . 'admin/Admin.php');
            new Admin();

            return;
        }

        require_once(BGBDX_DIR . 'Adapter.php');

        add_action('nightly', array($this, 'sendData'));
        add_action('parse_query', array($this, 'handleRequest'));
       
    }

    public function changeHeaders($headers)
    {
        $test   =   get_query_var('bokka_aggregate_test', false);
        if ($test === 'true') {
            $headers['Content-Type'] = 'application/json; charset=utf-8';
            return $headers;
        }
    }

    public function add_query_vars_filter( $vars ){
      $vars[] = "bokka_aggregate_test";
      $vars[] = "bokka_aggregate_run";
      return $vars;
    }


    /**
     * Create Directories on plugin activation
     */
    static public function install()
    {
        if (! wp_next_scheduled ( 'nightly' )) {
            wp_schedule_event(time(), 'daily', 'nightly');
        }
    }

	/**
	 * Singleton instantiation
	 * @return [static] instance
	 */
	public static function get_instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }



    public function handleRequest($query)
    {

        if ($this->IN_PROGRESS === TRUE)
            return;
       
        $test   =   get_query_var('bokka_aggregate_test', false);
        $run    =   get_query_var('bokka_aggregate_run', false);

        if($test === 'true'){
             $this->IN_PROGRESS = TRUE;

            //dry run, generate data but don't send
            echo $this->getData();
            die();
        } else if ($run === 'true') {
            $this->IN_PROGRESS = TRUE;
            echo $response = $this->sendData();
            die();
        }
        
    }

    private function getData(){
        $configurations     = get_option(BGBDX_PREFIX);
        $data               = new Adapter($configurations);
        $JSON               = json_encode($data->parsed);
        return $JSON;
    }


    /**
     * send data producrtion
     */
    public function sendData()
    {
        $domain = $_SERVER['HTTP_HOST'];
        $url = "http://138.68.22.158:8080/v1/uploadJSON";
        $JSON = $this->getData();
        

        if(strpos($domain, 'local') > -1 || strpos($domain, 'staging') > -1)
        return;


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post))
        );

        $result = curl_exec($ch);
        return $result;
    }
}

$fields = array(
    array(
        "label"     =>  "Settings",
        "name"      =>  "settings",
        "type"      =>  "parent",
        "fields"    =>  array(
            array(
                "label" => "Enable New Home Feed",
                "name"  => "enable-nhf",
                "type"  => "checkbox",
                "value" => true,
            ),
            array(
                "label" => "Enable BDX",
                "name"  => "enable-bdx",
                "type"  => "checkbox",
            ),
            array(
                "label" => "FTP Host (BDX)",
                "name"  => "ftp-host",
                "type"  => "text"
            ),
            array(
                "label" => "FTP User (BDX)",
                "name"  => "ftp-user",
                "type"  => "text"
            ),
            array(
                "label" => "FTP Pass (BDX)",
                "name"  => "ftp-pass",
                "type"  => "password"
            ),
            array(
                "label" => "New Home Feed API Key",
                "name"  => "nhf-api-key",
                "type"  => "text"
            ),
        )
    ),
    array(
        "label" => "Builder number",
        "name"  => "builder-number",
        "type"  => "text"
    ),
    array(
        "label" => "Corporate Name",
        "name"  => "corporate-name",
        "type"  => "text"
    ),
    array(
        "label" => "Corporate Number",
        "name"  => "corporate-number",
        "type"  => "text"
    ),
    array(
        "label" => "Name",
        "name"  => "name",
        "type"  => "text"
    ),
    array(
        "label" => "Url",
        "name"  => "url",
        "type"  => "text"
    ),
    array(
        "label" => "Reporting Name",
        "name"  => "reporting-name",
        "type"  => "text"
    ),
    array(
        "label" => "Email",
        "name"  => "email",
        "type"  => "email"
    ),
    array(
        "label" => "State",
        "name"  => "state",
        "type"  => "state"
    ),
    array(
        "label"     => "Communities Post Type",
        "name"      => "communities",
        "type"      => "post_type",
        "fields"    => array(
            array(
                "label" => "ID",
                "name"  => "id",
                "type"  => "post_field"
            ),
            array(
                "label" => "Name",
                "name"  => "name",
                "type"  => "post_field"
            ),
            array(
                "label" => "Leads Email",
                "name"  => "leads-email",
                "type"  => "acf"
            ),
            array(
                "label" => "Style",
                "name"  => "style",
                "type"  => "acf"
            ),
            array(
                "label" => "URL",
                "name"  => "url",
                "type"  => "post_field"
            ),
            array(
                "label" => "Images",
                "name"  => "images",
                "type"  => "acf"
            ),
            array(
                "label"     => "Sales Office Post Type",
                "name"      => "sales-office",
                "type"      => "parent-array",
                "fields"    => array(
                    array(
                        "label" =>  "Address 1",
                        "name"  =>  "address_1",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "Address 2",
                        "name"  =>  "address_2",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "City",
                        "name"  =>  "city",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "State",
                        "name"  =>  "state",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "ZIP",
                        "name"  =>  "zip",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "Phone",
                        "name"  =>  "phone",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label"     =>  "Geocode",
                        "name"      =>  "geocode",
                        "type"      =>  "parent",
                        "fields"    =>  array(
                            array(
                                "label" =>  "Latitude",
                                "name"  =>  "lat",
                                "type"  =>  "acf"
                            ),
                            array(
                                "label" =>  "Longitude",
                                "name"  =>  "long",
                                "type"  =>  "acf"
                            ),

                        )
                    )
                )
            ),
            array(
                "label" => "Floor Plans Post Type",
                "name" => "floorplans",
                "type" => "post_type",
                "fields"    => array(
                    array(
                        "label" => "ID",
                        "name"  => "id",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "URL",
                        "name"  => "url",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "Name",
                        "name"  => "name",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "Base Price",
                        "name"  => "base-price",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Baths",
                        "name"  => "baths",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Half Baths",
                        "name"  => "half-baths",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Beds",
                        "name"  => "beds",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Dinning Rooms",
                        "name"  => "dining-rooms",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Sqft",
                        "name"  => "sqft",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Stories",
                        "name"  => "stories",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Master Bed Location",
                        "name"  => "master-location",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Garage",
                        "name"  => "garage",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Garage Entry",
                        "name"  => "garage-entry",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Basement",
                        "name"  => "basement",
                        "type"  => "acf"
                    ),
                    array(
                        "label"     =>  "Images",
                        "name"      =>  "images",
                        "type"      =>  "parent",
                        "fields"    =>  array(
                            array(
                                "label" => "Elevation Images",
                                "name"  => "elevation-images",
                                "type"  => "acf"
                            ),
                            array(
                                "label" => "Floorplan Images",
                                "name"  => "floorplan-images",
                                "type"  => "acf"
                            )
                        )
                    ),
                    array(
                        "label" =>  "Relationship",
                        "name"  =>  "relationship",
                        "type"  =>  "acf-relationship"
                    )
                )
            )
        )
    )
);

$BGBDX = BDX::get_instance();


register_activation_hook(__FILE__, array('\BokkaWP\BDX','install'));