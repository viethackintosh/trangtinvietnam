<?php
/** 
 * định nghĩa thêm các route cho wp-json
 */
if (! class_exists('PostRoute')) {
    class PostRoute  {
        protected static $instance; 
        public static function get_instance(){
            if ( is_null( self::$instance ) ) self::$instance = new self();
            return self::$instance;       
        }

        public function __construct() {
            add_action('rest_api_init', [$this,'addFieldInPost']);
        }

        public function addFieldInPost() {
            // lấy thêm chi tiết của ảnh đại diện
            register_rest_field(
                'post',
                'featured_media',
                ['get_callback'=> [$this,'featureImageData']]
            );

            // lấy thêm các thông tin của tác giả bài viết
            register_rest_field(
                'post',
                'author',
                ['get_callback'=> [$this,'authorData']]
            );

            // lấy thêm thông tin category của bài viết
            register_rest_field(
                'post',
                'categories',
                ['get_callback'=>[$this,'categoryData']],
            );  
            
            // lấy thêm thông tin category của bài viết
            register_rest_field(
                'post',
                'closest',
                ['get_callback'=>[$this,'getClosestPost']],
            );     
            
            // lấy thêm thông tin breadcrum bài viết
            register_rest_field(
                'post',
                'breadcrumb',
                ['get_callback'=>[$this,'getBreadCrumb']],
            );     

            // lấy thêm thông tin số lượng bài viết
            register_rest_field(
                'post',
                'count',
                ['get_callback'=>[$this,'getCount']],
            );     
        }
        
        // cung cấp thêm dữ liệu của ảnh đại diện
        public function featureImageData($post, $fieldName, $request ) {
            if (!isset ($post[$fieldName])) return;
            $id = (int) $post[$fieldName] !== 0? (int) $post[$fieldName]: 3888;            
            $image = get_post($id);// nếu ảnh đại diện không có thì trả ảnh no image
                        
            $srcs = get_intermediate_image_sizes();
            $mediaSize = [];
            foreach($srcs as $src) {
                $srcSet = str_replace(home_url( ),'', wp_get_attachment_image_src($image->ID,$src));
                $mediaSize[$src] =  $srcSet[0];
            }
           
            return [     
                'id'=>$id,
                'src'=>$mediaSize,
                'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
		        'caption' => $image->post_excerpt,
                'srcset' =>str_replace(home_url( ), '', wp_get_attachment_image_srcset($id)),
            ];
        }

        //cung cấp thêm dữ liệu của tác giả bài viết
        public function authorData($post, $fieldName, $request) {
            if (empty($post[$fieldName])) return;
            $id = (int) $post[$fieldName];
            if (! $user = get_user_by('id',$id)) return;
            $data =$user->data;
            $link = str_replace(home_url(  ),'', get_author_posts_url($id));
            return [  
              
                'ID' => $data->ID,
                'display_name' =>$data->display_name,
                'user_name' => $data->user_login,
                'link' => $link
            ];
        }

        public function categoryData($post, $fieldName, $request) {
            if (empty($post[$fieldName])) return;
            $catList = $post[$fieldName];
            if (empty($catList)) return;
            $categories = [];
            foreach ($catList as $catId) {
                $cat_id = (int)$catId;
                $category = get_term( $cat_id );
                array_push($categories,[
                    'term_id' => $category->term_id,
                    'name' => $category->name,
                    'slug'=>$category->slug,
                ]);
            }
            return $categories;

        }
       
        public function getClosestPost($postout, $fieldName, $request) {
            $closest = [];
            $post = get_post( $postout['id'] );
            $nextPost = get_next_post();
            $previous =  get_previous_post();
            if ($nextPost) $closest['nextpost'] = [
                'post_name'=>$nextPost->post_name, 
                'ID'=> $nextPost->ID, 
                'title'=> $nextPost->post_title,
                'link' => str_replace(home_url(),'', get_permalink($nextPost)),
            ];
            if ($previous) $closest['previous'] =[
                'post_name'=>$previous->post_name, 
                'ID'=> $previous->ID, 
                'title'=> $previous->post_title,
                'link' => str_replace(home_url(),'', get_permalink($previous))
            ];

            return $closest;
        }

        public function getBreadCrumb($postout, $fieldName, $request) {
            $id = $postout['id'] ; 
            $cats = [];
            $postCat = wp_get_post_terms($id,'category')[0];
            $cats = $this->getParentCategories($cats, $postCat );
            $cats = array_map( [$this,'filterCategoriesField'],$cats);
           return $cats;
        }

        /**
         * $cats danh sách categor
         * trả về danh sách category cha
         */
        public function getParentCategories($cats, $catObj ) {
            $temp = $cats;
            if (! empty($catObj)) array_unshift($temp, $catObj);
            if ($catObj->parent !== 0) {
                $cate = get_term($catObj->parent,'category');
                $temp = $this->getParentCategories($temp,$cate);
            }
            return $temp;
        }

        public function filterCategoriesField($cat) {
            return [
                'id'=>$cat->term_id, 
                'name'=> $cat->name, 
                'slug'=>$cat->slug,
                'count'=>$cat->count,
                'link'=>str_replace(home_url( ),'', get_category_link($cat->term_id))
            ];
        }

        // đếm 
        public function getCount($postout, $fieldName, $request) {
            return wp_count_posts()->publish;
        }
    }

    function postRouteClass() { 
        return PostRoute::get_instance();    
    }
}

/**
 * fields
 * author: tác giả
 * id: 
 * excerpt: đoạn trích bài viết
 * title: tên bài viết (post_title)
 * link: đường dẫn bài viết
 * categories: danh mục bài viết
 * featured_media: ảnh đại diện bài viết
 * slug: tên bài viết (post_name)
 * date: ngày phát hành bài viết
 * closest: các bài viết gần nhất
 * breadcrumb
 */
//http://hackintosh/wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link,categories,featured_media,slug,date,nextpost&per_page=2
//http://hackintosh/wp-json/wp/v2/posts/3737?_fields=author,id,excerpt,title,link,categories,featured_media,slug,date,nextpost lấy bài viết theo id