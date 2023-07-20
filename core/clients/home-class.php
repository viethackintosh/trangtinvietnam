<?php
if (! class_exists('Home')) {
    class Home extends Clients {
        protected static $instance; 
        public static function get_instance(){
            if( is_null( self::$instance ) ) self::$instance = new self();
            return self::$instance;       
        }

        public function __construct() {
            parent::__construct();
            $this->handle = 'app-js';
            add_action('trangtin_head', [$this,'loadAppCss']);  
            add_action('trangtin_foot', [$this, 'loadAppScript']);
        }

        /**
         * load app.css in website
         */
        public function loadAppCss() {
            ?>
            <link rel=stylesheet id='app-css' href='<?php echo $this->themeUri; ?>/assets/scss/app.min.css'>
            <?php
        }

        /**
         * load app.js in website
         */
        public function loadAppScript() { ?>
            <script type=module defer src='<?php echo $this->themeUri; ?>/assets/script/app.js'></script>
        <?php }

    }

    function homeClass() { 
        return Home::get_instance();    
    }
}

