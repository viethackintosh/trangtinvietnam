<?php
if (! class_exists('IndexPage')) {
    class IndexPage extends Clients {
        protected static $instance; 
        public static function get_instance(){
            if( is_null( self::$instance ) ) self::$instance = new self();
            return self::$instance;       
        }

        public function __construct() {
            parent::__construct();
            add_action('trangtin_head', [$this,'loadAppCss']);  
            add_action('trangtin_head', [$this,'loadMetaPage']);  
            add_action('trangtin_foot', [$this, 'loadAppScript']);
            add_action('trangtin_content', [$this, 'loadPageLayout']); 
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

        /**
         * Phân loại page, tạo meta cho phép phía client đọc thông tin lấy dữ liệu xây dựng page
         */
        public function loadMetaPage() {
            
            $request = $_SERVER['REQUEST_URI']; // lấy các paramates của link
            $request = explode('/', $request); // phân tích thành mảng 

            // loại bỏ tất cả các thành phần không có giá trị
            $request = array_filter( $request,function($item) {return $item!= '';}); 
            $req =[];

            // chuyển lại index item từ 0
            foreach ($request as $item) $req[] = $item;            

            $len = count($req); // lấy số lượng item trong request

            $page = $req[$len-2]; // lấy phần từ gần cuối
            $numPage = $req[$len-1]; // lấy phần từ cuối cùng
            
            // kiểm tra nếu phần tử gần cuối là page và phần tử cuối là số
            $numPage = $page == 'page' &&  is_numeric($numPage)? $numPage: 0;
            
            if (is_home()) {               
            ?>
                <meta id=pagetype content='homepage'>
                <meta id=pageid content=<?php echo $numPage; ?>>
            <?php } 

            if (is_single()) { 
                global $post;
            ?>
                <meta id=pagetype content='article'>
                <meta id=pageid content='<?php echo $post->ID; ?>'>  
            <?php };
            if (is_category(  )) { ?>
                <meta id=pagetype content='category'>
                <meta id=pageid content='<?php echo $numPage; ?>'>  
            <?php };
             if (is_404(  )) { 
                echo '404 page';
             }
        }

        /**
         * phân loại trang và load class layout kết hợp với sự lựa chọn của người dùng để tải các giao diện phù hợp
         */
        public function loadPageLayout() {
            $layout;
            if (is_single()) {
                require_once(__DIR__ .'/single-layout-class.php');
                $layout = new SingleLayout();
                
            };
            if (is_home()) {
                require_once(__DIR__ .'/home-layout-class.php');
                $layout = new HomeLayout();
            };
            if (is_category()) {
                require_once(__DIR__ .'/category-layout-class.php');
                $layout = new CategoryLayout();
            };
            
            echo $layout->loadLayout();
        }
      
    }

    function indexClass() { 
        return IndexPage::get_instance();    
    }
}

