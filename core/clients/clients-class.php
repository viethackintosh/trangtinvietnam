<?php 
/*
* khởi tạo theme 
*/
class Clients {
   
   protected static $instance; 
   public $themeUri;   
   public $themeDri;
   public $handle;

   public static function get_instance(){

      if( is_null( self::$instance ) ){
            self::$instance = new self(); 
      }

      return self::$instance; 

   }
   public function __construct() {
      $this->themeUri = get_template_directory_uri();   
      $this->themeDri = get_template_directory();    
   }

   public function moduleScript($tag, $handle, $src) {
            
      if ( $this->handle !== $handle ) {
          return $tag;
      }
      // change the script tag by adding type="module" and return it.
      $tag = '<script type="module" src="' . esc_url( $src ) . '" defer></script>';
      return $tag;
  }

   
}

