<?php get_header() ?>
<?php
  wp_reset_postdata();
  $category_detail=get_the_category($post->ID);
  $author_details=get_user_by( 'ID', $post->post_author );
?>
  <div class="inner-wrapper">
    <section class="first-slide blog-detail-height">
      <?php 
        $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(422,248) );
      ?>
      <div class="blog-detail-img blog-bg-img" style="background-image:url(<?php echo isset($thumb_url[0])?$thumb_url[0]:''; ?>);"> 
        <div class="container-fluid"> 
           <div class="blog-detail-menu">                   
            <ul>
            <?php foreach($category_detail as $cd): ?>
              <li>
                <a href="<?php echo get_category_link($cd->term_id) ?>" class="category-link"><?php echo $cd->cat_name.' '; ?></a>
              </li>
            <?php endforeach; ?>
            </ul>
            <div class="heading-for-blog-pos">
              <p><?php the_title() ?></p>
            </div>
          </div>                
        </div>
        <div class="blog-img">
          <div>
            <img src="<?php echo get_avatar_url($author_details->ID) ?>">                
          </div>
        </div>
      </div>          
    </section>

    <section class="full-content-blog">
      <div class="">
        <div class="commenter-detail">
          <p class="commenter-name"><b><?php echo $author_details->display_name ?></b></p>
          <p class="date"><?php echo date('d M Y ',strtotime(get_the_date())) ?></p>
        </div>

        <div class="blog-detail-content">
          <?php the_content() ?>
        </div>

        <div class="back-btn">
          <button onclick="history.back()">
            Back
          </button>
        </div>

        <div>
          <p class="recommended">
            Recommended
          </p>
        </div>
      </div>
    </section>

    <?php
      $cats = get_the_category($post->ID);
      if($cats):
    ?>
    <section class="blog-section">
      <div class="blog-wrapper detail-blog">           
        <div class="" style="display: inline-block;width: 100%;">
    <?php
      $first_cat = $cats[0]->term_id;
      $args=array(
      'category__in' => array($first_cat),
      'post__not_in' => array($post->ID),
      'posts_per_page'=>3,
      'caller_get_posts'=>1
      );
      $my_query = new WP_Query($args);
      if( $my_query->have_posts() ):
      while ($my_query->have_posts()) : $my_query->the_post();
        $category_detail=get_the_category($post->ID);
        $author_details=get_user_by( 'ID', $post->post_author );
        $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(422,248) );
    ?>
      <?php include 'post-wrap.php'; ?>
    <?php
      endwhile;
      endif;
      wp_reset_query();
      endif;
    ?>
        </div>            
      </div>
    </section>


<?php get_footer() ?>