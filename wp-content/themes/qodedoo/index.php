<?php get_header() ?>

    <div class="inner-wrapper">
        <section class="first-slide main-img-bg">
          <div class="main main-bg-img"> 
            <div class="container-fluid"> 
              <div class="logo padding-btn"> <a href="#"> <img src="<?php echo get_template_directory_uri() ?>/assets/images/logo.png"> </a> </div>
               <div class="blog-txt">                   
                <span class="blog-dot jumbo-txt">Blog</span>
                <span class="circle-dot"></span>
              </div>                
            </div>
            <div class="dot-img">
              <img src="<?php echo get_template_directory_uri() ?>/assets/images/dots_bg.png">            
            </div>
          </div>
        </section>

        <section class="blog-section">
          <div class="blog-wrapper">
            <?php if(have_posts()): $i=1;?>
              <div class="post-item">
                <?php
                        while(have_posts()):
                            the_post();
                            $category_detail=get_the_category($post->ID);
                            $author_details=get_user_by( 'ID', $post->post_author );
                ?>
                  <?php include 'post-wrap.php'; ?>
                <?php $i++;endwhile; ?>
              </div>
            <?php endif;?>
            <div style="clear:both"></div>
            <div class="load-more">
              <span class="load-more-btn">
                <?php echo next_posts_link( 'Load More'); ?>
              </span>
            </div>
          </div>
        </section>    

<?php get_footer() ?>