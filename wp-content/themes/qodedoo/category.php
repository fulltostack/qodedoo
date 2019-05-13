<?php get_header() ?>
    <?php 
      $category = get_queried_object();
      $new_query = new WP_Query(array('term_id'=>$category->term_id));
      if($new_query->have_posts()):
      while($new_query->have_posts()):
      $new_query->the_post();
        $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(422,248) );
        if(isset($thumb_url[0])) break;
      endwhile;
      wp_reset_postdata();
      endif;
    ?>
    <div class="inner-wrapper">
        <section class="first-slide category-blog-detail-height">
          <div class="category-blog-detail-img blog-bg-img" style="background-image: url(<?php echo $thumb_url[0] ?>);"> 
            <div class="container-fluid animated slideInUp duration1 eds-on-scroll"> 
              <div class="category"> 
                <p class="category-heading">CATEGORY</p>              
                <div class="category-txt"> 
                  <span><?php echo $category->name ?></span>
                  <span class="music-dot"></span>

                </div>
              <div class="reset-btn">
                <button>
                  Reset
                </button>
              </div>         
              </div> 
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

        <footer>
          <div class="blog-wrapper">
            <div class="footer">
              <ul>
                <li>
                  <a href="#" class="active"> Home</a>
                </li>
                <li>
                  <a href="#"> Blog</a>
                </li>
              </ul>
              <div class="footer-menu">
                <p>
                  <a href="#">Term&Condition</a>
                </p>
                <p>
                  <a href="#">Privacy Policy </a>
                </p>
              </div>
              <div class="copyright">
                <p>
                  Copyright 2018 qodedoo Pty Ltd. All Rights Reserved.
                </p>
              </div>
            </div>
          </div>
        </footer>      
      </div>

<?php get_footer() ?>