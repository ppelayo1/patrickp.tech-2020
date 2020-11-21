<?php get_header('post');?>

<article class='container'>

    
    <?php 
    //get the posts ID
    global $post;
    $p = $post->ID;

    //prepare the querry
    $arg = array('p' => $p);
    $q = new WP_Query($arg);

    //Output the post 
    if($q->have_posts()){
        $q->the_post();
        the_title("<h2 class='mt-5 mb-5 text-center'>","</h2>");
        the_content();    
        the_date('',"<p class='text-right'> ",'</p>');   
        wp_reset_postdata();
    }
    ?>

    
</article>






<?php get_footer('post'); ?>