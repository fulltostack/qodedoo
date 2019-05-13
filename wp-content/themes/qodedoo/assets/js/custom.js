//****************** Js for icon change toggle ******************** //
 


    $('.nav_toggle').click(function() {
      $("body").toggleClass("nav_on");
  });
  

  /*jQuery(document).ready(function(){
    jQuery('.not-clicked').click(function(){
      jQuery('.sidebar-inner').addClass('nav-on');
      jQuery('.clicked').show();
      jQuery('.not-clicked').hide();
    })
    jQuery('.clicked').click(function(){
      jQuery('.sidebar-inner').removeClass('nav-on');
      jQuery('.not-clicked').show();
      jQuery('.clicked').hide();
    })
  })*/


//****************** Js for Sidebar Toggle******************** //

  /*var rn = 0;
  jQuery('.bar span').on('click', function() {
    if(rn == 0)
    {
      if(jQuery('.sidebar-toggle').is(':visible'))
      {
        jQuery('.sidebar-toggle').hide('slide',{},1000);
        rn = 1;
        setTimeout(function(){
          rn = 0;
        });
      }
      else
      {
        jQuery('.sidebar-toggle').show('slide',{},500);
      }
    }
  });*/

//****************** Js for Slide height******************** //

  jQuery(document).ready(function(){
    jQuery('.first-slide').css('height', jQuery(window).height());
    // Comma, not colon ----^
  });
  jQuery(window).resize(function(){
    jQuery('.first-slide').css('height', jQuery(window).height());
    // Comma, not colon ----^
  });  

//****************** Js for scroll******************** //

 document.querySelectorAll('a[href^="#second"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

 //****************** Js for Third Slide Tabs******************** //

	jQuery(document).ready(function() {
	    jQuery("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
	        e.preventDefault();
	        jQuery(this).siblings('a.active').removeClass("active");
	        jQuery(this).addClass("active");
	        var index = jQuery(this).index();
	        jQuery("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
	        jQuery("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
	    });
	});



/* My JS */
//var height = 578;
function slideAnimation(n,e)
{
    var len = jQuery('.slides').length;
    console.log('n ',n);
    console.log('e ',e);
    if(n == len) n=0;
    if(n == -1) n=len-1;
    if(e == (len+1)) e=1;
    if(e == 1) e=len;

    e = 'slide'+e;

    $('.default-slide').removeClass('default-slide');
    $('.slide-menu').removeClass('active');
    $('.slide-menu'+n).addClass('default-slide');
    $('.slide-menu'+n).addClass('active');
    var height = $('.'+e).height();
    //var move = height*n+(10*n);
    move = 0;
    for(var i=1;i<=n;i++)
    {
      move = move+$('.slide'+i).height();
    }

    move = move+(10*n);

    /*if(n==0)
    {
      move = 0;
    }
    if(n==1)
    {
      move = -558;
    }
    if(n==2)
    {
      move -1116;
    }
    if(n==3)
    {
      move -1674;
    }
    if(n==4)
    {
      move = -2252;
    }
    if(n==5)
    {
      move = -2800;
    }
    if(n==6)
    {
      move = -3358;
    }
    if(n==7)
    {
      move = -3916;
    }
    if(n==8)
    {
      move = -4476;
    }
    if(n==9)
    {
      move = -5062;
    }*/
    var cp_num = n+1;
    $('#slide').height(height);
    $('#slide').css('transform','translateY(-'+move+'px)');
    $('#slide').css('transition','transform 2s');
    $('.control-pad').hide();
    $('.control-pad-'+cp_num).show();
}

jQuery(document).ready(function($){
  setInitialHeight();
  setTimeout(function(){ 
    setInitialHeight();
  },1000)
});

function setInitialHeight()
{
  $('#slide').css('height',$('.slide1').height());
  $('.control-pad-1').show();
}

jQuery(document).ready(function(){
        
  var menu = $('#mobile-menu'),
    items = menu.find('.mobile-menu li'),
    tl = new TimelineLite();

    tl.add(TweenLite.to(menu, 0.5, {autoAlpha:1, ease: Quart.easeOut}))
      .staggerFrom(items, items.length * 0.1, { x: "-50", opacity:0, ease: Quart.easeOut}, 0.10);
    
    $('.not-clicked').on('click',function(){
      tl.restart();
    })
    $('.clicked').on('click',function(){
      tl.timeScale(1.6).reverse();
    });

    setTimeout(function(){
      $('.preloader').hide();
    },2000);
})

jQuery(document).ready(function(){ 
  jQuery('#myCarousel').carousel({
      interval: false
  }); 
})

jQuery(window).scroll(function() {
    if(is_front_page == 1)
    {
      var intro_scroll = jQuery('.intro-container').position().top;
      var services_scroll = jQuery('#slide').position().top;
      if(jQuery(window).scrollTop() > intro_scroll) {
        jQuery('.intro-container').css('transform','translateY(0px)');
        jQuery('.intro-container').css('transition','1s');
      };
    }
});

jQuery(document).ready(function(){
  setTimeout(function(){
    if(is_front_page == 1)
    {
      var height_i = jQuery('.intro-container').height();
      var height_s = jQuery('.iphone-img').height();
      jQuery('.intro-container').css('height',height_i+'px');
      jQuery('.intro-container').css('transform','translateY('+height_i+'px)');
    }
  },50);
});

jQuery(document).ready(function(){
  var s = skrollr.init({
      forceHeight: false
  });
})