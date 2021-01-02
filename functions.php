<?php
require(get_template_directory() . "/constants.php");
//this function returns the URL for the templates
function getTemplateURL($templateName){
    $url = null;
    
    $arg = array('post_type' => 'page',
                 'meta_key' => '_wp_page_template',
                 'meta_value' => $templateName);
    
    $q = get_posts($arg);
    
    if(isset($q[0])){
        $url = get_page_link($q[0]->ID);
    }
    return $url;
}


//allow thumbnails for posts
add_theme_support( 'post-thumbnails' ); 

//Register a sidebar for the search widgit
function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Search Box',
		'id'            => 'search_box',
		'before_widget' => '<div class="text-right mt-4">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );

function wp_add_styles(){
    //enque and register the styles
    wp_register_style('BootStrap' ,'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');
    wp_enqueue_style('BootStrap');

    wp_register_style('Line_Awesome', 'https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css');
    wp_enqueue_style('Line_Awesome');
    
    wp_register_style('open_sans',"https://fonts.googleapis.com/css?family=Open+Sans&display=swap");
    wp_enqueue_style('open_sans');
    
    wp_register_style('style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('style');
    
    
}

function wp_add_scripts(){
    //enque and register the scripts
    wp_enqueue_script("jquery");
    
    wp_register_script('popper','https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js');
    wp_enqueue_script('popper');
    
    wp_register_script('bootstrapJS','https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js');
    wp_enqueue_script('bootstrapJS');
    
    wp_register_script('constants',get_template_directory_uri() . '/constants.js');
    wp_enqueue_script('constants');
    
    wp_register_script( "portfolio_sorter", get_template_directory_uri() .'/PortfolioSorter.js', array('jquery') );
    wp_enqueue_script( 'portfolio_sorter');
    wp_localize_script( 'portfolio_sorter', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
   
    
    wp_register_script('main', get_template_directory_uri() . '/main.js');
    wp_enqueue_script('main','',array(),false,true);
}

add_action('wp_enqueue_scripts', 'wp_add_styles');
add_action('wp_enqueue_scripts', 'wp_add_scripts');


//Setup the portfolio ajax calls
add_action('wp_ajax_portfolio_ajax','ajaxCall');
add_action("wp_ajax_nopriv_portfolio_ajax", "ajaxCall");

function ajaxCall(){
    //Builds the post    
    $returnVal = [];
    $queryTerm = $_REQUEST['ajax_term'];
    
    //check nounce
    if(wp_verify_nonce($_REQUEST['nonce'],AJAX_NONCE)){
        if($queryTerm != RANDOM){
        //Builds the posts
            $arg = array('category_name' => $queryTerm);
            $q = new WP_Query($arg);

        }else{
            $arg = array('posts_per_page'   => -1,
                         'post_type'        => 'post');
            $q = new WP_Query($arg); 
        }
        if($q->have_posts()){
            //output the webPage posts
            while($q->have_posts()){
            
                $q->the_post();
                
                //add post flag
                $addPost = true;
                
                //Only include the post if it is not a blog or hidden post
                $categories = get_the_category();  
                
                for($i = 0; $i < count($categories);$i++){
                    if($categories[$i]->cat_name == BLOG || $categories[$i]->cat_name == HIDE){
                        $addPost = false;
                    }         
                }
                
                if($addPost){
                    array_push($returnVal, 
                     '<figure class="portfolioItem" data-title="'.get_the_title().'" data-pagelink="'.get_page_link().'" data-target="#modal" data-toggle="modal">' 
                     .get_the_post_thumbnail().
                     '</figure>');

                    wp_reset_postdata();
                }

            }

        }



        echo json_encode($returnVal);
    
    }else{//Nonce failed
        echo json_encode(false);
    }
    die();
}
?>
