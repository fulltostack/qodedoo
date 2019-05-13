<div class="post col-md-4 col-xs-12 col-sm-4 col-lg-4  animated slideInUp duration1 eds-on-scroll " style="<?php if($i%3==1){ echo "clear:both;";  } ?>">
  <a href="<?php the_permalink() ?>">
    <div class="blog-bg">
        <?php $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(422,248) ); ?>
        <?php if($thumb_url!=''): ?>
        <div class="thumb-wrap">
          <img src="<?php echo $thumb_url[0] ?>" class="img-responsive" alt="blo1" width="422" height="248">
        </div>
        <?php endif; ?>
      <p class="blog-title">
        <b>
        <?php 
          foreach($category_detail as $cd):
              if(count($category_detail)==1):
                  echo '<a href="'.get_category_link($cd->term_id).'">'.$cd->cat_name.'</a>';
              else :
                  echo '<span><a href="'.get_category_link($cd->term_id).'">'.$cd->cat_name.'</a></span>';
              endif; 
          endforeach;
        ?>
        </b>
      </p>
      <h3 class="blog-heading">
        <a href="<?php the_permalink() ?>">
          <?php the_title() ?>
        </a>
      </h3>               
      <p class="blog-content">
        <?php echo get_the_excerpt() ?>
      </p>
      <div class="blog-publish-date">
        <div class="blog-publish">
          <div>
            <img class = " blog-reply-img img-responsive" src="<?php echo get_avatar_url($author_details->ID) ?>" alt="commet-blog">
          </div>
        </div>
        <div class="blog-date">
          <b> 
            <p><?php echo $author_details->display_name ?></p>
            <p><?php echo date('d.m.y ',strtotime(get_the_date())) ?></p>
          </b>
        </div>
      </div>
    </div>
  </a> 
</div>