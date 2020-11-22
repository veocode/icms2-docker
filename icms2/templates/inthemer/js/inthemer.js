$.fn.extend({
    animateCss: function (animationName, callback) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
			var $block = $(this);
            $block.removeClass('animated ' + animationName).addClass('anim-done');
			$block.removeAttr('data-anim').removeAttr('data-anim-speed').removeAttr('data-anim-delay').removeAttr('data-anim-class');
			$block.css('animation-duration', '').css('animation-delay', '');
			if (callback){ callback(); }
        });
        return this;
    }
});

function InthemerPageController(){

	function init(){
		fixRows();
		animatePage();
		pinFooters();
		initParallax();
		initSliders();
		initMenus();
		initTabs();
		initAccordions();
	}

	function fixRows(){
		$('.page-row:has(.page-nav)').css('overflow', 'visible');
		$('.page-row:has(ul.menu)').css('overflow', 'visible');
	}

	function pinFooters(){

		$('.pin-down').each(function(){

			var $section = $(this);

			var wH = $(window).height();

			if ($section.height() + $section.offset().top < wH){
				$section.css({
					position: 'absolute',
					bottom: 0,
					width: '100%'
				});
			}

		});

	}

	function initTabs(){
		
		if ($('.page-tabs').length == 0) { return; }
		
		$('.page-tabs .tab-link').click(function(e){
			
			e.preventDefault();
			
			var $tabLink = $(this);
			var $tabs = $tabLink.parents('.page-tabs');
			
			$tabs.find('.tab-pane.active').removeClass('active');
			$tabs.find('.tab-pane').eq($tabLink.index()).addClass('active');
			$tabs.find('.tab-link.active').removeClass('active');
			$tabLink.addClass('active');
				
		});
		
	}

	function initAccordions(){
		
		if ($('.page-accordion').length == 0) { return; }
		
		$('.page-accordion .pane-title').click(function(e){
			
			e.preventDefault();
			
			var $pane = $(this).parent('.accordion-pane');
			var isOpened = $pane.hasClass('active');
			var isLinked = $pane.hasClass('linked');
			
			if (isOpened && isLinked){
				return;
			}
			
			if (isLinked){
				
				var $active = $pane.siblings('.active');
				
				$active.find('.pane-body').slideToggle(200, function(){
					$active.removeClass('active');
				});
				
			}
			
			$pane.find('.pane-body').slideToggle(200, function(){
				$pane.toggleClass('active');
			});
			
		});
		
	}

	function initMenus(){

		if ($('.page-nav').length == 0) { return; }

        $('.page-nav li').hover(function(){
            $(this).children('a').addClass("hover");
        }, function(){
            $(this).children('a').removeClass("hover");
        });

        $('.page-nav').each(function(){

            var $menu = $(this);
            var $button = $menu.find('.menu-button');

            $button.click(function(e){

                e.preventDefault();

                var $overlay = $('<div></div>').addClass('page-nav-overlay');
                var $menuClone = $menu.find('ul.menu').clone().appendTo($overlay);
                var $closeBtn = $('<a href="#" class="menu-close"></a>').appendTo($overlay);

                $closeBtn.click(function(e){
                    e.preventDefault();
                    $overlay.animateCss('fadeOutLeft', function(){
                        $overlay.remove();
                    });
                });

                $menuClone.find('li').each(function(){

                    var $item = $(this);

                    if ($item.find('ul').length == 0) { return; }

                    var $link = $item.children('a');

                    $('<span class="toggler"><i class="ic-angle-right"></i></span>').appendTo($link);

                    $link.click(function(e){
                        e.preventDefault();
                        $link.find('.toggler i').toggleClass('ic-angle-right').toggleClass('ic-angle-down');
                        $link.next('ul').slideToggle();
                    });

                });

                $overlay.addClass('animated').addClass('fadeInLeft').appendTo('body');

            });

        });

	}

	function initSliders(){
		if ($('.page-slider').length > 0) {
			$('.page-slider').each(function(){
				new InthemerSlider($(this));
			});
		}
	}

	function initParallax(){
		if ($('.it-parallax').length > 0) {
			$(window).scroll(function () {
				var t = $(window).scrollTop() * -1;
				$('.it-parallax').css('background-position-y', t + 'px');
			});
		}
	}

	function animatePage(){

		$(window).scroll(function(){
			$('[data-anim]').each(function(){
				animateBlock($(this), 'view');
			});
		}).scroll();

		$('[data-anim]').each(function(){
			animateBlock($(this), 'load');
		});

	}

	function animateBlock($block, eventType){

		var $window = $(window);

		if ($block.hasClass('anim-done') || $block.hasClass('animated')) { return; }

		var animType = $block.data('anim');
		if (animType != eventType) { return; }

		if ($block.offset().top > $window.scrollTop() + $window.height() - 20){
			return;
		}

		var animClass = $block.data('anim-class');
		if (!animClass) { return; }

		var animSpeed = parseFloat($block.data('anim-speed')/1000).toFixed(2) + 's';
		var animDelay = parseFloat($block.data('anim-delay')/1000).toFixed(2) + 's';

		$block.css('animation-duration', animSpeed).css('animation-delay', animDelay);

		$block.animateCss(animClass);

	}

	init();

}

$(document).ready(function () {
	new InthemerPageController();
});

function InthemerSlider($block) {

    var $slides = $block.find('.slide').hide();
    var currentSlide = 0;
    var slidesCount = $slides.length;
    var lastSlide = slidesCount - 1;
    var isAnimating = false;
	var isAuto = $block.hasClass('is-auto');
	var autoInterval;

	var slideDelay = $block.data('slide-delay') || 3000;
	var animDelay = $block.data('anim-delay') || 500;

	$block.hover(function(){
		if (isAuto){
			stopAutoSlide();
		}
	}, function(){
		if (isAuto){
			startAutoSlide();
		}
	});

    $slides.eq(currentSlide).show();

    $block.find('.next').click(function (e) {
        changeSlide('next', true);
    });

    $block.find('.prev').click(function (e) {
		changeSlide('prev', true);
    });

    $(window).resize(function(){
        $slides.css('width', '100%');
    });

	if (isAuto){
		console.log('auto');
		startAutoSlide();
	}

	function changeSlide(direction, isManualClick){

        if (isAnimating) {
			return;
		}

        if (isManualClick && isAuto){
            stopAutoSlide();
        }

        var $slides = $block.find('.slide');
        var $currentSlide = $slides.eq(currentSlide);

        var nextSlide, $nextSlide;

        if (direction == 'prev'){

            nextSlide = (currentSlide == 0) ? lastSlide : currentSlide - 1;
            $nextSlide = $slides.eq(nextSlide);
            $nextSlide.css({left: '', right: $block.width(), top: 0});

            $currentSlide.animate({left: $block.width()}, animDelay);
            $nextSlide.show().animate({right: 0}, animDelay, function(){
                isAnimating = false;
            });

        } else {

            nextSlide = (currentSlide == lastSlide) ? 0 : currentSlide + 1;
            $nextSlide = $slides.eq(nextSlide);

            $nextSlide.css({right: '', left: $block.width(), top: 0});

            $currentSlide.animate({left: '-' + $block.width()}, animDelay);
            $nextSlide.show().animate({left: 0}, animDelay, function () {
                isAnimating = false;
            });

        }

        currentSlide = nextSlide;
        isAnimating = true;

    }

	function startAutoSlide(){
        autoInterval = setInterval(function(){
            changeSlide('next');
        }, slideDelay);
    }

    function stopAutoSlide(){
        clearInterval(autoInterval);
    }

}

function insertJavascript(filepath, onloadCallback) {
    if ($('head script[src="'+filepath+'"]').length > 0){
        return;
    }
    var el = document.createElement('script');
    el.setAttribute('type', 'text/javascript');
    el.setAttribute('src', filepath);
    if (typeof(onloadCallback) == 'function') {
        el.setAttribute('onload', function() {
            onloadCallback();
        });
    }
    $('head').append(el);
}
