(function ($) {
    "use strict";

    var posWrapHeader = $('.topbar').height();
    var header = $('.container-menu-header');

    $(window).on('scroll',function(){

        if($(this).scrollTop() >= posWrapHeader) {
            $('.header').addClass('fixed-header');
            $(header).css('top',-posWrapHeader); 

        }  
        else {
            var x = - $(this).scrollTop(); 
            $(header).css('top',x); 
            $('.header').removeClass('fixed-header');
        } 

    });
    
    $('.btn-show-menu-mobile').on('click', function(){
        $(this).toggleClass('is-active');
        $('.wrap-side-menu').slideToggle();
    });

    var arrowMainMenu = $('.arrow-main-menu');

    for(var i=0; i<arrowMainMenu.length; i++){
        $(arrowMainMenu[i]).on('click', function(){
            $(this).parent().find('.sub-menu').slideToggle();
            $(this).toggleClass('turn-arrow');
        })
    }

    $(window).resize(function(){
        if($(window).width() >= 992){
            if($('.wrap-side-menu').css('display') == 'block'){
                $('.wrap-side-menu').css('display','none');
                $('.btn-show-menu-mobile').toggleClass('is-active');
            }
            if($('.sub-menu').css('display') == 'block'){
                $('.sub-menu').css('display','none');
                $('.arrow-main-menu').removeClass('turn-arrow');
            }
        }
    });

    $( "a.nav-hover" )
    .mouseover(function() {
        hideDivNav();
        var target = $(this).attr('data-id');
        showNavSide($('#' + target));
    })
    .mouseout(function() {
        //var target = $(this).attr('data-id');
        //hideNavSide($('#' + target));
    });

    $('div.div-nav-side')
    .mouseleave(function() {
        hideDivNav();
    });

    var showNavSide = function (elements)
    {
        elements.show();
    }

    var hideNavSide = function (elements)
    {
        elements.hide();
    }

    var hideDivNav = function ()
    {
        $('div.div-nav-side').hide();
    }

})(jQuery);

$(".main_menu li a").click(function() {
    $(this).parent().addClass('active').siblings().removeClass('active');
 });
 $(".wrap_header_mobile li a").click(function() {
    $(this).parent().addClass('active').siblings().removeClass('active');
 });
 $(".add-active li a").click(function() {
    $(this).parent().addClass('active').siblings().removeClass('active');
 });