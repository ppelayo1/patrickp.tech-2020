<?php get_header('home'); ?>

    <div id='welcomeBox' class='container-fluid'>
            
        <h1 class='text-center'>Web Developer</h1>
            

    </div>

<div id='mainBody' class='container'>
    <h2 class='text-center'>Portfolio</h2>
    <?php
        $nonce = wp_create_nonce(AJAX_NONCE);
    
        echo "<div id='buttons'>
                 <a id='favorites' data-nonce='".$nonce."' data-ajax_term='".FAVORITES."' class='sortButton buttonActive text-center'  target='target'><span>MyFavorites</span></a>
                 
                 <a id='webSites' data-nonce='".$nonce."' data-ajax_term='".WEB_PAGE_CAT."' class='sortButton buttonInActive text-center'  target='target'><span>Web Sites</span></a>
                 
                 <a id='javascript' data-nonce='".$nonce."' data-ajax_term='".JAVASCRIPT."' class='sortButton buttonInActive text-center'  target='target'><span>Javascript</span></a>
                 
                 <a id='random' data-nonce='".$nonce."' data-ajax_term='".RANDOM."' class='sortButton buttonInActive text-center'  target='target'><span>Random</span></a>
             </div>"
    ?>
    <iframe style="display:none;" name="target"></iframe>
    <?php
    
        
        //Builds the posts
        $arg = array('category_name' => FAVORITES);
        $q = new WP_Query($arg);
        
        if($q->have_posts()){
            
            echo '<section id="portfolio">';
            //output the webPage posts
            while($q->have_posts()){
                $q->the_post();
                
                echo '<figure class="portfolioItem" data-title="'.get_the_title().'" data-pagelink="'.get_page_link().'" data-target="#modal" data-toggle="modal">';
                the_post_thumbnail();
                echo ' </figure>';
            wp_reset_postdata();
            }
            echo '</section>';
        }
    ?>
    
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id='thePostTitle' class="modal-title">unset</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <img href=<? ?>
            </button>
          </div>
          <div class="modal-body">
            <iframe id='thePost' src=""></iframe>
             <div id='loadingMessage'><h1>Loading...</h1></div>
          </div>
        </div>
      </div>
    </div>
    

    <?php
        /*
        //Favorite Posts, Web page first
        $arg = array('tag' => FAVORITES, 'category_name' => WEB_PAGE_CAT);
        $q = new WP_Query($arg);
        $firstPost = true;
        $counter = 0;
        if($q->have_posts()){

            //output the webPage posts
            while($q->have_posts()){
                $q->the_post();
                if($counter == 0){
                    echo '<div class="row">';
                    echo "<figure class='col-md-3'>";
                    echo "<a href='" . get_page_link() . "' target='_blank'>"  . get_the_post_thumbnail() . "</a>";
                    echo "</figure>";
                }

                if($firstPost){
                    $firstPost = false;
                }else{
                    echo "<figure class='col-md-3 offset-md-1'>";
                    echo "<a href='" . get_page_link() . "' target='_blank'>" . get_the_post_thumbnail() . "</a>";
                    echo "</figure>";
                }

                $counter++;

                if($counter == 3){
                    $counter = 0;
                    $firstPost = true;
                    echo "</div>";
                }
              }
            wp_reset_postdata();
        }

        //Favorite Posts, Games second
        $arg = array('tag' => FAVORITES, 'category_name' => GAMES_CAT);
        $q = new WP_Query($arg);
        $firstPost = true;
        $counter = 0;
        if($q->have_posts()){

            //output the webPage posts
            while($q->have_posts()){
                $q->the_post();
                if($counter == 0){
                    echo '<div class="row">';
                    echo "<figure class='col-md-3'>";
                    echo "<a href='" . get_page_link() . "' target='_blank'>"  . get_the_post_thumbnail() . "</a>";
                    echo "</figure>";
                }

                if($firstPost){
                    $firstPost = false;
                }else{
                    echo "<figure class='col-md-3 offset-md-1'>";
                    echo "<a href='" . get_page_link() . "' target='_blank'>" . get_the_post_thumbnail() . "</a>";
                    echo "</figure>";
                }

                $counter++;

                if($counter == 3){
                    $counter = 0;
                    $firstPost = true;
                    echo "</div>";
                }
              }
            wp_reset_postdata();
        }

        */
    ?>
<?php get_footer(); ?>