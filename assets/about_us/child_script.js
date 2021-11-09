(function ($) {
	"use strict";

	$(document).ready(function () {
		$('.thim-js-call-slick').each(function(){
			var wrapSlick = $(this);
			var slideSlick = $(this).find('.slide-slick');

			var showArrow = false;
			if($(wrapSlick).find('.wrap-arrow-slick').length > 0) {
				showArrow = true;
			}

			// Get data
			var numOfSlide = Number($(this).data('numofslide'));
			var numOfScroll = Number($(this).data('numofscroll'));
			var speedAuto = Number($(this).data('speedauto'));

			var centerMode = false;
			var paddingCenter = 0;
			if($(this).data('centermode') == '1') {
				centerMode = true;
				paddingCenter = 118;
			} else {
				centerMode = false;
			}

			var loopSlide = false;
			if($(this).data('loopslide') == '1') {
				loopSlide = true;
			} else {
				loopSlide = false;
			}

			var autoScroll = false;
			if($(this).data('autoscroll') == '1') {
				autoScroll = true;
			} else {
				autoScroll = false;
			}

			var customdot = false;
			if($(this).data('customdot') == '1') {
				customdot = true;
			} else {
				customdot = false;
			}

			var verticalSlide = false;
			if($(this).data('verticalslide') == '1') {
				verticalSlide = true;
			} else {
				verticalSlide = false;
			}

			var verticalSwipe = false;
			if($(this).data('verticalswipe') == '1') {
				verticalSwipe = true;
			} else {
				verticalSwipe = false;
			}

			var respon = [[4, 4], [3, 3], [2, 2], [1, 1], [1, 1]];
			var str = $(this).data('respon') //'[4, 4], [3, 3], [2, 2], [1, 1], [1, 1]';
			var strArray = str.match(/(\d+)/g);
			respon =    [
				[Number(strArray[0]), Number(strArray[1])],
				[Number(strArray[2]), Number(strArray[3])],
				[Number(strArray[4]), Number(strArray[5])],
				[Number(strArray[6]), Number(strArray[7])],
				[Number(strArray[8]), Number(strArray[9])]
			]

			// Call slick
			$(slideSlick)
				.on('afterChange init', function(event, slick, direction){
					// console.log('afterChange/init', event, slick, slick.$slides);
					// remove all prev/next
					slick.$slides.removeClass('prevSlide').removeClass('nextSlide');

					// find current slide
					for (var i = 0; i < slick.$slides.length; i++)
					{
						var $slide = $(slick.$slides[i]);
						if ($slide.hasClass('slick-current')) {
							// update DOM siblings
							$slide.prev().addClass('prevSlide');
							$slide.next().addClass('nextSlide');
							break;
						}
					}
				})
				.on('beforeChange', function(event, slick) {
					// optional, but cleaner maybe
					// remove all prev/next
					slick.$slides.removeClass('prevSlide').removeClass('nextSlide');
				})
				.slick({
				centerMode: centerMode,
				centerPadding: '0px',
				rtl: false,
				vertical: verticalSlide,
				verticalSwiping: true,
				pauseOnFocus: false,
				pauseOnHover: true,
				slidesToShow: numOfSlide,
				slidesToScroll: numOfScroll,
				fade: false,
				infinite: loopSlide,
				autoplay: autoScroll,
				autoplaySpeed: false,
				arrows: showArrow,
				appendArrows: $(wrapSlick).find('.wrap-arrow-slick'),
				prevArrow: $(wrapSlick).find('.prev-slick'),
				nextArrow: $(wrapSlick).find('.next-slick'),
				dots: false,
				dotsClass:'dots-slick',
				adaptiveHeight: false,
				responsive: [
					{
						breakpoint: 1368,
						settings: {
							centerMode: centerMode,
							centerPadding: '0px',
							slidesToShow: respon[0][0],
							slidesToScroll: respon[0][1]
						}
					},
					{
						breakpoint: 1199,
						settings: {
							centerMode: centerMode,
							centerPadding: '0px',
							slidesToShow: respon[1][0],
							slidesToScroll: respon[1][1]
						}
					},
					{
						breakpoint: 991,
						settings: {
							centerMode: centerMode,
							centerPadding: '0px',
							slidesToShow: respon[2][0],
							slidesToScroll: respon[2][1]
						}
					},
					{
						breakpoint: 767,
						settings: {
							centerMode: centerMode,
							centerPadding: '0px',
							slidesToShow: respon[3][0],
							slidesToScroll: respon[3][1]
						}
					},
					{
						breakpoint: 575,
						settings: {
							centerMode: centerMode,
							centerPadding: '0px',
							slidesToShow: respon[4][0],
							slidesToScroll: respon[4][1]
						}
					}
				]
			})
		});

		// var swiper = new Swiper('.swiper-container', {
		// 	slidesPerView: 'auto',
		// 	spaceBetween: 0,
		// 	centeredSlides: true,
		// 	loop: true,
		// 	direction: 'vertical',
		// 	pagination: {
		// 		el: '.swiper-pagination',
		// 		clickable: true,
		// 	},
		// 	navigation: {
		// 		nextEl: '.swiper-button-next',
		// 		prevEl: '.swiper-button-prev',
		// 	},
		// });

		var offsetTop = 50;

		jQuery('.course-info-right.sticky-sidebar').theiaStickySidebar({
			'containerSelector'     : '',
			'additionalMarginTop'   : offsetTop,
			'additionalMarginBottom': '0',
			'updateSidebarHeight'   : false,
			'minWidth'              : '768',
			'sidebarBehavior'       : 'modern',
		});

		var elementInfoTop = $('.course-info-top');
		if(elementInfoTop.length){
			var InfoTopHeight = elementInfoTop.innerHeight(),
				elementInfoRight = $('.course-info-right');
			elementInfoRight.css('margin-top', '-' + ( InfoTopHeight + 60 - 77 ) + 'px' );
		}

		$('.js-call-accordion').each(function(){
			var wraper = $(this);

			if($(wraper).hasClass('active-accordion')) {
				$(wraper).find('.section-content').show();
			}
			else {
				$(wraper).find('.section-content').hide();
			}

			$(wraper).find('.js-toggle-accordion').on('click', function(){
				$(wraper).toggleClass('active-accordion');
				$(wraper).find('.section-content').slideToggle();
			});
		});
	});

	$( window ).resize(function() {
		var elementInfoTop = $('.course-info-top');
		if(elementInfoTop.length){
			var InfoTopHeight = elementInfoTop.innerHeight(),
				elementInfoRight = $('.course-info-right');
			elementInfoRight.css('margin-top', '-' + ( InfoTopHeight + 60 - 77 ) + 'px' );
		}
	});

	$( window ).load(function () {
		var swiper = new Swiper('.swiper-container', {
			slidesPerView: 'auto',
			spaceBetween: 0,
			centeredSlides: true,
			loop: true,
			direction: 'vertical',
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			// Responsive breakpoints
			breakpoints: {
				// when window width is <= 480px
				480: {
					slidesPerView: 1,
				},
			}
		});

		// click show subcategory on mobile
		var $cat_item_has_children =   jQuery('.mobile-menu-wrapper .menu-right .thim-widget-course-categories li.category-has-child');

		if ( $cat_item_has_children.length ) {
			jQuery('.mobile-menu-wrapper .menu-right .thim-widget-course-categories li.category-has-child > a').after( "<span class='icon_cate_child'></span>" );
			var $icon_menu_item_mobile  =   jQuery('.icon_cate_child');
			$icon_menu_item_mobile.each(function () {
				jQuery(this).on( "click", function() {
					jQuery(this).parent().children('.mobile-menu-wrapper .menu-right .thim-widget-course-categories li.category-has-child >ul').slideToggle("400");
				})
			})

		}
	})

})(jQuery);