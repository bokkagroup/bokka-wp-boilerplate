<?php
/**
 * @package Bokka_builder
 * @version a-0.0.1
 */
/*
Plugin Name: Bokka MVC
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Creates a global interface for handling MVC components in a Wordpress Theme. It's main purpose is to create a global object that will allow us to override models/views/templates in our child-themes.
Author: Codewizard
Version: 0.0.1
Author URI: http://bokkagroup.com
*/

namespace BokkaWP;
define("BGMVC_DIR", plugin_dir_path(__FILE__) );

/**
 * BokkaBuilder
 * @version 0.0.1 Singleton
 */
class MVC {

	private static $instance;



	public function __construct(){

		$upload_dir = wp_upload_dir();

		//Set directory variables
		$this->parentThemeDir =  get_stylesheet_directory();
		$this->childThemeDir =  get_template_directory_uri();

		//load mustache if nobody else has
		if( !class_exists('Mustache_Autoloader') && !class_exists('Mustache_Engine')){






            global $Handlebars;

            require_once( BGMVC_DIR . 'lib/Handlebars/Autoloader.php' );
            \Handlebars\Autoloader::register();

            $themeDir = $this->parentThemeDir;

            $Handlebars = new \Handlebars\Handlebars(
                array(
                    'loader' => new \Handlebars\Loader\FilesystemLoader($themeDir.'/patterns'),
                    'partials_loader' => new \Handlebars\Loader\FilesystemLoader(
                        $themeDir.'/patterns'

                    )
                )
            );
		}

		//load base classes
		require_once( BGMVC_DIR . 'controllers/BaseController.php' );
		require_once( BGMVC_DIR . 'views/BaseView.php' );
		require_once( BGMVC_DIR . 'models/BaseModel.php' );

		//auto load controllers
		if( !is_admin() ){
			$this->autoLoad();
		}
	}

	/**
	 * Singleton instantiation
	 * @return [static] instance
	 */
	public static function get_instance(){
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }


	public function autoLoad(){
		$this->loadFiles( 'controllers' );
        $this->loadFiles( 'models' );
        $this->loadFiles( 'views' );
		return;
	}

	/**
	 * Loads multiple files from theme directories, child theme overrides parent
	 * @param  [string] $type [ a MVC file type i.e. "controllers", "models", "templates" ]
	 * @return [void]
	 */
	public function loadFiles( $type ){
		$typeURI = strtolower( '/' . $type . '/');

		//make sure there are directories to introspect
		if( !file_exists(  $this->childThemeDir . $typeURI )
			&& !file_exists( $this->parentThemeDir . $typeURI ) ){
			error_log( 'BokkaMVC Error: '. __( 'Could not find any directories for {'. $type . '}, you may need to add a folder in your child or parent theme.', 'BOKKA_MVC' ) );
			return;
		}

		//get some arrays of our filenames
		if(  file_exists( $this->parentThemeDir . $typeURI ) )
			$parentFiles = array_diff( scandir( $this->parentThemeDir . $typeURI ), array('.', '..') );

		if(  file_exists( $this->childThemeDir . $typeURI ) )
			$childFiles =  array_diff( scandir( $this->childThemeDir . $typeURI ), array('.', '..') );

		//get the diff and remove dupes from parent (child overrides parent)
		if( isset( $parentFiles ) && isset( $childFiles ) )
			$parentFiles = array_diff( $parentFiles, $childFiles );


		//load remaining parent files
		if( isset( $parentFiles ) ){

			foreach( $parentFiles as $parentFile){

				require_once( $this->parentThemeDir . $typeURI . $parentFile);
			}
		}


		//oad remaining child files
		if( isset( $childFiles ) && $childFiles !== $parentFiles ){
			foreach( $childFiles as $childFile){
				require_once( $this->childThemeDir . $typeURI . $childFile);
			}
		}

		return;

	}

	/**
	 * Loads a single file, child theme overrides parent
	 * @param  [string] $fileName [full name of the file you are trying to include w/ extension]
	 * @param  [sting] $type     [a MVC file type i.e. "controllers", "models", "templates"]
	 * @return [void]           [description]
	 */
	public function loadFile( $fileName, $type ){
		$typeURI = strtolower( '/' . $type . '/');
		$childFileURI = $this->childThemeDir . $typeURI . $fileName;
		$parentFileURI = $this->parentThemeDir . $typeURI . $fileName;

		//make sure the file exists
		if( !file_exists( $childFileURI ) && !file_exists( $parentFileURI ) ){
			error_log( 'BokkaMVC Error: '. __( 'Could not find file {' . $typeURI . $fileName . '}, please create file.', 'BOKKA_MVC' ) );
			return;
		}

		//load it ( check child theme first )
		if(  file_exists( $childFileURI ) )
			require_once( $childFileURI );

		if(  file_exists( $parentFileURI  ) && !file_exists( $childFileURI  ) )
			require_once( $parentFileURI );

		return;

	}
}

$BGMVC = MVC::get_instance();

register_activation_hook( __FILE__, 'pluginprefix_install' );