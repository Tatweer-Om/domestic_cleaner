(function ($) {

    "use strict";


    /*------------------------------------------
     = ALL ESSENTIAL FUNCTIONS
 -------------------------------------------*/

    const $navbar = $(".navigation-holder");
    const $openBtn = $(".mobail-menu .open-btn");
    const $xButton = $(".mobail-menu .navbar-toggler");
    const $mainNav = $("#navbar > ul");
    const $body = $("body");
    const $menuClose = $(".menu-close");

    // Toggle mobile navigation
    function toggleMobileNavigation() {
        $openBtn.on("click", e => {
            e.stopImmediatePropagation();
            $navbar.toggleClass("slideInn");
            $xButton.toggleClass("x-close");
            return false;
        });
    }
    toggleMobileNavigation();

    // Toggle class for small menu based on window width
    function toggleClassForSmallNav() {
        if (window.innerWidth <= 991) {
            $mainNav.addClass("small-nav");
        } else {
            $mainNav.removeClass("small-nav");
        }
    }
    toggleClassForSmallNav();

    // Small menu functionality
    function smallNavFunctionality() {
        const windowWidth = window.innerWidth;
        const $smallNav = $(".navigation-holder > .small-nav");
        const $subMenu = $smallNav.find(".sub-menu");
        const $megaMenu = $smallNav.find(".mega-menu");
        const $menuItems = $smallNav.find(".menu-item-has-children > a");

        if (windowWidth <= 991) {
            $subMenu.hide();
            $megaMenu.hide();
            $menuItems.off("click").on("click", e => {
                const $this = $(e.currentTarget);
                $this.siblings().slideToggle();
                e.preventDefault();
                e.stopImmediatePropagation();
                $this.toggleClass("rotate");
            });
        } else {
            $mainNav.find(".sub-menu, .mega-menu").show();
        }
    }
    smallNavFunctionality();

    // Close menu handlers
    $body.on("click", () => $navbar.removeClass("slideInn"));
    $menuClose.on("click", () => {
        $navbar.removeClass("slideInn");
        $openBtn.removeClass("x-close");
    });

    // Toggles (toggle1 - toggle4)
    const toggles = [
        { btn: '#toggle1', target: '.create-account', wrap: '.caupon-wrap.s1', toggleClass: 'active-border' },
        { btn: '#toggle2', target: '#open2', wrap: '.caupon-wrap.s2', toggleClass: 'coupon-2' },
        { btn: '#toggle3', target: '#open3', wrap: '.caupon-wrap.s2', toggleClass: 'coupon-2' },
        { btn: '#toggle4', target: '#open4', wrap: '.caupon-wrap.s3', toggleClass: 'coupon-2' }
    ];

    toggles.forEach(({ btn, target, wrap, toggleClass }) => {
        $(btn).on("click", () => {
            $(target).slideToggle();
            $(wrap).toggleClass(toggleClass);
        });
    });

    // Payment select toggles
    $('.payment-select .addToggle').on('click', () => {
        $('.payment-name').addClass('active');
        $('.payment-option').removeClass('active');
    });
    $('.payment-select .removeToggle').on('click', () => {
        $('.payment-option').addClass('active');
        $('.payment-name').removeClass('active');
    });

    // Bootstrap tooltips initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // HERO SLIDER
    const menu = [];
    jQuery('.swiper-slide').each(function () {
        menu.push(jQuery(this).find('.slide-inner').attr("data-text"));
    });
    const interleaveOffset = 0.5;
    const swiperOptions = {
        loop: true,
        speed: 1000,
        parallax: true,
        autoplay: {
            delay: 6500,
            disableOnInteraction: false,
        },
        watchSlidesProgress: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        on: {
            progress() {
                const swiper = this;
                for (let i = 0; i < swiper.slides.length; i++) {
                    const slideProgress = swiper.slides[i].progress;
                    const innerOffset = swiper.width * interleaveOffset;
                    const innerTranslate = slideProgress * innerOffset;
                    swiper.slides[i].querySelector(".slide-inner").style.transform =
                        `translate3d(${innerTranslate}px, 0, 0)`;
                }
            },
            touchStart() {
                const swiper = this;
                for (let i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = "";
                }
            },
            setTransition(speed) {
                const swiper = this;
                for (let i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = `${speed}ms`;
                    swiper.slides[i].querySelector(".slide-inner").style.transition = `${speed}ms`;
                }
            }
        }
    };
    const swiper = new Swiper(".swiper-container", swiperOptions);

    // Data background image
    $(".slide-bg-image").each(function () {
        const $this = $(this);
        const bg = $this.data("background");
        if (bg) {
            $this.css("background-image", `url(%24%7bbg%7d.html)`);
        }
    });

    /*------------------------------------------
        = HIDE PRELOADER
    -------------------------------------------*/
    function preloader() {
        const $preloader = $('.preloader');
        if ($preloader.length) {
            $preloader.delay(100).fadeOut(500, () => {
                wow.init();
            });
        }
    }

    /*------------------------------------------
        = WOW ANIMATION SETTING
    -------------------------------------------*/
    const wow = new WOW({
        boxClass: 'wow',
        animateClass: 'animated',
        offset: 0,
        mobile: true,
        live: true
    });

    /*------------------------------------------
        = ACTIVE POPUP IMAGE
    -------------------------------------------*/
    if ($(".fancybox").length) {
        $(".fancybox").fancybox({
            openEffect: "elastic",
            closeEffect: "elastic",
            wrapCSS: "project-fancybox-title-style"
        });
    }

    /*------------------------------------------
        = POPUP VIDEO
    -------------------------------------------*/
    $(".video-btn").on("click", function () {
        $.fancybox({
            href: this.href,
            aspectRatio: true,
            type: $(this).data("type"),
            title: this.title,
            helpers: {
                title: { type: 'inside' },
                media: {}
            },
            beforeShow() {
                $(".fancybox-wrap").addClass("gallery-fancybox");
            }
        });
        return false;
    });

    /*------------------------------------------
        = ACTIVE GALLERY POPUP IMAGE
    -------------------------------------------*/
    if ($(".popup-gallery").length) {
        $('.popup-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: { enabled: true },
            zoom: {
                enabled: true,
                duration: 300,
                easing: 'ease-in-out',
                opener: function (openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }
        });
    }

    /*------------------------------------------
        = FUNCTION FORM SORTING GALLERY
    -------------------------------------------*/
    function sortingGallery() {
        const $galleryFilters = $(".sortable-gallery .gallery-filters");
        if ($galleryFilters.length) {
            const $container = $('.gallery-container');
            $container.isotope({
                filter: '*',
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false,
                }
            });

            $galleryFilters.find('li a').on("click", function () {
                $galleryFilters.find('li .current').removeClass('current');
                $(this).addClass('current');
                const selector = $(this).attr('data-filter');
                $container.isotope({
                    filter: selector,
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false,
                    }
                });
                return false;
            });
        }
    }
    sortingGallery();

    /*------------------------------------------
        = MASONRY GALLERY SETTING
    -------------------------------------------*/
    function masonryGridSetting() {
        const $masonryGallery = $('.masonry-gallery');
        if ($masonryGallery.length) {
            const $grid = $masonryGallery.masonry({
                itemSelector: '.grid-item',
                columnWidth: '.grid-item',
                percentPosition: true
            });
            $grid.imagesLoaded().progress(() => {
                $grid.masonry('layout');
            });
        }
    }
    // Uncomment below if you want to enable masonry grid on page load
    // masonryGridSetting();

    document.addEventListener("DOMContentLoaded", () => {
        const grid = document.querySelector('.grid');
        if (grid) {
            new Masonry(grid, {
                itemSelector: '.grid-item',
                columnWidth: '.grid-item',
                percentPosition: true
            });
        }
    });

    /*------------------------------------------
      = FUNFACT
    -------------------------------------------*/
    if ($(".odometer").length) {
        $('.odometer').appear();
        $(document.body).on('appear', '.odometer', () => {
            $(".odometer").each(function () {
                const countNumber = $(this).attr("data-count");
                $(this).html(countNumber);
            });
        });
    }

    /*------------------------------------------
        = STICKY HEADER
    -------------------------------------------*/

    // Clone nav for sticky menu
    function cloneNavForStickyMenu($ele, newElmClass) {
        $ele.addClass('original').clone().insertAfter($ele).addClass(newElmClass).removeClass('original');
    }

    // Clone home style 1 navigation for sticky menu
    const $wpoNavigation = $('.wpo-site-header .navigation');
    if ($wpoNavigation.length) {
        cloneNavForStickyMenu($wpoNavigation, "sticky-header");
    }

    let lastScrollTop = 0;

    function stickyMenu(targetMenu, toggleClass) {
        const st = $(window).scrollTop();

        if (st > 1000) {
            if (st > lastScrollTop) {
                // scroll down - hide sticky menu
                targetMenu.removeClass(toggleClass);
            } else {
                // scroll up - show sticky menu
                targetMenu.addClass(toggleClass);
            }
        } else {
            targetMenu.removeClass(toggleClass);
        }

        lastScrollTop = st;
    }

    $(window).on('scroll', () => {
        stickyMenu($('.sticky-header'), 'active');
    });

    /*------------------------------------------
     = Header search toggle
 -------------------------------------------*/
    if ($(".header-search-form-wrapper").length) {
        const $searchToggleBtn = $(".search-toggle-btn");
        const $searchToggleBtnIcon = $(".search-toggle-btn i");
        const $searchContent = $(".header-search-form");
        const $body = $("body");

        $searchToggleBtn.on("click", e => {
            $searchContent.toggleClass("header-search-content-toggle");
            $searchToggleBtnIcon.toggleClass("fi flaticon-magnifying-glass fi ti-close");
            e.stopPropagation();
        });

        $body.on("click", () => {
            $searchContent.removeClass("header-search-content-toggle");
        }).find($searchContent).on("click", e => {
            e.stopPropagation();
        });
    }

    /*------------------------------------------
        = Header shopping cart toggle
    -------------------------------------------*/
    if ($(".mini-cart").length) {
        const $cartToggleBtn = $(".cart-toggle-btn");
        const $cartContent = $(".mini-cart-content");
        const $cartCloseBtn = $(".mini-cart-close");
        const $body = $("body");

        $cartToggleBtn.on("click", e => {
            $cartContent.toggleClass("mini-cart-content-toggle");
            e.stopPropagation();
        });

        $cartCloseBtn.on("click", e => {
            $cartContent.removeClass("mini-cart-content-toggle");
            e.stopPropagation();
        });

        $body.on("click", () => {
            $cartContent.removeClass("mini-cart-content-toggle");
        }).find($cartContent).on("click", e => {
            e.stopPropagation();
        });
    }

    /*------------------------------------------
        = partners-slider
    -------------------------------------------*/
    $('.partners-slider').slick({
        infinite: true,
        autoplay: true,
        arrows: false,
        dots: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: [
            { breakpoint: 1399, settings: { slidesToShow: 5, slidesToScroll: 1 } },
            { breakpoint: 1199, settings: { slidesToShow: 5, slidesToScroll: 1 } },
            { breakpoint: 991, settings: { slidesToShow: 4, slidesToScroll: 1 } },
            { breakpoint: 757, settings: { slidesToShow: 3, slidesToScroll: 1 } },
            { breakpoint: 575, settings: { slidesToShow: 2, slidesToScroll: 1 } }
        ]
    });

    /*------------------------------------------
        = service-slider
    -------------------------------------------*/
    $('.service-slider').slick({
        infinite: true,
        autoplay: true,
        arrows: false,
        dots: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
            { breakpoint: 1399, settings: { slidesToShow: 3, slidesToScroll: 1 } },
            { breakpoint: 1199, settings: { slidesToShow: 2, slidesToScroll: 1 } },
            { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 1 } },
            { breakpoint: 757, settings: { slidesToShow: 1, slidesToScroll: 1 } },
            { breakpoint: 575, settings: { slidesToShow: 1, slidesToScroll: 1 } }
        ]
    });

    /*------------------------------------------
        = service-slider-s2
    -------------------------------------------*/
    $('.service-slider-s2').slick({
        infinite: true,
        autoplay: true,
        arrows: false,
        dots: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            { breakpoint: 1399, settings: { slidesToShow: 3, slidesToScroll: 1 } },
            { breakpoint: 1199, settings: { slidesToShow: 3, slidesToScroll: 1 } },
            { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 1 } },
            { breakpoint: 757, settings: { slidesToShow: 1, slidesToScroll: 1 } },
            { breakpoint: 575, settings: { slidesToShow: 1, slidesToScroll: 1 } }
        ]
    });

    /*------------------------------------------
        = Testimonial SLIDER
    -------------------------------------------*/
    if ($(".wpo-testimonial-active").length) {
        $(".wpo-testimonial-active").owlCarousel({
            autoplay: false,
            smartSpeed: 300,
            margin: 30,
            loop: true,
            autoplayHoverPause: true,
            dots: false,
            nav: false,
            responsive: {
                0: { items: 1, dots: true, nav: false },
                500: { items: 1, dots: true, nav: false },
                768: { items: 1 },
                991: { items: 2 },
                1200: { items: 2 },
                1400: { items: 2 },
            }
        });
    }

    /*------------------------------------------
        = SHOP DETAILS PAGE PRODUCT SLIDER
    -------------------------------------------*/
    if ($(".shop-single-slider").length) {
        $('.slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.slider-nav'
        });
        $('.slider-nav').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            focusOnSelect: true,
            prevArrow: '<i class="nav-btn nav-btn-lt ti-arrow-left"></i>',
            nextArrow: '<i class="nav-btn nav-btn-rt ti-arrow-right"></i>',
            responsive: [
                { breakpoint: 500, settings: { slidesToShow: 3, infinite: true } },
                { breakpoint: 400, settings: { slidesToShow: 2 } }
            ]
        });
    }

    /*------------------------------------------
        = TOUCHSPIN FOR PRODUCT SINGLE PAGE
    -------------------------------------------*/
    if ($("input[name='product-count']").length) {
        $("input[name='product-count']").TouchSpin({
            verticalbuttons: true
        });
    }

    /*-----------------------
        cart-plus-minus-button 
    -------------------------*/
    $(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');

    $(".qtybutton").on("click", function () {
        const $button = $(this);
        const $input = $button.parent().find("input");
        const oldValue = parseFloat($input.val()) || 0;
        let newVal;

        if ($button.text() === "+") {
            newVal = oldValue + 1;
        } else {
            newVal = oldValue > 0 ? oldValue - 1 : 0;
        }
        $input.val(newVal);
    });



    /*------------------------------------------
   = BACK TO TOP BTN SETTING
-------------------------------------------*/
    $("body").append("<a href='#' class='back-to-top'><i class='ti-arrow-up'></i></a>");

    function toggleBackToTopBtn() {
        const amountScrolled = 1000;
        if ($(window).scrollTop() > amountScrolled) {
            $("a.back-to-top").fadeIn("slow");
        } else {
            $("a.back-to-top").fadeOut("slow");
        }
    }

    $("a.back-to-top").on("click", function (e) {
        e.preventDefault(); // better than return false here
        $("html,body").animate({ scrollTop: 0 }, 700);
    });


    /*------------------------------------------
        = Appointment FORM SUBMISSION
    -------------------------------------------*/
    // Reusable AJAX form handler
    function handleAjaxForm($form, loaderSelector, successSelector, errorSelector) {
        $.ajax({
            type: "POST",
            url: "mail-contact.php",
            data: $form.serialize(),
            dataType: "json",
            beforeSend: function () {
                $(loaderSelector).show();
            },
            success: function (response) {
                $(loaderSelector).hide();
                if (response.status === "success") {
                    $(successSelector).slideDown("slow").text("Form submitted successfully!");
                    setTimeout(function () {
                        $(successSelector).slideUp("slow");
                    }, 3000);
                    $form[0].reset();
                } else {
                    $(errorSelector).slideDown("slow").text(response.message || "Something went wrong.");
                    setTimeout(function () {
                        $(errorSelector).slideUp("slow");
                    }, 3000);
                }
            },
            error: function (xhr) {
                $(loaderSelector).hide();
                let msg = "An error occurred.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $(errorSelector).slideDown("slow").text(msg);
                setTimeout(function () {
                    $(errorSelector).slideUp("slow");
                }, 3000);
            }
        });
    }

    // Contact Form Main Validation
    if ($("#contact-form-main").length) {
        $("#contact-form-main").validate({
            rules: {
                name: { required: true, minlength: 2 },
                email: { required: true, email: true },
                phone: { required: true, minlength: 10 },
                zip: { required: true, digits: true },
                subject: { required: true },
                approx: { required: true },
                bed: { required: true },
                bath: { required: true }
            },
            messages: {
                name: "Please enter your name",
                email: "Please enter a valid email address",
                phone: "Please enter your phone number",
                zip: "Please enter your zip code",
                subject: "Please select a service",
                approx: "Please enter approx. square footage",
                bed: "Please select bedroom option",
                bath: "Please select bathroom option"
            },
            submitHandler: function (form) {
                handleAjaxForm($(form), "#loader", "#success", "#error");
                return false;
            }
        });
    }

    // Contact Form 2 Validation
    if ($("#contact-form").length) {
        $("#contact-form").validate({
            rules: {
                name: { required: true, minlength: 2 },
                email: { required: true, email: true },
                subject: { required: true },
                date: { required: true },
                time: { required: true }
            },
            messages: {
                name: "Please enter your name",
                email: "Please enter your email address",
                subject: "Please select your subject",
                date: "Please select a date",
                time: "Please select a time"
            },
            submitHandler: function (form) {
                handleAjaxForm($(form), "#loader", "#success", "#error");
                return false;
            }
        });
    }

    /*==========================================================================
        WHEN DOCUMENT LOADING
    ==========================================================================*/
    $(window).on('load', function () {
        preloader();
        masonryGridSetting();
        sortingGallery();

        toggleMobileNavigation();
        smallNavFunctionality();
    });


    /*==========================================================================
        WHEN WINDOW SCROLL
    ==========================================================================*/
    $(window).on("scroll", function () {
        if ($(".wpo-site-header").length) {
            stickyMenu($('.wpo-site-header .navigation'), "sticky-on");
        }
        toggleBackToTopBtn();
    });


    /*==========================================================================
        WHEN WINDOW RESIZE
    ==========================================================================*/
    $(window).on("resize", function () {
        toggleClassForSmallNav();

        clearTimeout($.data(this, 'resizeTimer'));
        $.data(this, 'resizeTimer', setTimeout(() => {
            smallNavFunctionality();
        }, 200));
    });



})(window.jQuery);



/* languageSelect js */
document.addEventListener('DOMContentLoaded', () => {
    const selectElement = document.getElementById('languageSelect');
    if (!selectElement) return;

    const customSelectWrapper = document.querySelector('.custom-select-wrapper');
    const customSelect = document.querySelector('.custom-select');
    const customOptions = document.querySelector('.custom-options');

    // Initialize with selected option
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    customSelect.innerHTML = `<img src="${selectedOption.getAttribute('data-icon')}" alt=""> ${selectedOption.text}`;

    // Populate custom options
    Array.from(selectElement.options).forEach(option => {
        const optionDiv = document.createElement('div');
        optionDiv.innerHTML = `<img src="${option.getAttribute('data-icon')}" alt=""> ${option.text}`;
        optionDiv.addEventListener('click', () => {
            customSelect.innerHTML = `<img src="${option.getAttribute('data-icon')}" alt=""> ${option.text}`;
            selectElement.value = option.value;
            customOptions.style.display = 'none';
            // Optional: trigger change event if needed
            selectElement.dispatchEvent(new Event('change'));
        });
        customOptions.appendChild(optionDiv);
    });

    // Toggle options dropdown
    customSelectWrapper.addEventListener('click', () => {
        customOptions.style.display = (customOptions.style.display === 'block') ? 'none' : 'block';
    });

    // Close dropdown if click outside
    document.addEventListener('click', (event) => {
        if (!customSelectWrapper.contains(event.target)) {
            customOptions.style.display = 'none';
        }
    });
});


/* accordion js */
document.addEventListener('DOMContentLoaded', () => {
    const headers = document.querySelectorAll('.accordion-header');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const isActive = item.classList.contains('active');

            // Close all accordion items
            document.querySelectorAll('.accordion-item.active').forEach(activeItem => {
                activeItem.classList.remove('active');
            });

            // Toggle current item
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });
});
