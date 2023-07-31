<?php 
if (! class_exists('CategoryLayout')) {
    class CategoryLayout extends Clients {
        protected static $instance; 
        public static function get_instance(){
            if( is_null( self::$instance ) ){
                self::$instance = new self(); 
            }      
            return self::$instance;       
        }
        /**
         * đọc trong dữ liệu người dùng set mẫu layout nào cho single thì load cho phù hợp
         */ 
        public function loadLayout() {
            return $this->defaultLayout();
        }

        public function defaultLayout() { 
            global $post;
            echo 'category';
        ?>
            <article class="article single default <?php echo "post-{$post->ID}"; ?>"> 
                <section class="__header">
                    <div class="breadcrumb"><!--breadcrum --></div>
                    <h1 class=title><!-- tiêu đề bài viết --></h1>
                    <div class="meta">
                        <span class="author"><!-- tên tác giả và đường link --></span>
                        <span class="date"><!-- ngày phát hành bài viết --></span>
                    </div>
                    <figure class="feature aligncenter size-large is-resized"><!-- ảnh đại diện bài viết --></figure>
                </section>
                <section class="--wrapper">
                    <div class="--content">
                        <div class="content"><!-- nội dung bài viết --></div>
                        <div class="post-footer">
                            <div class="author-box"><!-- thông tin tác giả bài viết --></div>
                            <div class="closest"><!-- previous - next --></div>
                        </div>
                    </div>
                    <div class="sidebar"><!-- các thông tin cần thiết--></div>
                </section>
            </article>
        <?php }
    }    
}


