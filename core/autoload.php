<?php

if (! class_exists('autoloadcore')) {
	class autoloadcore {

		protected static $instance;
		public $childUri;
		public $childDir;

		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
         $this->childUri = get_stylesheet_directory_uri();
         $this->childDir = get_stylesheet_directory();
         $this->autoLoadClasses();
		}

		// kiểm tra plugin để 
		public function autoLoadClasses() {

			$core = "{$this->childDir}/core/";
			$classJson = $core . 'classes.json'  ;           
		
			if (! file_exists($classJson)) return;
			$_classes = json_decode(file_get_contents($classJson),true);      
			
			foreach ($_classes as $key => $isfile) {				
				if ( file_exists($core. '/' . $isfile) ) {   
					require_once($core . '/' . $isfile);  					
						$files = explode('/',$isfile);
						$file =$files[count($files)-1];
						$initclass = str_replace('-','',str_replace('.php','',$file));   
						if (function_exists($initclass)) $initclass();
					}
											
			}
       
		}
	}
}
return autoloadcore::get_instance(); 


