
$(".navbar-nav li a").click(function () {
    $(this).parent().addClass('active').siblings().removeClass('active');
});

$(document).on("click", 'input.custom-control-input', function () {
  if ($(this).is(":checked")) {
    $('label.active').removeClass('active');
    $(this).next("label.custom-control-label").addClass("active");
  }
});

function openNav() {
  document.getElementById("Sidenav").style.width = "250px";
}
function closeNav() {
  document.getElementById("Sidenav").style.width = "0";
}

//LightSlider Review
$(document).ready(function() {
  $('#banner').lightSlider({
    item:1,
    loop:true,
    slideMove:1,
    easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
    speed:1000,
    speed:1000,
    controls:false,
    pause:5000,
    auto:true,
    loop:true,
    responsive : [
      {
        breakpoint:800,
        settings: {
          item:1,
          slideMove:1,
          slideMargin:6,
        }
      },
      {
        breakpoint:480,
        settings: {
          item:1,
          slideMove:1
        }
      }
    ]
  });  
  
  //LightSlider product1
  var slider1 = $('#product1').lightSlider({
    item:4,
    slideMove:1,
    controls: false,
    pager: false,
      responsive : [
        {
            breakpoint:800,
            settings: {
                item:3,
                slideMove:1,
              }
        },
        {
            breakpoint:480,
            settings: {
                item:2,
                slideMove:1
              }
        }
      ]
    });
    
		$('#goToPrevSlide1').on('click', function () {
			slider1.goToPrevSlide();
		});
		$('#goToNextSlide1').on('click', function () {
			slider1.goToNextSlide();
		});
  
  //LightSlider product2
  var slider2 = $('#product2').lightSlider({
    item:4,
    slideMove:1,
    controls: false,
    pager: false,
      responsive : [
        {
            breakpoint:800,
            settings: {
                item:3,
                slideMove:1,
              }
        },
        {
            breakpoint:480,
            settings: {
                item:2,
                slideMove:1
              }
        }
      ]
    });
    
		$('#goToPrevSlide2').on('click', function () {
			slider2.goToPrevSlide();
		});
		$('#goToNextSlide2').on('click', function () {
			slider2.goToNextSlide();
		});

  //LightSlider product3
  var slider3 = $('#product3').lightSlider({
    item:4,
    slideMove:1,
    controls: false,
    pager: false,
      responsive : [
        {
            breakpoint:800,
            settings: {
                item:3,
                slideMove:1,
              }
        },
        {
            breakpoint:480,
            settings: {
                item:2,
                slideMove:1
              }
        }
      ]
    });
    
		$('#goToPrevSlide3').on('click', function () {
			slider3.goToPrevSlide();
		});
		$('#goToNextSlide3').on('click', function () {
			slider3.goToNextSlide();
		});

  //LightSlider product4
  var slider4 = $('#product4').lightSlider({
    item:4,
    slideMove:1,
    controls: false,
    pager: false,
      responsive : [
        {
            breakpoint:800,
            settings: {
                item:3,
                slideMove:1,
              }
        },
        {
            breakpoint:480,
            settings: {
                item:2,
                slideMove:1
              }
        }
      ]
    });
    
		$('#goToPrevSlide4').on('click', function () {
			slider4.goToPrevSlide();
		});
		$('#goToNextSlide4').on('click', function () {
			slider4.goToNextSlide();
		});

  //LightSlider product detail vertical
    var slider5 = $('#vertical').lightSlider({
      gallery:true,
      item:1,
      vertical:true,
      verticalHeight:500,
      vThumbWidth:145,
      thumbItem:3,
      thumbMargin:4,
      slideMargin:0,
      controls: false,
    });
    
		$('#goToPrevSlide5').on('click', function () {
      slider5.goToPrevSlide();
		});
		$('#goToNextSlide5').on('click', function () {
      slider5.goToNextSlide();
		});
    
    //Js Sort Select Option name='sort_product'
    $('select[name="sort_product"]').on('change', function() {
      $(this).closest('form').submit();
    });

  //Js Sort Select Option name='sort_product'
  $('select[name="sort_product"]').on('change', function() {
    $(this).closest('form').submit();
  });

  
});

function openNav() {
  document.getElementById("mySidenav").style.width = "100%";
  }

  function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  }
