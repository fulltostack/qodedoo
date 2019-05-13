<!-- <div class="sidebar">
  <div class="sidebar-inner"> 
    <div class="sidebar-menu">
         <div class="sidebar-menu-container">
          <nav id="mobile-menu">
            <?php wp_nav_menu( array( 
            			'theme_location' => 'main_menu',
            			'menu_class' => 'menu-container mobile-menu list-unstyled' 
            		) ); 
            	?>
          </nav>
          <div class="spacer"></div>
         </div>
    </div>
   <div class="bar"> 
       <span style="display: none;" class="clicked"></span>
       <span class="not-clicked"></span>
  </div>
  <div class="text-center ic_lg"> 
      <img src="<?php echo get_template_directory_uri() ?>/assets/images/icon-logo.png">
  </div>

  <?php 
  	$social_query = new WP_Query(array('post_type'=>'social_accounts'));
  	if($social_query->have_posts()):
  ?>
    <div class="socila_menu hidden-xs"> 
       <ul class="list-unstyled">
  <?php
  		while($social_query->have_posts()):
  			$social_query->the_post();
  ?>
  			<li>
  				<a href="<?php the_cfc_field('social','link') ?>">
  					<img src="<?php the_cfc_field('social','icon') ?>" />
  				</a>
  			</li>
  <?php
  		endwhile;
  ?>
  		</ul>
  	</div>
  <?php
  	endif;
  ?>
  </div>  
</div> -->


<div class="sidebar"> 
  <a class="nav_toggle">
    <div class="hamburger">
      <div class="hamburger1"></div><div class="hamburger2"></div><div class="hamburger3"></div>
    </div>    
   </a>
   <div class="text-center ic_lg"> 
      <img src="<?php echo get_template_directory_uri() ?>/assets/images/icon-logo.png">
  </div>

   <?php 
    $social_query = new WP_Query(array('post_type'=>'social_accounts'));
    if($social_query->have_posts()):
  ?>
    <div class="socila_menu hidden-xs"> 
       <ul class="list-unstyled">
  <?php
      while($social_query->have_posts()):
        $social_query->the_post();
  ?>
        <li>
          <a href="<?php the_cfc_field('social','link') ?>">
            <img src="<?php the_cfc_field('social','icon') ?>" />
          </a>
        </li>
  <?php
      endwhile;
  ?>
      </ul>
    </div>
  <?php
    endif;
  ?>
</div>

<nav class="main">
  <div class="menu-main-menu-container">
       <nav id="menu-main-menu">
            <?php wp_nav_menu( array( 
                  'theme_location' => 'main_menu',
                  'menu_class' => 'menu-container menu list-unstyled' 
                ) ); 
              ?>
      </nav>
  </div>
</nav>