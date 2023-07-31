<?php 
if (!class_exists('HomeLayout')) {
    class HomeLayout extends Clients {
        protected static $instance; 
        public static function get_instance(){
            if( is_null( self::$instance ) ){
                self::$instance = new self(); 
            }      
            return self::$instance;       
        }

        public function loadLayout() {
            return $this->defaultLayout();
        }

        public function defaultLayout() { ?>
            <section class="page__header">
                <!-- 
                    header của page, đặt banner quảng cáo, gird bài viết mới nhất
                -->
            </section>
            <article class="article homepage default">
               home page
            </article>
        <?php }
    }
} 


