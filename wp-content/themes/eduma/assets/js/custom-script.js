var thim_scroll = true;

(function ($) {
    "use strict";


    $.avia_utilities = $.avia_utilities || {};
    $.avia_utilities.supported = {};
    $.avia_utilities.supports = (function () {
        var div = document.createElement('div'),
            vendors = ['Khtml', 'Ms', 'Moz', 'Webkit', 'O'];
        return function (prop, vendor_overwrite) {
            if (div.style.prop !== undefined) {
                return "";
            }
            if (vendor_overwrite !== undefined) {
                vendors = vendor_overwrite;
            }
            prop = prop.replace(/^[a-z]/, function (val) {
                return val.toUpperCase();
            });

            var len = vendors.length;
            while (len--) {
                if (div.style[vendors[len] + prop] !== undefined) {
                    return "-" + vendors[len].toLowerCase() + "-";
                }
            }
            return false;
        };
    }());

    /* Smartresize */
    (function ($, sr) {
        var debounce = function (func, threshold, execAsap) {
            var timeout;
            return function debounced() {
                var obj = this, args = arguments;

                function delayed() {
                    if (!execAsap)
                        func.apply(obj, args);
                    timeout = null;
                }

                if (timeout)
                    clearTimeout(timeout);
                else if (execAsap)
                    func.apply(obj, args);
                timeout = setTimeout(delayed, threshold || 100);
            };
        };
        // smartresize
        jQuery.fn[sr] = function (fn) {
            return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr);
        };
    })(jQuery, 'smartresize');

    //Back To top
    var back_to_top = function () {
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 400) {
                jQuery('#back-to-top').addClass('active');
            } else {
                jQuery('#back-to-top').removeClass('active');
            }
        });
        jQuery('#back-to-top').on('click', function () {
            jQuery('html, body').animate({scrollTop: '0px'}, 800);
            return false;
        });
    };

    //// stick header
    $(document).ready(function () {
        var $header = $('#masthead.header_default');
        var $content_pusher = $('#wrapper-container .content-pusher');
        $header.imagesLoaded(function () {
            var height_sticky_header = $header.outerHeight(true);
            $content_pusher.css({"padding-top": height_sticky_header + 'px'});
            $(window).resize(function () {
                var height_sticky_header = $header.outerHeight(true);
                $content_pusher.css({"padding-top": height_sticky_header + 'px'});
            });
        });
    });

    var thim_TopHeader = function () {
        var header = $('#masthead'),
            height_sticky_header = header.outerHeight(true),
            content_pusher = $('#wrapper-container .content-pusher'),
            top_site_main = $('#wrapper-container .top_site_main');

        //header_overlay
        if (header.hasClass('header_overlay')) {
            //header overlay
            header.imagesLoaded(function () {
                top_site_main.css({"padding-top": height_sticky_header + 'px'});
                $(window).resize(function () {
                    var height_sticky_header = header.outerHeight(true);
                    top_site_main.css({"padding-top": height_sticky_header + 'px'});
                });
            });
        } else {
            //Header default
            header.imagesLoaded(function () {
                content_pusher.css({"padding-top": height_sticky_header + 'px'});
                $(window).resize(function () {
                    var height_sticky_header = header.outerHeight(true);
                    content_pusher.css({"padding-top": height_sticky_header + 'px'});
                });
            });
        }

        $('#toolbar .menu li.menu-item-has-children').on({
            'mouseenter': function() {
                $(this).children('.sub-menu').stop(true, false).fadeIn(250);
            },
            'mouseleave': function() {
                $(this).children('.sub-menu').stop(true, false).fadeOut(250);
            },
        });
    };

    var thim_SwitchLayout = function () {
        var cookie_name = 'course_switch',
            archive = $('#thim-course-archive');
        if (archive.length > 0) {
            //Check grid-layout
            if (!jQuery.cookie(cookie_name) || jQuery.cookie(cookie_name) == 'grid-layout') {
                if (archive.hasClass('thim-course-list')) {
                    archive.removeClass('thim-course-list').addClass('thim-course-grid');
                }
                $('.thim-course-switch-layout > a.switchToGrid').addClass('switch-active');
            } else {
                if (archive.hasClass('thim-course-grid')) {
                    archive.removeClass('thim-course-grid').addClass('thim-course-list');
                }
                $('.thim-course-switch-layout > a.switchToList').addClass('switch-active');
            }

            $('.thim-course-switch-layout > a').on('click', function (event) {
                var elem = $(this);

                event.preventDefault();
                if (!elem.hasClass('switch-active')) {
                    if (elem.hasClass('switchToGrid')) {
                        $('.thim-course-switch-layout > a').removeClass('switch-active');
                        elem.addClass('switch-active');
                        archive.fadeOut(300, function () {
                            archive.removeClass('thim-course-list').addClass(' thim-course-grid').fadeIn(300);
                            jQuery.cookie(cookie_name, 'grid-layout', {expires: 3, path: '/'});
                        });
                    } else {
                        $('.thim-course-switch-layout > a').removeClass('switch-active');
                        elem.addClass('switch-active');
                        archive.fadeOut(300, function () {
                            archive.removeClass('thim-course-grid').addClass('thim-course-list').fadeIn(300);
                            jQuery.cookie(cookie_name, 'list-layout', {expires: 3, path: '/'});
                        });
                    }
                }
            });
        }

    };

    var thim_Shop_SwitchLayout = function () {
        var cookie_name = 'product_list',
            archive = $('#thim-product-archive');
        if (archive.length > 0) {
            //Check grid-layout
            if (!jQuery.cookie(cookie_name) || jQuery.cookie(cookie_name) == 'grid-layout') {
                if (archive.hasClass('thim-product-list')) {
                    archive.removeClass('thim-product-list').addClass('thim-product-grid');
                }
                $('.thim-product-switch-layout > a.switch-active').removeClass('switch-active');
                $('.thim-product-switch-layout > a.switchToGrid').addClass('switch-active');
            } else {
                if (archive.hasClass('thim-product-grid')) {
                    archive.removeClass('thim-product-grid').addClass('thim-product-list');
                }
                $('.thim-product-switch-layout > a.switch-active').removeClass('switch-active');
                $('.thim-product-switch-layout > a.switchToList').addClass('switch-active');
            }

            $(document).on('click', '.thim-product-switch-layout > a', function (event) {
                var elem = $(this),
                    archive = $('#thim-product-archive');

                event.preventDefault();
                if (!elem.hasClass('switch-active')) {
                    if (elem.hasClass('switchToGrid')) {
                        $('.thim-product-switch-layout > a').removeClass('switch-active');
                        elem.addClass('switch-active');
                        archive.fadeOut(300, function () {
                            archive.removeClass('thim-product-list').addClass(' thim-product-grid').fadeIn(300);
                            jQuery.cookie(cookie_name, 'grid-layout', {expires: 3, path: '/'});
                        });
                    } else {
                        $('.thim-product-switch-layout > a').removeClass('switch-active');
                        elem.addClass('switch-active');
                        archive.fadeOut(300, function () {
                            archive.removeClass('thim-product-grid').addClass('thim-product-list').fadeIn(300);
                            jQuery.cookie(cookie_name, 'list-layout', {expires: 3, path: '/'});
                        });
                    }
                }
            });
        }

    };

    var thim_Blog_SwitchLayout = function() {
        var cookie_name = 'blog_layout',
            archive = $('#blog-archive'),
            switch_layout = archive.find('.switch-layout');
        if (archive.length > 0) {
            //Check grid-layout
            if (!jQuery.cookie(cookie_name) || jQuery.cookie(cookie_name) == 'grid-layout') {
                if (archive.hasClass('blog-list')) {
                    archive.removeClass('blog-list').addClass('blog-grid');
                }
                switch_layout.find('> a.switch-active').
                removeClass('switch-active');
                switch_layout.find('> a.switchToGrid').
                addClass('switch-active');
            } else {
                if (archive.hasClass('blog-grid')) {
                    archive.removeClass('blog-grid').addClass('blog-list');
                }
                switch_layout.find('> a.switch-active').
                removeClass('switch-active');
                switch_layout.find('> a.switchToList').
                addClass('switch-active');
            }

            $(document).
            on('click', '#blog-archive .switch-layout > a',
                function(event) {
                    var elem = $(this),
                        archive = $('#blog-archive');

                    event.preventDefault();
                    if (!elem.hasClass('switch-active')) {
                        switch_layout.find('>a').
                        removeClass('switch-active');
                        elem.addClass('switch-active');
                        if (elem.hasClass('switchToGrid')) {
                            archive.fadeOut(300, function() {
                                archive.removeClass('blog-list').
                                addClass('blog-grid').
                                fadeIn(300);
                                jQuery.cookie(cookie_name, 'grid-layout',
                                    {expires: 3, path: '/'});
                            });
                        } else {
                            archive.fadeOut(300, function() {
                                archive.removeClass('blog-grid').
                                addClass('blog-list').
                                fadeIn(300);
                                jQuery.cookie(cookie_name, 'list-layout',
                                    {expires: 3, path: '/'});
                            });
                        }
                    }
                });
        }

    };

    var thim_Menu = function () {
        //Add class for masthead
        var $header = $('#masthead.sticky-header'),
            off_Top = ( $('.content-pusher').length > 0 ) ? $('.content-pusher').offset().top : 0,
            menuH = $header.outerHeight(),
            latestScroll = 0;
        if ($(window).scrollTop() > 2) {
            $header.removeClass('affix-top').addClass('affix');
        }
        $(window).scroll(function () {
            var current = $(this).scrollTop();
            if (current > 2) {
                $header.removeClass('affix-top').addClass('affix');
            } else {
                $header.removeClass('affix').addClass('affix-top');
            }

            if (current > latestScroll && current > menuH + off_Top) {
                if (!$header.hasClass('menu-hidden')) {
                    $header.addClass('menu-hidden');
                }
            } else {
                if ($header.hasClass('menu-hidden')) {
                    $header.removeClass('menu-hidden');
                }
            }

            latestScroll = current;
        });

        //Show submenu when hover
        $('.wrapper-container:not(.mobile-menu-open) .site-header .navbar-nav >li,.wrapper-container:not(.mobile-menu-open) .site-header .navbar-nav li,.site-header .navbar-nav li ul li').on({
            'mouseenter': function () {
                $(this).children('.sub-menu').stop(true, false).fadeIn(250);
            },
            'mouseleave': function () {
                $(this).children('.sub-menu').stop(true, false).fadeOut(250);
            }
        });

        if ($(window).width() > 768) {
            //Magic Line
            var menu_active = $('#masthead .navbar-nav>li.menu-item.current-menu-item,#masthead .navbar-nav>li.menu-item.current-menu-parent');
            if (menu_active.length > 0) {
                menu_active.before('<span id="magic-line"></span>');
                var menu_active_child = menu_active.find('>a,>span.disable_link'),
                    menu_left = menu_active.position().left,
                    menu_child_left = parseInt(menu_active_child.css('padding-left')),
                    magic = $('#magic-line');
                magic.width(menu_active_child.width()).css("left", Math.round(menu_child_left + menu_left)).data('magic-width', magic.width()).data('magic-left', magic.position().left);
            } else {
                var first_menu = $('#masthead .navbar-nav>li.menu-item:first-child');
                first_menu.after('<span id="magic-line"></span>');
                var magic = $('#magic-line');
                magic.data('magic-width', 0);
            }

            var nav_H = parseInt($('.site-header .navigation').outerHeight());
            magic.css('bottom', nav_H - (nav_H - 90) / 2 - 64);

            $('#masthead .navbar-nav>li.menu-item').on({
                'mouseenter': function () {
                    var elem = $(this).find('>a,>span.disable_link,>span.tc-menu-inner'),
                        new_width = elem.width(),
                        parent_left = elem.parent().position().left,
                        left = parseInt(elem.css('padding-left'));
                    if (!magic.data('magic-left')) {
                        magic.css('left', Math.round(parent_left + left));
                        magic.data('magic-left', 'auto');
                    }
                    magic.stop().animate({
                        left: Math.round(parent_left + left),
                        width: new_width
                    });
                },
                'mouseleave': function () {
                    magic.stop().animate({
                        left: magic.data('magic-left'),
                        width: magic.data('magic-width')
                    });
                }
            });
        }

        //Update position for sub-menu
        $('.header_v1 .menu-item.widget_area:not(.dropdown_full_width),.header_v1 .menu-item.multicolumn:not(.dropdown_full_width)').each(function () {
            var elem = $(this),
                sub_menu = elem.find('>.sub-menu');
            if (sub_menu.length > 0) {
                sub_menu.css('left', ( elem.width() - sub_menu.width() ) / 2);
            }
        });

    };

    /* ****** jp-jplayer  ******/
    var thim_post_audio = function () {
        $('.jp-jplayer').each(function () {
            var $this = $(this),
                url = $this.data('audio'),
                type = url.substr(url.lastIndexOf('.') + 1),
                player = '#' + $this.data('player'),
                audio = {};
            audio[type] = url;
            $this.jPlayer({
                ready: function () {
                    $this.jPlayer('setMedia', audio);
                },
                swfPath: 'jplayer/',
                cssSelectorAncestor: player
            });
        });
    };

    var thim_post_gallery = function () {
        $('article.format-gallery .flexslider').imagesLoaded(function () {
            if(jQuery().flexslider) {
                $('.flexslider').flexslider({
                    slideshow     : true,
                    animation     : 'fade',
                    pauseOnHover  : true,
                    animationSpeed: 400,
                    smoothHeight  : true,
                    directionNav  : true,
                    controlNav    : false
                });
            }
        });
    };

    /* ****** PRODUCT QUICK VIEW  ******/
    var thim_quick_view = function () {
        $('.quick-view').on('click', function (e) {
            /* add loader  */
            $('.quick-view a').css('display', 'none');
            $(this).append('<a href="javascript:;" class="loading dark"></a>');
            var product_id = $(this).attr('data-prod');
            var data = {action: 'jck_quickview', product: product_id};
            $.post(ajaxurl, data, function (response) {
                $.magnificPopup.open({
                    mainClass: 'my-mfp-zoom-in',
                    items: {
                        src: response,
                        type: 'inline'
                    }
                });
                $('.quick-view a').css('display', 'inline-block');
                $('.loading').remove();
                $('.product-card .wrapper').removeClass('animate');
                setTimeout(function () {
                    $('.product-lightbox form').wc_variation_form();
                }, 600);
            });
            e.preventDefault();
        });
    };

    var thim_miniCartHover = function () {
        jQuery(document).on('mouseenter', '.minicart_hover', function () {
            jQuery(this).next('.widget_shopping_cart_content').slideDown();
        }).on('mouseleave', '.minicart_hover', function () {
            jQuery(this).next('.widget_shopping_cart_content').delay(100).stop(true, false).slideUp();
        });
        jQuery(document)
            .on('mouseenter', '.widget_shopping_cart_content', function () {
                jQuery(this).stop(true, false).show();
            })
            .on('mouseleave', '.widget_shopping_cart_content', function () {
                jQuery(this).delay(100).stop(true, false).slideUp();
            });
    };

    var thim_carousel = function () {
        if (jQuery().owlCarousel) {
            $(".thim-widget-event,.thim-gallery-images,.sc-testimonials").owlCarousel({
                autoPlay: false,
                singleItem: true,
                stopOnHover: true,
                pagination: true,
                autoHeight: false
            });

            $('.thim-carousel-wrapper').each(function () {
                var item_visible = $(this).data('visible') ? parseInt($(this).data('visible')) : 4,
                    item_desktopsmall = $(this).data('desktopsmall') ? parseInt($(this).data('desktopsmall')) : item_visible,
                    pagination = $(this).data('pagination') ? true : false,
                    navigation = $(this).data('navigation') ? true : false;

                $(this).owlCarousel({
                    items: item_visible,
                    itemsDesktop: [1200, item_visible],
                    itemsDesktopSmall: [1024, item_desktopsmall],
                    itemsTablet: [768, 2],
                    itemsMobile: [480, 1],
                    navigation: navigation,
                    pagination: pagination,
                    lazyLoad: true,
                    navigationText: [
                        "<i class=\'fa fa-chevron-left \'></i>",
                        "<i class=\'fa fa-chevron-right \'></i>"
                    ],
                });
            });

            $('.thim-carousel-course-categories .thim-course-slider').each(function () {
                var item_visible = $(this).data('visible') ? parseInt($(this).data('visible')) : 7,
                    item_desktopsmall = $(this).data('desktopsmall') ? parseInt($(this).data('desktopsmall')) : 6,
                    pagination = $(this).data('pagination') ? true : false,
                    navigation = $(this).data('navigation') ? true : false;

                $(this).owlCarousel({
                    items: item_visible,
                    itemsDesktopSmall: [1024, item_desktopsmall],
                    itemsTablet: [768, 4],
                    itemsMobile: [480, 2],
                    navigation: navigation,
                    pagination: pagination,
                    navigationText: [
                        "<i class=\'fa fa-chevron-left \'></i>",
                        "<i class=\'fa fa-chevron-right \'></i>"
                    ],
                });
            });
        }

    };

    var thim_contentslider = function () {
        $('.thim-testimonial-slider').each(function () {
            var elem = $(this),
                item_visible = parseInt(elem.data('visible')),
                autoplay = elem.data('autoplay') ? true : false,
                mousewheel = elem.data('mousewheel') ? true : false;
            if(jQuery().thimContentSlider){
                var testimonial_slider = $(this).thimContentSlider({
                    items: elem,
                    itemsVisible: item_visible,
                    mouseWheel: mousewheel,
                    autoPlay: autoplay,
                    itemMaxWidth: 100,
                    itemMinWidth: 100,
                    activeItemRatio: 1.18,
                    activeItemPadding: 0,
                    itemPadding: 15
                });
            };
        });
    };

    var thim_course_menu_landing = function () {
        if ($('.thim-course-menu-landing').length > 0) {
            var menu_landing = $('.thim-course-menu-landing'),
                tab_course = $('#course-landing .nav-tabs'),
                tab_active = tab_course.find('>li.active'),
                tab_item = tab_course.find('>li>a'),
                tab_landing = menu_landing.find('.thim-course-landing-tab'),
                tab_landing_item = tab_landing.find('>li>a'),
                landing_Top = ( $('#course-landing').length ) > 0 ? $('#course-landing').offset().top : 0,
                checkTop = ( $(window).height() > landing_Top ) ? $(window).height() : landing_Top;

            $('footer#colophon').addClass('has-thim-course-menu');
            if (tab_active.length > 0) {
                var active_href = tab_active.find('>a').attr('href'),
                    landing_active = tab_landing.find('>li>a[href="' + active_href + '"]');

                if (landing_active.length > 0) {
                    landing_active.parent().addClass('active');
                }
            }

            tab_landing_item.on('click', function (event) {
                event.preventDefault();
                var href = $(this).attr('href'),
                    parent = $(this).parent();

                $('body, html').animate({
                    scrollTop: tab_course.offset().top - 50
                }, 800);
                if (!parent.hasClass('active')) {
                    tab_landing.find('li.active').removeClass('active');
                    parent.addClass('active');
                    tab_course.find('>li>a[href="' + href + '"]').trigger('click');
                }
            });

            tab_item.on('click', function () {
                var href = $(this).attr('href'),
                    parent_landing = tab_landing.find('>li>a[href="' + href + '"]').parent();

                if (!parent_landing.hasClass('active')) {
                    tab_landing.find('li.active').removeClass('active');
                    parent_landing.addClass('active');
                }
            });

            $(window).scroll(function () {
                if ($(window).scrollTop() > checkTop) {
                    $('body').addClass('course-landing-active');
                } else {
                    $('body.course-landing-active').removeClass('course-landing-active');
                }
                ;
            })
        }
    };

    $(function () {
        back_to_top();

        /* Waypoints magic
         ---------------------------------------------------------- */
        if (typeof jQuery.fn.waypoint !== 'undefined') {
            jQuery('.wpb_animate_when_almost_visible:not(.wpb_start_animation)').waypoint(function () {
                jQuery(this).addClass('wpb_start_animation');
            }, {offset: '85%'});
        }
    });

    function empty(data) {
        if (typeof(data) == 'number' || typeof(data) == 'boolean') {
            return false;
        }
        if (typeof(data) == 'undefined' || data === null) {
            return true;
        }
        if (typeof(data.length) != 'undefined') {
            return data.length === 0;
        }
        var count = 0;
        for (var i in data) {
            if (Object.prototype.hasOwnProperty.call(data, i)) {
                count++;
            }
        }
        return count === 0;
    }

    var windowWidth = window.innerWidth,
        windowHeight = window.innerHeight,
        $document = $(document),
        orientation = windowWidth > windowHeight ? 'landscape' : 'portrait';
    var TitleAnimation = {
        selector: '.article__parallax',
        initialized: false,
        animated: false,
        initialize: function () {

            //this.update();
        },
        update: function () {
            //return;

        }
    };
    /* ====== ON RESIZE ====== */
    $(window).load(function () {
        thim_post_audio();
        thim_post_gallery();
        thim_TopHeader();
        thim_Menu();
        thim_quick_view();
        thim_miniCartHover();
        thim_carousel();
        thim_contentslider();
        thim_SwitchLayout();
        thim_Shop_SwitchLayout();
        thim_Blog_SwitchLayout();

        setTimeout(function () {
            TitleAnimation.initialize();
            thim_course_menu_landing();
        }, 400);

    });

    $(window).on("debouncedresize", function (e) {
        windowWidth = $(window).width();
        windowHeight = $(window).height();
        TitleAnimation.initialize();
    });

    $(window).on("orientationchange", function (e) {
        setTimeout(function () {
            TitleAnimation.initialize();
        }, 300);
    });

    var latestScrollY = $('html').scrollTop() || $('body').scrollTop(),
        ticking = false;

    function updateAnimation() {
        ticking = false;
        TitleAnimation.update();
    }

    function requestScroll() {
        if (!ticking) {
            requestAnimationFrame(updateAnimation);
        }
        ticking = true;
    }

    $(window).on("scroll", function () {
        latestScrollY = $('html').scrollTop() || $('body').scrollTop();
        requestScroll();
    });


    jQuery(function ($) {
        var adminbar_height = jQuery('#wpadminbar').outerHeight();
        jQuery('.navbar-nav li a,.arrow-scroll > a').on('click', function (e) {
            if (parseInt(jQuery(window).scrollTop(), 10) < 2) {
                var height = 47;
            } else height = 0;
            var sticky_height = jQuery('#masthead').outerHeight();
            var menu_anchor = jQuery(this).attr('href');
            if (menu_anchor && menu_anchor.indexOf("#") == 0 && menu_anchor.length > 1) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: jQuery(menu_anchor).offset().top - adminbar_height - sticky_height + height
                }, 850);
            }
        });
    });

    /* Menu Sidebar */
    jQuery(document).on('click', '.menu-mobile-effect', function (e) {
        e.stopPropagation();
        jQuery('.wrapper-container').toggleClass('mobile-menu-open');
    });

    jQuery(document).on('click', '.mobile-menu-open #main-content', function () {
        jQuery('.wrapper-container.mobile-menu-open').removeClass('mobile-menu-open');
    });

    function mobilecheck() {
        var check = false;
        (function (a) {
            if (/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    }

    if (mobilecheck()) {
        window.addEventListener('load', function () { // on page load
            var main_content = document.getElementById('main-content');
            if (main_content) {
                main_content.addEventListener("touchstart", function (e) {
                    jQuery('.wrapper-container').removeClass('mobile-menu-open');
                });
            }
        }, false);
    };

    /* mobile menu */
    if (jQuery(window).width() > 768) {
        jQuery('.navbar-nav>li.menu-item-has-children >a,.navbar-nav>li.menu-item-has-children >span').after('<span class="icon-toggle"><i class="fa fa-angle-down"></i></span>');
    } else {
        jQuery('.navbar-nav>li.menu-item-has-children:not(.current-menu-parent) >a,.navbar-nav>li.menu-item-has-children:not(.current-menu-parent) >span').after('<span class="icon-toggle"><i class="fa fa-angle-down"></i></span>');
        jQuery('.navbar-nav>li.menu-item-has-children.current-menu-parent >a,.navbar-nav>li.menu-item-has-children.current-menu-parent >span').after('<span class="icon-toggle"><i class="fa fa-angle-up"></i></span>');
    }
    jQuery('.navbar-nav>li.menu-item-has-children .icon-toggle').on('click', function () {
        if (jQuery(this).next('ul.sub-menu').is(':hidden')) {
            jQuery(this).next('ul.sub-menu').slideDown(500, 'linear');
            jQuery(this).html('<i class="fa fa-angle-up"></i>');
        }
        else {
            jQuery(this).next('ul.sub-menu').slideUp(500, 'linear');
            jQuery(this).html('<i class="fa fa-angle-down"></i>');
        }
    });

})(jQuery);

(function ($) {
    var thim_quiz_index = function () {
        var question_index = $('.single-quiz .quiz-question-nav .index-question'),
            quiz_total_text = $('.single-quiz .quiz-total .quiz-text');
        if (question_index.length > 0) {
            quiz_total_text.html(question_index.html());
            question_index.hide();
        }
    };

    $(window).load(function () {

        $('.article__parallax').each(function (index, el) {
            $(el).parallax("50%", 0.4);
        });
        $('.images_parallax').parallax_images({
            speed: 0.5
        });
        $(window).resize(function () {
            $('.images_parallax').each(function (index, el) {
                $(el).imagesLoaded(function () {
                    var parallaxHeight = $(this).find('img').height();
                    $(this).height(parallaxHeight);
                });
            });
        }).trigger('resize');
    });

    jQuery(function ($) {
        $('.video-container').on('click', '.beauty-intro .btns', function () {
            var iframe = '<iframe src="' + $(this).closest(".video-container").find(".yt-player").attr('data-video') + '" height= "' + $('.parallaxslider').height() + '"></iframe>';
            $(this).closest(".video-container").find(".yt-player").replaceWith(iframe);
            //debug >HP
            $(this).closest(".video-container").find('.hideClick:first').css('display', 'none');
        });
    });

    jQuery(function ($) {

        if (!$('.add-review').length) {
            return;
        }
        var $star = $('.add-review .filled');
        var $review = $('#review-course-value');
        $star.find('li').on('mouseover',
            function () {
                $(this).nextAll().find('span').removeClass('fa-star').addClass('fa-star-o');
                $(this).prevAll().find('span').removeClass('fa-star-o').addClass('fa-star');
                $(this).find('span').removeClass('fa-star-o').addClass('fa-star');
                $review.val($(this).index() + 1);
            }
        );
    });

    jQuery(function ($) {
        var $payment_form = $('form[name="learn_press_payment_form"]');
        $('input[name="payment_method"]', $payment_form).on('click', function () {
            var $this = $(this);
            if ($this.is(":checked")) {
                $this.closest('li').find('.learn_press_payment_form').slideDown();
                $('.learn_press_payment_form', $this.closest('li').siblings()).slideUp();
            }
        });
        $('.course-payment .thim-enroll-course-button').on('click', function () {
            var button = $(this),
                payment_methods = $('input[name="payment_method"]', $payment_form),
                take = false,
                payment = payment_methods.filter(":checked").val();
            if (0 == payment_methods.length) {
                take = true;
            } else if (1 == payment_methods.length) {
                payment_methods.attr('checked', true);
                take = true;
            } else {
                if ($payment_form.is(':visible')) {
                    if (!payment) {
                        alert(learn_press_js_localize.no_payment_method);
                        return;
                    } else {
                        take = true;
                    }
                } else {
                    $payment_form.show();
                    return;
                }
            }
            if (!take) return;
            $(this).html($(this).data('loading-text') || 'Processing').attr('disabled', true);
            if ($payment_form.triggerHandler('learn_press_place_order') !== false && $payment_form.triggerHandler('learn_press_place_order_' + payment) !== false) {
                var data = {
                    action: 'learnpress_take_course',
                    payment_method: payment_methods.filter(":checked").val(),
                    course_id: button.data('id'),
                    data: $payment_form.serialize()
                };

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'html',
                    data: $payment_form.serialize(),
                    success: function (res) {
                        var matches = res.match(/<!-- LPR_AJAX_START -->(.*)<!-- LPR_AJAX_END -->/),
                            message = '';
                        if (matches && matches[1]) {
                            var json = JSON.parse(matches[1]);
                            if (json) {
                                if (json.redirect && (json.result.toLowerCase() == 'success')) {
                                    window.location.href = json.redirect;
                                    return;
                                } else {
                                    message = json.message;
                                }
                            } else {
                                message = matches[1];
                            }
                        } else {
                            message = res;
                        }
                        if (message) {
                            alert(message);
                        }
                        button.removeAttr('disabled').html(button.data('text'));
                    }
                });
            }
            return false;
        });

        $('.thim-course-landing-button .thim-enroll-course-button').on('click', function (event) {
            event.preventDefault();
            $('.course-payment .thim-enroll-course-button').trigger('click');
            $('body, html').animate({
                scrollTop: $('.course-payment .thim-enroll-course-button').offset().top - 50
            }, 600, function () {
            });
        });
    });

    jQuery(function ($) {
        $('#thim_login').attr('placeholder', thim_js_translate.login);
        $('#thim_pass').attr('placeholder', thim_js_translate.password);
    });

    function load_lesson(evt) {
        evt.preventDefault();
        var $link = $(this),
            $parent = $link.parent(),
            permalink = $link.attr('href');
        if (!$link.data('id')) return false;
        if ($parent.hasClass('current')) return false;
        if ($parent.hasClass('course-lesson')) {
            $('.curriculum-sections .course-lesson.loading').removeClass('loading');
            $parent.addClass('loading');
        }

    }

    jQuery(function ($) {


        if ($('.course-content').children().length > 0) {
            $.magnificPopup.open({
                closeOnBgClick: false,
                preloader: false,
                showCloseBtn: false,
                items: {
                    src: $('.course-content'),
                    type: 'inline'
                },
                callbacks: {
                    open: function () {
                        var href = $(location).attr('href').slice(0, -1),
                            parent = $('.course-content').parent(),
                            elem = $('.curriculum-sections .course-lesson a.lesson-title[href="' + href + '"]'),
                            span_index = '';

                        if (elem.length > 0) {
                            span_index = '<span class="index">' + elem.parent().find('.index').html() + '</span>';
                        }

                        $('.course-content').find('.popup-title').css({
                            'left': parent.position().left,
                            'top': parent.position().top,
                            'width': parent.width()
                        }).append('<button type="button" class="mfp-close">Close</button>').prepend(span_index);

                        if ($('.thim-course-menu-landing').length > 0) {
                            $('.thim-course-menu-landing').addClass('thim-hidden');
                        }

                        //Cancle event close when loading
                        $.magnificPopup.instance.close = function () {
                            if ($('.thim-loading-container').length > 0) {
                                return;
                            }
                            $('.thim-course-menu-landing.thim-hidden').removeClass('thim-hidden');
                            $.magnificPopup.proto.close.call(this);
                        };
                    },
                    resize: function () {
                        var title = $('.course-content .popup-title'),
                            parent = $('.course-content').parent();
                        if (title) {
                            title.animate({
                                'left': parent.position().left,
                                'top': parent.position().top,
                                'width': parent.width()
                            }, 400);
                        }

                    },
                }

            });
        }

        $(document)
            .off('click', '.course-content-lesson-nav a')
            .off('click', '.section-content .course-lesson a')
            .on('click', '.section-content .course-lesson a', function (e) {
                e.preventDefault();
                var elem = $(this),
                    span_index = '<span class="index">' + elem.parent().find('.index').html() + '</span>',
                    content_H = parseInt($(window).outerHeight() * 3 / 4);
                $('.course-content').html('<div class="thim-loading-container"><div class="thim-loading"></div></div>').outerHeight(content_H);
                $.magnificPopup.open({
                    closeOnBgClick: false,
                    preloader: false,
                    showCloseBtn: false,
                    items: {
                        src: $('.course-content'),
                        type: 'inline'
                    },
                    mainClass: 'mfp-with-fade',
                    removalDelay: 300,
                    callbacks: {
                        open: function () {
                            thim_scroll = false;
                            if ($('.thim-course-menu-landing').length > 0) {
                                $('.thim-course-menu-landing').addClass('thim-hidden');
                            }

                            //Cancle event close when loading
                            $.magnificPopup.instance.close = function () {
                                if ($('.thim-loading-container').length > 0) {
                                    return;
                                }
                                thim_scroll = true;
                                $('.thim-course-menu-landing.thim-hidden').removeClass('thim-hidden');
                                $.magnificPopup.proto.close.call(this);
                            };
                        },
                        resize: function () {
                            var title = $('.course-content .popup-title'),
                                parent = $('.course-content').parent();
                            if (title) {
                                title.animate({
                                    'left': parent.position().left,
                                    'top': parent.position().top,
                                    'width': parent.width()
                                }, 400);
                            }

                        },
                    }
                });
                var permalink = $(this).attr('href');
                history.pushState({}, '', permalink);
                LearnPress.load_lesson(permalink, {
                    success: function () {
                        var content = $('.mfp-content .course-content'),
                            parent = $('.course-content').parent(),
                            content_newH = content.outerHeight(),
                            description = content.find('.lesson-description'),
                            description_H = (description.length > 0 ) ? parseInt(description.outerHeight()) : 0;
                        $('.course-content').addClass('loading').outerHeight(content_H);
                        setTimeout(function () {
                            var description_newH = (description.length > 0 ) ? parseInt(description.outerHeight()) : 0;
                            $('.course-content').animate({
                                height: content_newH + description_newH - description_H
                            }, 400, function () {
                                $(this).find('>.popup-title').css({
                                    'left': parent.position().left,
                                    'top': parent.position().top,
                                    'width': parent.width()
                                }).append('<button type="button" class="mfp-close">Close</button>').prepend(span_index);
                                $(this).removeClass('loading');
                            });
                        }, 500);
                    }
                });
                return false;
            })
            .on('click', '.course-content-lesson-nav a', function (e) {
                var permalink = $(this).attr('href'),
                    elem = $('.curriculum-sections .course-lesson a.lesson-title[href="' + permalink + '"]'),
                    span_index = '<span class="index">' + elem.parent().find('.index').html() + '</span>',
                    content_H = ( $('.course-content').outerHeight() < $('.mfp-container').height() ) ? $('.course-content').outerHeight() : $('.mfp-container').height();
                e.preventDefault();
                $('.course-content').html('<div class="thim-loading-container"><div class="thim-loading"></div></div>').outerHeight(content_H);
                history.pushState({}, '', permalink);
                LearnPress.load_lesson(permalink, {
                    success: function () {
                        var content = $('.mfp-content .course-content'),
                            parent = $('.course-content').parent(),
                            content_newH = content.outerHeight(),
                            description = content.find('.lesson-description'),
                            description_H = (description.length > 0 ) ? parseInt(description.outerHeight()) : 0;
                        $('.course-content').addClass('loading').outerHeight(content_H);
                        setTimeout(function () {
                            var description_newH = (description.length > 0 ) ? parseInt(description.outerHeight()) : 0;
                            $('.course-content').animate({
                                height: content_newH + description_newH - description_H
                            }, 400, function () {
                                $(this).find('>.popup-title').css({
                                    'left': parent.position().left,
                                    'top': parent.position().top,
                                    'width': parent.width()
                                }).append('<button type="button" class="mfp-close">Close</button>').prepend(span_index);
                                $(this).removeClass('loading');
                            });
                        }, 500);
                    }
                });
                return false;
            })
            .on('click', '.section-content .course-quiz a', function (e) {
                var elem = $(this),
                    content_H = parseInt($(window).outerHeight() * 3 / 4);
                title = '<h3 class="popup-title"><span class="index">' + elem.parent().find('.index').html() + '</span>' + elem.html() + '<button type="button" class="mfp-close">Close</button></h3>';
                e.preventDefault();
                $.magnificPopup.open({
                    closeOnBgClick: false,
                    preloader: false,
                    showCloseBtn: false,
                    items: {
                        src: '<div class="thim-iframe-quiz"><iframe src="' + $(this).attr('href') + '"></iframe></div>',
                        type: 'inline'
                    },
                    mainClass: 'mfp-with-fade',
                    removalDelay: 300,
                    callbacks: {
                        open: function () {
                            var main = $('.thim-iframe-quiz'),
                                parent = main.parent();
                            thim_scroll = false;

                            main.outerHeight(content_H).prepend('<div class="thim-loading-container"><div class="thim-loading"></div></div>');
                            $('.thim-iframe-quiz iframe').load(function () {
                                var iframe = $(this),
                                    body_height = iframe.contents().find('body').height();
                                iframe.contents().find('.quiz-title, .back-to-course').remove();
                                iframe.css('min-height', body_height + 150);
                                main.outerHeight(content_H).animate({
                                    height: body_height + 150
                                }, 400, function () {
                                    main.find('.popup-title').remove();
                                    main.prepend(title).find('.popup-title').css({
                                        'left': parent.position().left,
                                        'top': parent.position().top,
                                        'width': parent.width()
                                    });
                                    main.find('.thim-loading-container').remove();
                                });

                            });

                            if ($('.thim-course-menu-landing').length > 0) {
                                $('.thim-course-menu-landing').addClass('thim-hidden');
                            }

                            //Cancle event close when loading
                            $.magnificPopup.instance.close = function () {
                                if ($('.thim-iframe-quiz .thim-loading-container').length > 0) {
                                    return;
                                }
                                thim_scroll = true;
                                $('.thim-course-menu-landing.thim-hidden').removeClass('thim-hidden');
                                $.magnificPopup.proto.close.call(this);
                            };

                        },
                        resize: function () {
                            var title = $('.thim-iframe-quiz .popup-title'),
                                parent = $('.thim-iframe-quiz').parent();
                            if (title) {
                                title.animate({
                                    'left': parent.position().left,
                                    'top': parent.position().top,
                                    'width': parent.width()
                                }, 400);
                            }

                        },
                    }
                });
                return false;
            });

        if (typeof LearnPress !== "undefined") {
            LearnPress = $.extend(
                LearnPress, {
                    pushHistory: function (url) {
                        history.pushState({}, '', url);
                    },
                    initQuiz: function (data) {

                        var model = new LearnPress_Model_Quiz(data),
                            view = new LearnPress_View_Quiz(model);
                        view.listenTo(model, 'change', function () {

                            var iframe = $(".thim-iframe-quiz iframe", parent.document.body),
                                main = iframe.parent(),
                                body_H = iframe.contents().find('body').height();

                            thim_quiz_index();
                            iframe.css('min-height', body_H + 150);
                            main.animate({
                                height: body_H + 150
                            }, 400, function () {
                                main.find('.popup-title').css({
                                    'left': main.parent().position().left,
                                    'top': main.parent().position().top,
                                    'width': main.parent().width()
                                });
                                main.find('.thim-loading-container').remove();
                            });
                            $('.quiz-content').hide();
                        });
                    }
                }
            );
        }

        $('.quiz-question-answer .check_answer ').on('click', function () {
            var elem = $(this),
                quiz_hint = $(this).parents('.quiz-question-nav').find('.lpr-question-hint');

            if (quiz_hint.length > 0) {
                return;
            }

            $(".thim-iframe-quiz iframe", parent.document.body).parent().append('<div class="thim-loading-container"><div class="thim-loading"></div></div>');
            $(document).ajaxComplete(function (event, request, settings) {
                var iframe = $(".thim-iframe-quiz iframe", parent.document.body),
                    main = iframe.parent(),
                    body_H = iframe.contents().find('body').height();

                iframe.css('min-height', body_H + 150);
                main.animate({
                    height: body_H + 150
                }, 400, function () {
                    main.find('.popup-title').css({
                        'left': main.parent().position().left,
                        'top': main.parent().position().top,
                        'width': main.parent().width()
                    });
                    main.find('.thim-loading-container').remove();
                });
            });

        });

        //$('.quiz-question-nav-buttons .prev-question,.quiz-question-nav-buttons .next-question,.button-finish-quiz,.button-start-quiz,.button-retake-quiz').on('click', function () {
        $('.quiz-question-nav-buttons .prev-question,.quiz-question-nav-buttons .next-question,.button-start-quiz').on('click', function () {
            $(".thim-iframe-quiz iframe", parent.document.body).parent().append('<div class="thim-loading-container"><div class="thim-loading"></div></div>');
        });


        $(document).on('learn_press_before_retake_quiz', function (e, confirm) {
            if (confirm) {
                $(".thim-iframe-quiz iframe", parent.document.body).parent().append('<div class="thim-loading-container"><div class="thim-loading"></div></div>');
            }
            return confirm;
        });

        $(document).on('learn_press_before_finish_quiz', function (e, confirm) {
            if (confirm) {
                $(".thim-iframe-quiz iframe", parent.document.body).parent().append('<div class="thim-loading-container"><div class="thim-loading"></div></div>');
            }
            return confirm;
        });
    });

    // Learnpress custom code js
    $(document).ready(function () {

        $(document).on('mouseenter', '.quiz-question-nav .question-hint', function () {
            $(this).find('.quiz-hint-content').addClass('quiz-active');
        }).on('mouseleave', '.quiz-question-nav .question-hint', function () {
            $(this).find('.quiz-hint-content').removeClass('quiz-active');
        });

        $(window).scroll(function (event) {
            if (thim_scroll && thim_scroll === false) {
                event.preventDefault();
            }
        });

        //Take this course - single course
        var payment_check = $('#learn_press_payment_form input:checked');
        if (!(payment_check.length > 0)) {
            $('.learn_press_payment_checkout').hide();
        } else {
            payment_check.parents('.learn_press_woo_payment_methods').find('.learn_press_payment_form').show();
        }
        $('.learn_press_payment_checkout').on('click', function (event) {
            event.preventDefault();
            $(this).parents('.course-payment').find('.thim-enroll-course-button').trigger('click');
        });
        $('.learn_press_payment_close').on('click', function () {
            $(this).parent().hide();
        });
        $('#learn_press_payment_form input').on('change', function () {
            $('.learn_press_payment_checkout:hidden').show();
        });
    });


    $(window).load(function () {
        thim_quiz_index();
    });


    $(document).ready(function () {
        $(".course-wishlist-box [class*='course-wishlist']").on('click', function (event) {
            event.preventDefault();
            var $this = $(this);
            if ($this.hasClass('loading')) return;
            $this.addClass('loading');
            $this.toggleClass('course-wishlist');
            $this.toggleClass('course-wishlisted');
            $class = $this.attr('class');
            if ($this.hasClass('course-wishlisted')) {
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: 'add_wish_list',
                        course_id: $this.attr('course-id')
                    },
                    success: function () {
                        $this.removeClass('loading')
                    },
                    error: function () {
                        $this.removeClass('loading')
                    }
                });
            }
            if ($this.hasClass('course-wishlist')) {
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: 'remove_wish_list',
                        course_id: $this.attr('course-id')
                    },
                    success: function () {
                        $this.removeClass('loading')
                    },
                    error: function () {
                        $this.removeClass('loading')
                    }
                });
            }
        });

    });

    $(document).on('click', '#course-review-load-more', function () {
        var $button = $(this);
        if (!$button.is(':visible')) return;
        $button.addClass('loading');
        var paged = parseInt($(this).attr('data-paged')) + 1;
        $.ajax({
            type: "POST",
            dataType: 'html',
            url: window.location.href,
            data: {
                action: 'learn_press_load_course_review',
                paged: paged
            },
            success: function (response) {
                var $content = $(response),
                    $new_review = $content.find('.course-reviews-list>li');
                $('#course-reviews .course-reviews-list').append($new_review);
                if ($content.find('#course-review-load-more').length) {
                    $button.removeClass('loading').attr('data-paged', paged);
                } else {
                    $button.remove();
                }
            }
        });
    });


    $(document).on('click', '.single-lpr_course .course-meta .course-review .value', function () {
        var review_tab = $('.course-tabs a[href="#tab-course-review"]');
        if (review_tab.length > 0) {
            review_tab.trigger('click');
            $('body, html').animate({
                scrollTop: review_tab.offset().top - 50
            }, 800);
        }
    });

    //Widget live search course
    var search_timer = false;

    function thimlivesearch(contain) {
        var input_search = contain.find('.courses-search-input'),
            list_search = contain.find('.courses-list-search'),
            keyword = input_search.val(),
            loading = contain.find('.fa-search,.fa-times');

        if (keyword) {
            if (keyword.length < 1) {
                return;
            }
            loading.addClass('fa-spinner fa-spin');
            jQuery.ajax({
                type: 'POST',
                data: 'action=courses_searching&keyword=' + keyword + '&from=search',
                url: ajaxurl,
                success: function (html) {
                    var data_li = '';
                    var items = jQuery.parseJSON(html);
                    if (!items.error) {
                        jQuery.each(items, function (index) {
                            if (index == 0) {
                                data_li += '<li class="ui-menu-item' + this['id'] + ' ob-selected"><a class="ui-corner-all" href="' + this['guid'] + '">' + this['title'] + '</a></li>';
                            } else {
                                data_li += '<li class="ui-menu-item' + this['id'] + '"><a class="ui-corner-all" href="' + this['guid'] + '">' + this['title'] + '</a></li>';
                            }
                        });
                        list_search.html('').append(data_li);
                    }
                    thimsearchHover();
                    loading.removeClass('fa-spinner fa-spin');
                },
                error: function (html) {
                }
            });
        }
    }

    function thimsearchHover() {
        jQuery('.courses-list-search li').on('mouseenter', function () {
            jQuery('.courses-list-search li').removeClass('ob-selected');
            jQuery(this).addClass('ob-selected');
        });
    }

    jQuery(document).ready(function () {

        jQuery('.thim-course-search-overlay .search-toggle').on('click', function (e) {
            e.stopPropagation();
            var parent = jQuery(this).parent();
            jQuery('body').addClass('thim-search-active');
            setTimeout(function () {
                parent.find('.thim-s').focus();
            }, 500);

        });
        jQuery('.search-popup-bg').on('click', function () {
            var parent = jQuery(this).parent();
            window.clearTimeout(search_timer);
            parent.find('.courses-list-search').empty();
            parent.find('.thim-s').val('');
            jQuery('body').removeClass('thim-search-active');
        });

        jQuery('.courses-search-input').on('keyup', function (event) {
            clearTimeout(jQuery.data(this, 'search_timer'));
            var contain = jQuery(this).parents('.courses-searching'),
                list_search = contain.find('.courses-list-search'),
                item_search = list_search.find('>li');

            if (event.which == 13) {
                event.preventDefault();
                jQuery(this).stop();
            } else if (event.which == 38) {
                if (navigator.userAgent.indexOf('Chrome') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Chrome') + 7).split(' ')[0]) >= 15) {
                    var selected = item_search.filter(".ob-selected");
                    if (item_search.length > 1) {
                        item_search.removeClass("ob-selected");
                        // if there is no element before the selected one, we select the last one
                        if (selected.prev().length == 0) {
                            selected.siblings().last().addClass("ob-selected");
                        } else { // otherwise we just select the next one
                            selected.prev().addClass("ob-selected");
                        }
                    }
                }
            } else if (event.which == 40) {
                if (navigator.userAgent.indexOf('Chrome') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Chrome') + 7).split(' ')[0]) >= 15) {
                    var selected = item_search.filter(".ob-selected");
                    if (item_search.length > 1) {
                        item_search.removeClass("ob-selected");

                        // if there is no element before the selected one, we select the last one
                        if (selected.next().length == 0) {
                            selected.siblings().first().addClass("ob-selected");
                        } else { // otherwise we just select the next one
                            selected.next().addClass("ob-selected");
                        }
                    }
                }
            } else if (event.which == 27) {
                if (jQuery('body').hasClass('thim-search-active')) {
                    jQuery('body').removeClass('thim-search-active');
                }
                list_search.html('');
                jQuery(this).val('');
                jQuery(this).stop();
            } else {
                var search_timer = setTimeout(function () {
                    thimlivesearch(contain);
                }, 500);
                jQuery(this).data('search_timer', search_timer);
            }
        });
        jQuery('.courses-search-input').on('keypress', function (event) {
            var item_search = jQuery(this).parents('.courses-searching').find('.courses-list-search>li');

            if (event.keyCode == 13) {
                var selected = jQuery(".ob-selected");
                if (selected.length > 0) {
                    var ob_href = selected.find('a').first().attr('href');
                    window.location.href = ob_href;
                }
                event.preventDefault();
            }
            if (event.keyCode == 27) {
                if (jQuery('body').hasClass('thim-search-active')) {
                    jQuery('body').removeClass('thim-search-active');
                }
                jQuery('.courses-list-search').html('');
                jQuery(this).val('');
                jQuery(this).stop();
            }
            if (event.keyCode == 38) {
                var selected = item_search.filter(".ob-selected");
                // if there is no element before the selected one, we select the last one
                if (item_search.length > 1) {
                    item_search.removeClass("ob-selected");
                    if (selected.prev().length == 0) {
                        selected.siblings().last().addClass("ob-selected");
                    } else { // otherwise we just select the next one
                        selected.prev().addClass("ob-selected");
                    }
                }
            }
            if (event.keyCode == 40) {
                var selected = item_search.filter(".ob-selected");
                if (item_search.length > 1) {
                    item_search.removeClass("ob-selected");
                    // if there is no element before the selected one, we select the last one
                    if (selected.next().length == 0) {
                        selected.siblings().first().addClass("ob-selected");
                    } else { // otherwise we just select the next one
                        selected.next().addClass("ob-selected");
                    }
                }
            }
        });

        jQuery('.courses-list-search,.courses-search-input').on('click', function (event) {
            event.stopPropagation();
        });

        jQuery('body').on('click', function () {
            if (!jQuery('body').hasClass('course-scroll-remove')) {
                jQuery('body').addClass('course-scroll-remove');
            }
        });

        jQuery(window).scroll(function () {
            if (jQuery('body').hasClass('course-scroll-remove') && jQuery(".courses-list-search li").length > 0) {
                jQuery(".courses-searching .courses-list-search").empty();
                jQuery(".courses-searching .thim-s").val('');
            }
        });

        jQuery('.courses-search-input').on('focus', function () {
            if (jQuery('body').hasClass('course-scroll-remove')) {
                jQuery('body').removeClass('course-scroll-remove');
            }
        });


        //Widget icon box
        $(".wrapper-box-icon").each(function () {
            var $this = $(this);
            if ($this.attr("data-icon")) {
                var $color_icon = $(".boxes-icon", $this).css('color');
                var $color_icon_change = $this.attr("data-icon");
            }
            if ($this.attr("data-icon-border")) {
                var $color_icon_border = $(".boxes-icon", $this).css('border-color');
                var $color_icon_border_change = $this.attr("data-icon-border");
            }
            if ($this.attr("data-icon-bg")) {
                var $color_bg = $(".boxes-icon", $this).css('background-color');
                var $color_bg_change = $this.attr("data-icon-bg");
            }


            if ($this.attr("data-btn-bg")) {
                var $color_btn_bg = $(".smicon-read", $this).css('background-color');
                var $color_btn_border = $(".smicon-read", $this).css('border-color');
                var $color_btn_bg_text_color = $(".smicon-read", $this).css('color');

                var $color_btn_bg_change = $this.attr("data-btn-bg");
                if ($this.attr("data-text-readmore")) {
                    var $color_btn_bg_text_color_change = $this.attr("data-text-readmore");
                } else {
                    $color_btn_bg_text_color_change = $color_btn_bg_text_color;
                }

                $(".smicon-read", $this).on({
                    'mouseenter': function () {
                        if ($("#style_selector_container").length > 0) {
                            if ($(".smicon-read", $this).css("background-color") != $color_btn_bg)
                                $color_btn_bg = $(".smicon-read", $this).css('background-color');
                        }
                        $(".smicon-read", $this).css({
                            'background-color': $color_btn_bg_change,
                            'border-color': $color_btn_bg_change,
                            'color': $color_btn_bg_text_color_change
                        });
                    },
                    'mouseleave': function () {
                        $(".smicon-read", $this).css({
                            'background-color': $color_btn_bg,
                            'border-color': $color_btn_border,
                            'color': $color_btn_bg_text_color
                        });
                    }
                });

            }

            $(".boxes-icon", $this).on({
                'mouseenter': function () {
                    if ($this.attr("data-icon")) {
                        $(".boxes-icon", $this).css({'color': $color_icon_change});
                    }
                    if ($this.attr("data-icon-bg")) {
                        /* for select style*/
                        if ($("#style_selector_container").length > 0) {
                            if ($(".boxes-icon", $this).css("background-color") != $color_bg)
                                $color_bg = $(".boxes-icon", $this).css('background-color');
                        }

                        $(".boxes-icon", $this).css({'background-color': $color_bg_change});
                    }
                    if ($this.attr("data-icon-border")) {
                        $(".boxes-icon", $this).css({'border-color': $color_icon_border_change});
                    }
                },
                'mouseleave': function () {
                    if ($this.attr("data-icon")) {
                        $(".boxes-icon", $this).css({'color': $color_icon});
                    }
                    if ($this.attr("data-icon-bg")) {
                        $(".boxes-icon", $this).css({'background-color': $color_bg});
                    }
                    if ($this.attr("data-icon-border")) {
                        $(".boxes-icon", $this).css({'border-color': $color_icon_border});
                    }
                }
            });

        });
        /* End Icon Box */

        //Background video
        $('.bg-video-play').on("click", function () {
            var elem = $(this),
                video = $(this).parents('.thim-widget-icon-box').find('.full-screen-video'),
                player = video.get(0);
            if (player.paused) {
                player.play();
                elem.addClass('bg-pause');
            } else {
                player.pause();
                elem.removeClass('bg-pause');
            }
        });


        //wpcf7-form-submit
        $(document).on('click', '.wpcf7-form-control.wpcf7-submit', function () {
            var elem = $(this),
                form = elem.parents('.wpcf7-form');
            form.addClass('thim-sending');
            $(document).on('invalid.wpcf7', function (event) {
                form.removeClass('thim-sending');
            });
            $(document).on('spam.wpcf7', function (event) {
                form.removeClass('thim-sending');
            });
            $(document).on('mailsent.wpcf7', function (event) {
                form.removeClass('thim-sending');
            });
            $(document).on('mailfailed.wpcf7', function (event) {
                form.removeClass('thim-sending');
            });
        });

    });


    //Include script plugin miniorange-login
    jQuery(window).load(function () {
        // If cookie is set, scroll to the position saved in the cookie.
        if (jQuery.cookie("scroll") !== null) {
            jQuery(document).scrollTop(jQuery.cookie("scroll"));
            jQuery.cookie("scroll", null);
        }
        // When a button is clicked...
        jQuery('.custom-login-button').on("click", function () {
            // Set a cookie that holds the scroll position.
            jQuery.cookie("scroll", jQuery(document).scrollTop());
        });

        jQuery('.login-button').on("click", function () {
            // Set a cookie that holds the scroll position.
            jQuery.cookie("scroll", jQuery(document).scrollTop());

        });
    });

    //Include plugin event file events.js
    jQuery(document).ready(function () {
        // countdown each
        var counts = $('.tp_event_counter');
        for (var i = 0; i < counts.length; i++) {
            var time = $(counts[i]).attr('data-time');
            time = new Date(time);

            if (WPEMS) {
                $(counts[i]).countdown({
                    labels    : WPEMS.l18n.labels,
                    labels1   : WPEMS.l18n.label1,
                    until     : time,
                    serverSync: WPEMS.current_time,
                });
            } else {
                $(counts[i]).countdown({
                    labels    : TP_Event.l18n.labels,
                    labels1   : TP_Event.l18n.label1,
                    until     : time,
                    serverSync: TP_Event.current_time,
                });
            }
        }

        // owl-carausel
        var carousels = $('.tp_event_owl_carousel');
        for (var i = 0; i < carousels.length; i++) {
            var data = $(carousels[i]).attr('data-countdown');
            var options = {
                navigation: true, // Show next and prev buttons
                slideSpeed: 300,
                paginationSpeed: 400,
                singleItem: true
            };
            if (typeof data !== 'undefined') {
                data = JSON.parse(data);
                $.extend(options, data);

                $.each(options, function (k, v) {
                    if (v === 'true') {
                        options[k] = true;
                    } else if (v === 'false') {
                        options[k] = false;
                    }
                });
            }

            if (typeof options.slide === 'undefined' || options.slide === true) {
                $(carousels[i]).owlCarousel(options);
            }
            else {
                $(carousels[i]).removeClass('owl-carousel');
            }
        }
    });

    // Sticky sidebar
    jQuery(document).ready(function () {
        var offsetTop = 20;
        if ($("#wpadminbar").length) {
            offsetTop += $("#wpadminbar").outerHeight();
        }
        if ($("#masthead.sticky-header").length) {
            offsetTop += $("#masthead.sticky-header").outerHeight();
        }
        jQuery("#sidebar.sticky-sidebar").theiaStickySidebar({
            "containerSelector": "",
            "additionalMarginTop": offsetTop,
            "additionalMarginBottom": "0",
            "updateSidebarHeight": false,
            "minWidth": "768",
            "sidebarBehavior": "modern"
        });
    });

    // Prevent search when no content submited
    jQuery(document).ready(function () {
        $(".courses-searching form").submit(function () {
            var input_search = $(this).find("input[name='s']");
            if ($.trim(input_search.val()) === "") {
                input_search.focus();
                return false;
            }
        });

        $('form#bbp-search-form').submit(function () {
            if ($.trim($("#bbp_search").val()) === "") {
                $("#bbp_search").focus();
                return false;
            }
        });

        $("form.search-form").submit(function () {
            var input_search = $(this).find("input[name='s']");
            if ($.trim(input_search.val()) === "") {
                input_search.focus();
                return false;
            }
        });

        //Register form untispam
        $('form#registerform').submit(function (event) {
            var elem = $(this),
                input_username = elem.find('#user_login'),
                input_email = elem.find('#user_email'),
                input_captcha = $('.thim-login-captcha .captcha-result');

            if ($('#registerform #check_spam_register').length > 0 && $('#registerform #check_spam_register').val() != '') {
                event.preventDefault();
            }

            if (input_captcha.length > 0) {
                var captcha_1 = parseInt(input_captcha.data('captcha1')),
                    captcha_2 = parseInt(input_captcha.data('captcha2'));

                if (captcha_1 + captcha_2 != parseInt(input_captcha.val())) {
                    input_captcha.addClass('invalid').val('');
                    event.preventDefault();
                }
            }

            if (input_username.length > 0 && input_username.val() == '') {
                input_username.addClass('invalid');
                event.preventDefault();
            }

            if (input_email.length > 0 && input_email.val() == '') {
                input_email.addClass('invalid');
                event.preventDefault();
            }
        });

        $('#customer_login .register').submit(function (event) {
            var elem = $(this),
                input_username = elem.find('#reg_username'),
                input_email = elem.find('#reg_email'),
                input_pass = elem.find('#reg_password'),
                input_captcha = $('#customer_login .register .captcha-result');

            if (input_captcha.length > 0) {
                var captcha_1 = parseInt(input_captcha.data('captcha1')),
                    captcha_2 = parseInt(input_captcha.data('captcha2'));

                if (captcha_1 + captcha_2 != parseInt(input_captcha.val())) {
                    input_captcha.addClass('invalid').val('');
                    event.preventDefault();
                }
            }

            if (input_pass.length > 0 && input_pass.val() == '') {
                input_pass.addClass('invalid');
                event.preventDefault();
            }

            if (input_username.length > 0 && input_username.val() == '') {
                input_username.addClass('invalid');
                event.preventDefault();
            }

            if (input_email.length > 0 && input_email.val() == '') {
                input_email.addClass('invalid');
                event.preventDefault();
            }
        });

        $('#customer_login .register, #reg_username, #reg_email, #reg_password, .thim-login-captcha .captcha-result, #registerform #user_login,#registerform #user_email').on('focus', function () {
            $(this).removeClass('invalid');
        });

        $('.thim-language').on({
            'mouseenter': function () {
                $(this).children('.list-lang').stop(true, false).fadeIn(250);
            },
            'mouseleave': function () {
                $(this).children('.list-lang').stop(true, false).fadeOut(250);
            }
        });

        //Widget gallery-posts
        $(window).load(function () {
            if ($('.thim-widget-gallery-posts .wrapper-gallery-filter').length > 0) {
                $('.thim-widget-gallery-posts .wrapper-gallery-filter').isotope({filter: '*'});
            }
        });

        $(document).on('click', '.filter-controls .filter', function (e) {
            e.preventDefault();
            var filter = $(this).data('filter'),
                filter_wraper = $(this).parents('.thim-widget-gallery-posts').find('.wrapper-gallery-filter');
            $('.filter-controls .filter').removeClass('active');
            $(this).addClass('active');
            filter_wraper.isotope({filter: filter});
        });


        $(document).on('click', '.thim-gallery-popup', function (e) {
            e.preventDefault();
            var elem = $(this),
                post_id = elem.attr('data-id'),
                data = {action: 'thim_gallery_popup', post_id: post_id};
            elem.addClass('loading');
            $.post(ajaxurl, data, function (response) {
                elem.removeClass('loading');
                $('.thim-gallery-show').append(response);
                if ($('.thim-gallery-show img').length > 0) {
                    $('.thim-gallery-show').magnificPopup({
                        mainClass: 'my-mfp-zoom-in',
                        type: 'image',
                        delegate: 'a',
                        showCloseBtn: false,
                        gallery: {
                            enabled: true
                        },
                        callbacks: {
                            open: function () {
                                $.magnificPopup.instance.close = function () {
                                    $('.thim-gallery-show').empty();
                                    $.magnificPopup.proto.close.call(this);
                                };
                            },
                        }
                    }).magnificPopup('open');
                } else {
                    $.magnificPopup.open({
                        mainClass: 'my-mfp-zoom-in',
                        items: {
                            src: $('.thim-gallery-show'),
                            type: 'inline'
                        },
                        showCloseBtn: false,
                        callbacks: {
                            open: function () {
                                $.magnificPopup.instance.close = function () {
                                    $('.thim-gallery-show').empty();
                                    $.magnificPopup.proto.close.call(this);
                                };
                            },
                        }
                    });
                }
            });
        });

        $('.widget-button.custom_style').each(function () {
            var elem = $(this),
                old_style = elem.attr('style'),
                hover_style = elem.data('hover');
            elem.on({
                'mouseenter': function () {
                    elem.attr('style', hover_style);
                },
                'mouseleave': function () {
                    elem.attr('style', old_style);
                }
            })
        });

        $(document).on('click', '#thim-popup-login .close-popup', function (event) {
            event.preventDefault();
            $('body').removeClass('thim-popup-active');
            $('#thim-popup-login').removeClass('active');
        });

        $(document).on('click', '.thim-login-popup .login', function (event) {
            // if ($(window).width() > 767) {
                event.preventDefault();
                let $popup = $('#thim-popup-login');
                $('body').addClass('thim-popup-active');
                $popup.addClass('active');
                if ($(this).hasClass('login')) {
                    $popup.addClass('sign-in');
                } else {
                    $popup.addClass('sign-up');
                }
            // }
        });

        $(document).on('click', '#thim-popup-login', function (e) {
            if ($(e.target).attr('id') == 'thim-popup-login') {
                $('body').removeClass('thim-popup-active');
                $('#thim-popup-login').removeClass('active');
            }
        });


        $('#thim-popup-login form[name="loginform"]').submit(function (event) {
            event.preventDefault();

            var form = $(this),
                elem = $('#thim-popup-login .thim-login-container'),
                input_username = elem.find('#thim_login').val(),
                input_password = elem.find('#thim_pass').val(),
                wp_submit = elem.find('#wp-submit').val();

            if (input_username == '' || input_password == '') {
                return;
            }
            elem.addClass('loading');
            elem.append('<div class="cssload-container"><div class="cssload-loading"><i></i><i></i><i></i><i></i></div></div>');
            elem.find('.message').slideDown().remove();

            var data = {
                action: 'thim_login_ajax',
                data: form.serialize() + '&wp-submit=' + wp_submit,
            };

            $.post(ajaxurl, data, function (response) {
                try {
                    var response = JSON.parse(response);
                    elem.find('.thim-login').append(response.message);
                    if (response.code == '1') {
                        if (response.redirect) {
                            if (window.location.href == response.redirect) {
                                location.reload();
                            } else {
                                window.location.href = response.redirect;
                            }
                        } else {
                            location.reload();
                        }
                    }
                } catch (e) {
                    return false;
                }
                elem.removeClass('loading');
                elem.find('.cssload-container').remove();
            });

            return false;
        });

        $('.thim-video-popup .button-popup').on('click', function (e) {
            var item = $(this);
            e.preventDefault();
            $.magnificPopup.open({
                items       : {
                    src : item.parent().parent().find('.video-content'),
                    type: 'inline'
                },
                showCloseBtn: false,
                callbacks   : {
                    open: function () {
                        $('body').addClass('thim-popup-active');
                        $.magnificPopup.instance.close = function () {
                            $('body').removeClass('thim-popup-active');
                            $.magnificPopup.proto.close.call(this);
                        };
                    },
                }
            });
        });

    });

    $(window).load(function () {
        thim_min_height_carousel('.thim-carousel-instructors', '.instructor-item');
        thim_min_height_carousel('.thim-owl-carousel-post', '.image');
        thim_min_height_carousel('.thim-course-carousel', '.course-thumbnail');

        thim_min_height_content_area();
    });

    function thim_min_height_carousel(el, child) {
        var $elements = $(el);

        $elements.each(function () {
           var $element = $(this),
               $child = child ? $element.find(child) : $element.children(),
               maxHeight = 0;

            $child.each(function () {
                var thisHeight = $(this).outerHeight();
                if(thisHeight > maxHeight){
                    maxHeight = thisHeight;
                }
            }).css('min-height', maxHeight);
        });
    }

    function thim_min_height_content_area() {
        var content_area = $('#main-content .content-area'),
            footer = $('#main-content .site-footer'),
            winH = $(window).height();
        if (content_area.length > 0 && footer.length > 0) {
            content_area.css('min-height', winH - footer.height());
        }
    }


    //Widget counter box
    (function (a) {
        a.fn.countTo = function (g) {
            g = g || {};
            return a(this).each(function () {
                function e(a) {
                    a = b.formatter.call(h, a, b);
                    f.html(a);
                }

                var b = a.extend({}, a.fn.countTo.defaults, {
                        from: a(this).data("from"),
                        to: a(this).data("to"),
                        speed: a(this).data("speed"),
                        refreshInterval: a(this).data("refresh-interval"),
                        decimals: a(this).data("decimals")
                    }, g), j = Math.ceil(b.speed / b.refreshInterval), l = (b.to - b.from) / j, h = this, f = a(this),
                    k = 0, c = b.from, d = f.data("countTo") || {};
                f.data("countTo", d);
                d.interval && clearInterval(d.interval);
                d.interval =
                    setInterval(function () {
                        c += l;
                        k++;
                        e(c);
                        "function" == typeof b.onUpdate && b.onUpdate.call(h, c);
                        k >= j && (f.removeData("countTo"), clearInterval(d.interval), c = b.to, "function" == typeof b.onComplete && b.onComplete.call(h, c));
                    }, b.refreshInterval);
                e(c);
            });
        };
        a.fn.countTo.defaults = {
            from: 0, to: 0, speed: 1E3, refreshInterval: 100, decimals: 0, formatter: function (a, e) {
                return a.toFixed(e.decimals);
            }, onUpdate: null, onComplete: null
        };
    })(jQuery);

    jQuery(window).load(function () {
        if (jQuery().waypoint) {
            jQuery('.counter-box').waypoint(function () {
                jQuery(this).find('.display-percentage').each(function () {
                    var percentage = jQuery(this).data('percentage');
                    jQuery(this).countTo({from: 0, to: percentage, refreshInterval: 40, speed: 1000});
                });
            }, {
                triggerOnce: true,
                offset: 'bottom-in-view'
            });
        }
    });


})(jQuery);
