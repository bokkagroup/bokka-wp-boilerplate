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



$BGBDX = BDX::get_instance();


register_activation_hook(__FILE__, array('\BokkaWP\BDX','install'));