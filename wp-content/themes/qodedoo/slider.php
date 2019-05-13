<?php 
  $slider_query = new WP_Query(array('post_type'=>'slider','posts_per_page'=>10));
  if($slider_query->have_posts()):
    $i=1;$j=1;$k=1;
?>
<div class="">
  <?php
    while($slider_query->have_posts()):
      $slider_query->the_post();
?>
  <div class="control-pad control-pad-<?php echo $k ?>" style="display:none;background-color:<?php the_cfc_field('sliderdetails','color') ?>;"> 
   
    <div class="slide_counter"> 
      <span class="big_count"> <?php echo ($k<10)?'0'.$k:$k; ?> </span>
      <span class="small_count">.<?php echo ($slider_query->post_count<10)?'0'.$slider_query->post_count:$slider_query->post_count; ?> </span>
    </div>

    <div class="control-nav">
      <?php //if($k!=1): ?>
      <a href="#myCarousel" data-slide="prev" onclick="slideAnimation(<?php echo $k-2 ?>,'<?php echo $k-1 ?>')"> <img src="<?php echo get_template_directory_uri() ?>/assets/images/arrow_left.png" onmouseover="this.src='<?php echo get_template_directory_uri() ?>/assets/images/arrow_blk_left.png'" onmouseout="this.src='<?php echo get_template_directory_uri() ?>/assets/images/arrow_left.png'"></a> 
      <?php //endif; ?>
      <?php //if($k!=$slider_query->post_count): ?>
      <a href="#myCarousel" data-slide="next" onclick="slideAnimation(<?php echo $k ?>,'<?php echo $k+1 ?>')"> <img src="<?php echo get_template_directory_uri() ?>/assets/images/arrow_right.png" onmouseover="this.src='<?php echo get_template_directory_uri() ?>/assets/images/arrow_blk_right.png'" onmouseout="this.src='<?php echo get_template_directory_uri() ?>/assets/images/arrow_right.png'"></a> 
      <?php //endif; ?>
    </div>
    
  </div>
<?php $k++;
    endwhile;
?>
  <div class="bhoechie-tab-container hidden-sm hidden-xs clearfix">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3" style="padding:0;">
        <div class="list-group">
          <div class="list-title"> USERS </div>
<?php
    while($slider_query->have_posts()):
      $slider_query->the_post()
?>
          <a href="javascipt:void(0);" onclick="slideAnimation(<?php echo $i-1 ?>,'slide<?php echo $i ?>')" onmouseenter="slideAnimation(<?php echo $i-1 ?>,'slide<?php echo $i ?>')" class="slide-menu slide-menu<?php echo $i-1 ?> list-group-item <?php if($i==1){ echo 'default-slide active'; } ?>">
            <?php the_title() ?>
          </a>
<?php $i++; endwhile; ?>
        </div>
      </div>
      <div style="overflow: hidden;">
      <div id="slide" class="col-lg-12 col-md-12 col-sm-12 col-xs-9 bhoechie-tab" style="transform: translateY(0px); transition: transform 1s">
<?php
    while($slider_query->have_posts()):
      $slider_query->the_post();
?>
          <!-- flight section -->
          <div class="slide<?php echo $j ?> slides bhoechie-tab-content active">
               <div class="row"> 
                   <div class="col-sm-6" style="padding-top:1%;padding-left:67px;">
                    <?php $cls = (get_cfc_field('sliderdetails','title-line')==1) ? 'one-line' : 'two-line'; ?>
                    <div class="tab-heading-box <?php echo $cls ?>"> 
                      <div class="heading-txt"> <?php echo strtoupper(get_the_title()) ?> <span style="margin-left:-42px;color:<?php the_cfc_field('sliderdetails','color') ?>;">.</span></div>
                    </div>
                    <div class="tabcontent-box">
                          <?php the_content() ?>
                    </div>
                </div>
                <div style="padding-right: 0;" class="col-sm-6">
                    <div class="tabcontent_thumb">
                       <img src="<?php the_post_thumbnail_url() ?>"> 
                    </div>
                </div>
               </div>
          </div>
<?php $j++; endwhile; ?>
      </div>
      </div>
  </div>

  <div id="myCarousel" class="carousel slide hidden-lg hidden-md" data-ride="carousel">
      <div class="list-title"> USERS </div>
   
    <div class="carousel-inner">
      <?php while($slider_query->have_posts()):
      $slider_query->the_post(); ?>
        <div class="item <?php if($l==1){ echo 'active'; } ?>">
          <div class="slide-heading-txt"> <?php get_the_title() ?> </div>  
               
            <?php the_content() ?>

            <div class="slide-thumb"> <img src="<?php echo the_post_thumbnail_url() ?>"></div>
        </div>
      <?php $l++; endwhile; ?>
    </div>
     
      <div class="carousel-control-box">
           <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="contol-left">
        <img src="<?php echo get_template_directory_uri() ?>/assets/images/arrow_left.png">
      </span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="contol-right">
        <img src="<?php echo get_template_directory_uri() ?>/assets/images/arrow_right.png">
      </span>
    </a>
      </div> 
      
  </div>
</div>
<?php endif; ?>
