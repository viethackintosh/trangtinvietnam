<?php
get_header(); ?>
<?php do_action('trangtin_before_content')?>
<acticle class="article single">
    <section class="__header">
        <div class="breadcrum">breadcrum</div>
        <div class="title"><h1 class=><?php echo get_the_title(); ?></h1></div>
        <div class="meta">
            <span class="author"></span>
            <span class="date"></span>
        </div>
        <div class="feature">
            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large');?>"/>
        </div>
    </section>
    <section class="__content"><?php echo get_the_content();?></section>
    <section class="__footer">
        <div class="closest">
            <?php 
                $previous = get_previous_post();                   
                $next = get_next_post( );                    
                if (! empty($previous)) {
            ?>
            <span class="previous">
                <a href="<?php echo get_permalink($previous->ID); ?>" 
                    class="previouspost"  
                    data-type='post'>
                    <?php echo $previous->post_title;?>
                </a>
            </span>
            <?php } 
                if (! empty($next)) {
            ?>
            <span class="next">                    
                    <a href="<?php echo get_permalink($next->ID); ?>" 
                        class="previouspost" 
                        data-type='post'>
                        <?php echo $next->post_title;?>
                    </a>
                </span>
            <?php }?>
        </div>        
    </section>
</acticle>
<div class="sidebar">
    <?php get_sidebar(); ?>
</div>
<?php do_action('trangtin_after_content')?>
<?php get_footer();?>