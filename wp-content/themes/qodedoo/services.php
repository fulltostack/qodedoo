<?php $services_query = new WP_Query(array('post_type'=>'services')) ?>
<?php if($services_query->have_posts()): ?>
<div class="container-fluid"> 
	  <div class="text-center sec-title"> USES </div>
	  <!-- <div style="overflow:hidden"> -->
	   <div class="features_box row  animated slideInUp duration2 eds-on-scroll ">  
	        <?php while($services_query->have_posts()): $services_query->the_post(); ?>
	          <div class="col-md-2 col-sm-2 col-xs-6">
	            <span class="feat_icon"> <img src="<?php the_post_thumbnail_url() ?>"> </span>
	            <span> <?php the_title() ?></span>
	         </div>
	        <?php endwhile; ?>
	   </div>
	  <!-- </div> -->
</div>
<?php endif; ?>