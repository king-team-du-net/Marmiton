/*
 * App Marmiton Recipe | Bootstrap 5
 * Copyright 2020-2023 rdequidt
 * Theme libs scripts
*/

/** Libs */


/** Mode Modules */

/** Summernote */
import 'summernote/dist/summernote-bs5.min.js';

/** Fontawesome Iconpicker */
import 'fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js';

/** Magnific Popup - Lightbox for the gallery */
import 'magnific-popup/dist/jquery.magnific-popup.min.js';

/** Smooth scroll */
import SmoothScroll from 'smooth-scroll/dist/smooth-scroll.polyfills.min.js';

/** Bootstrap Select */
// import 'bootstrap-select/dist/js/bootstrap-select.min.js';
import '../vendor/bootstrap-select/js/bootstrap-select.min.js';

/** Object Fit Images - Fallback for browsers that don't support object-fit */
import 'object-fit-images/dist/ofi.min.js';

/** Swiper Carousel */
// import Swiper from 'swiper';
import Swiper from '../vendor/swiper/swiper-bundle.min.js';

/** Slider */
import noUiSlider from 'nouislider/dist/nouislider.min.js';

/** Cookie */
//import 'jquery.cookie/jquery.cookie.js';
import cookieBar from '../vendor/jquery.cookieBar/jquery.cookieBar.min.js';

/** Main Theme JS file */
// import './theme.js';

// Declares utility functions

$(function () {

    "use strict";

    // ------------------------------------------------------- //
    //   Checks if an email address is valid
    // ------------------------------------------------------ //

    function isEmailValid(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    // ------------------------------------------------------- //
    //   Bootstrap Touchspin
    // ------------------------------------------------------ //

    if ($('.touchspin-integer').length) {
        $(".touchspin-integer").TouchSpin({
            verticalbuttons: true,
            min: $(this).data('min'),
            max: $(this).data('max')
        });
    }
    if ($('.touchspin-decimal').length) {
        $(".touchspin-decimal").TouchSpin({
            verticalbuttons: true,
            min: $(this).data('min'),
            max: $(this).data('max'),
            decimals: 2,
            step: 0.01,
            prefix: $('body').data('currency-symbol')
        });
    }

    // ------------------------------------------------------- //
    //   Initializes Font Awesome picker
    // ------------------------------------------------------ //

    if ($('.icon-picker').length) {
        $('.icon-picker').iconpicker({
            animation: false,
            inputSearch: true
        });
    }

    // ------------------------------------------------------- //
    //   Initializes wysiwyg editor
    // ------------------------------------------------------ //

    if ($('.wysiwyg').length) {
        $('.wysiwyg').summernote({
            height: 500,
        });
    }

    // ------------------------------------------------------- //
    //   Tags input
    // ------------------------------------------------------ //

    if ($(".tags-input").length) {
        $(".tags-input").each(function () {
            $(this).tagsinput({
                tagClass: 'badge bg-primary'
            });
        });
        $('.bootstrap-tagsinput').each(function () {
            $(this).addClass('form-control');
        });
    }

    // ------------------------------------------------------- //
    //   Datetimepickers
    // ------------------------------------------------------ //

    /*
    if ($('.datetimepicker').length) {
        $('.datetimepicker').each(function () {
            $(this).datetimepicker({
                format: 'Y-m-d H:i'
            });
        });
    }

    if ($('.datepicker').length) {
        $('.datepicker').each(function () {
            $(this).datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });
        });
    }
    */

    // ------------------------------------------------------- //
    //   Lightbox in galleries
    // ------------------------------------------------------ //

    $(".slider-gallery").each(function () {
        // the containers for all your galleries
        $(this).magnificPopup({
            delegate: "a", // the selector for gallery item
            type: "image",
            gallery: {
                enabled: true,
                tCounter: "", // markup of counter
            },
        });
    });

    $(".gallery").each(function () {
        // the containers for all your galleries
        $(this).magnificPopup({
            delegate: "a", // the selector for gallery item
            type: "image",
            gallery: {
                enabled: true,
            },
        });
    });

    // =====================================================
    //     Reset input
    // =====================================================

    $(".input-expand .form-control").on("focus", function () {
        $(this).parents(".input-expand").addClass("focus");
    });
    $(".input-expand .form-control").on("blur", function () {
        setTimeout(function () {
            $(".input-expand .form-control").parents(".input-expand").removeClass("focus");
        }, 333);
    });


    // =====================================================
    //      Detail slider
    // =====================================================

    var detailSlider = new Swiper(".detail-slider", {
        slidesPerView: 3,
        spaceBetween: 0,
        centeredSlides: true,
        loop: true,
        breakpoints: {
            991: {
                slidesPerView: 4,
            },
            565: {
                slidesPerView: 3,
            },
        },

        // If we need pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },

        // Navigation arrows
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    var bookingDetailSlider = new Swiper(".booking-detail-slider", {
        slidesPerView: 2,
        spaceBetween: 0,
        centeredSlides: true,
        loop: true,
        // If we need pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },

        // Navigation arrows
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    // =====================================================
    //      Init swipers automatically
    // =====================================================

    $(".swiper-init").each(function () {
        var slider = $(this),
            configuration = JSON.parse($(this).attr("data-swiper"));

        new Swiper(slider, configuration);
    });

    var homeSlider = new Swiper(".home-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        loop: true,
        speed: 1500,
        parallax: true,
        /*
        autoplay: {
            delay: 5000,
        },
        */
        // If we need pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        // Navigation arrows
        navigation: {
            nextEl: "#homeNext",
            prevEl: "#homePrev",
        },
    });

    // =====================================================
    //      Items slider
    // =====================================================

    var itemsSlider = new Swiper(".items-slider", {
        slidesPerView: 4,
        spaceBetween: 20,
        loop: true,
        roundLengths: true,
        breakpoints: {
            1200: {
                slidesPerView: 3,
            },
            991: {
                slidesPerView: 2,
            },
            565: {
                slidesPerView: 1,
            },
        },

        // If we need pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
    });

    var itemsSliderFull = new Swiper(".items-slider-full", {
        slidesPerView: 6,
        spaceBetween: 20,
        loop: true,
        roundLengths: true,
        breakpoints: {
            1600: {
                slidesPerView: 5,
            },
            1400: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 3,
            },
            991: {
                slidesPerView: 2,
            },
            565: {
                slidesPerView: 1,
            },
        },

        // If we need pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
    });

    var guidesSlider = new Swiper(".guides-slider", {
        slidesPerView: 5,
        spaceBetween: 15,
        loop: true,
        roundLengths: true,
        breakpoints: {
            1200: {
                slidesPerView: 4,
            },
            991: {
                slidesPerView: 3,
            },
            768: {
                slidesPerView: 2,
            },
            400: {
                slidesPerView: 1,
            },
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
    });

    var brandsSlider = new Swiper(".brands-slider", {
        slidesPerView: 6,
        spaceBetween: 15,
        loop: true,
        roundLengths: true,
        breakpoints: {
            1200: {
                slidesPerView: 4,
            },
            991: {
                slidesPerView: 3,
            },
            768: {
                slidesPerView: 2,
            },
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
    });

    var swiper = new Swiper(".hero-slider", {
        effect: "fade",
        speed: 2000,
        allowTouchMove: false,
        autoplay: {
            delay: 10000,
        },
    });

    var instagramSlider = new Swiper(".instagram-slider", {
        slidesPerView: 16,
        breakpoints: {
            1900: {
                slidesPerView: 12,
            },
            1500: {
                slidesPerView: 10,
            },
            1200: {
                slidesPerView: 8,
            },
            991: {
                slidesPerView: 6,
            },
            768: {
                slidesPerView: 5,
            },
            576: {
                slidesPerView: 4,
            },
        },
    });

    var testimonialsSlider = new Swiper(".testimonials-slider", {
        slidesPerView: 2,
        spaceBetween: 20,
        loop: true,
        roundLengths: true,
        breakpoints: {
            1200: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            991: {
                slidesPerView: 2,
                spaceBetween: 0,
            },
            565: {
                slidesPerView: 1,
            },
        },

        // If we need pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
    });

    // ------------------------------------------------------- //
    //   Increase/Decrease product amount
    // ------------------------------------------------------ //
    $(".btn-items-decrease").on("click", function () {
        var input = $(this).siblings(".input-items");
        if (parseInt(input.val(), 10) >= 1) {
            if (input.hasClass("input-items-greaterthan")) {
                input.val(parseInt(input.val(), 10) - 1 + "+");
            } else {
                input.val(parseInt(input.val(), 10) - 1);
            }
        }
    });

    $(".btn-items-increase").on("click", function () {
        var input = $(this).siblings(".input-items");
        if (input.hasClass("input-items-greaterthan")) {
            input.val(parseInt(input.val(), 10) + 1 + "+");
        } else {
            input.val(parseInt(input.val(), 10) + 1);
        }
    });

    // ------------------------------------------------------- //
    //   Scroll to top button
    // ------------------------------------------------------ //

    $(window).on("scroll", function () {
        if ($(window).scrollTop() >= 1500) {
            $("#scrollTop").fadeIn();
        } else {
            $("#scrollTop").fadeOut();
        }
    });

    $("#scrollTop").on("click", function () {
        $("html, body").animate(
            {
                scrollTop: 0,
            },
            1000
        );
    });

    // ------------------------------------------------------- //
    // Adding fade effect to dropdowns
    // ------------------------------------------------------ //

    $.fn.slideDropdownUp = function () {
        $(this).fadeIn().css("transform", "translateY(0)");
        return this;
    };
    $.fn.slideDropdownDown = function (movementAnimation) {
        if (movementAnimation) {
            $(this).fadeOut().css("transform", "translateY(30px)");
        } else {
            $(this).hide().css("transform", "translateY(30px)");
        }
        return this;
    };

    $(".navbar .dropdown").on("show.bs.dropdown", function (e) {
        $(this).find(".dropdown-menu").first().slideDropdownUp();
    });
    $(".navbar .dropdown").on("hide.bs.dropdown", function (e) {
        var movementAnimation = true;

        // if on mobile or navigation to another page
        if ($(window).width() < 992 || (e.clickEvent && e.clickEvent.target.tagName.toLowerCase() === "a")) {
            movementAnimation = false;
        }

        $(this).find(".dropdown-menu").first().slideDropdownDown(movementAnimation);
    });

    // ------------------------------------------------------- //
    //    Collapse button control (used for more/less filters)
    // ------------------------------------------------------ //

    $(".btn-collapse").each(function () {
        var button = $(this),
            collapseId = button.attr("data-bs-target");

        if ($(collapseId).length) {
            var collapseElement = $(collapseId);

            $(collapseElement).on("hide.bs.collapse", function () {
                button.text(button.attr("data-collapsed-text"));
            });

            $(collapseElement).on("show.bs.collapse", function () {
                button.text(button.attr("data-expanded-text"));
            });
        }
    });

    // ------------------------------------------------------- //
    //   Bootstrap tooltips
    // ------------------------------------------------------- //

    $('[data-bs-toggle="tooltip"]').tooltip();

    // ------------------------------------------------------- //
    //   Smooth Scroll
    // ------------------------------------------------------- //

    var smoothScroll = new SmoothScroll("a[data-smooth-scroll]", {
        offset: 80,
    });

    // ------------------------------------------------------- //
    //   Object Fit Images
    // ------------------------------------------------------- //

    // objectFitImages();


    // ------------------------------------------------------- //
    //   Newsletter subscribe
    // ------------------------------------------------------- //

    $(document).on("click", "#newsletter-subscribe", function () {
        var $thisButton = $(this);
        if ($thisButton.attr("data-action-done") == "1") {
            $thisButton.unbind("click");
            return false;
        }

        if (!isEmailValid($("#newsletter-email").val())) {
            showStackBarTop('error', '', 'Make sure to enter a valid email address', {}, 'javascript');
            return false;
        }

        $.ajax({
            type: "POST",
            url: $thisButton.data('target'),
            data: {email: $("#newsletter-email").val()},
            beforeSend: function () {
                $thisButton.attr("data-action-done", "1");
                $thisButton.html("<i class='fas fa-spinner fa-spin'></i>");
            },
            success: function (response) {
                if (response.hasOwnProperty('success')) {
                    $thisButton.html('<i class="fas fa-envelope-open-text"></i>');
                    $thisButton.attr("title", response.success).tooltip("_fixTitle");
                    showStackBarTop('success', '', response.success);
                } else if (response.hasOwnProperty('error')) {
                    $thisButton.html('<i class="fas fa-exclamation-circle"></i>');
                    $thisButton.attr("title", response.error).tooltip("_fixTitle");
                    showStackBarTop('error', '', response.error);
                } else {
                    $thisButton.html('<i class="fas fa-exclamation-circle"></i>');
                    $thisButton.attr("title", 'An error has occured', {}, 'javascript').tooltip("_fixTitle");
                    showStackBarTop('error', '', 'An error has occured', {}, 'javascript');
                }
            }
        });
    });

    // ------------------------------------------------------- //
    //   Cookie Bar
    // ------------------------------------------------------- //

    if (typeof $("body").data('cookie-bar-page-link') !== 'undefined') {
        $.cookieBar('addTranslation', 'fr', {
            message: 'Nous utilisons des cookies pour fournir nos services. En utilisant ce site Web, vous acceptez cela.',
            acceptText: 'D\'accord',
            infoText: 'Plus d\'information'
        });
        $.cookieBar('addTranslation', 'en', {
            message: 'We use cookies to provide our services. By using this website you agree to this.',
            acceptText: 'Agree',
            infoText: 'More information'
        });
        $.cookieBar({
            style: 'bottom',
            infoLink: $("body").data('cookie-bar-page-link'),
            language: $("html").attr("lang")
        });
    }
});
