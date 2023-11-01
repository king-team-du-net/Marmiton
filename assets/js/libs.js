/*
 * App Marmiton Recipe | Bootstrap 5
 * Copyright 2020-2023 rdequidt
 * Theme libs scripts
*/

/** Libs */


/** Mode Modules */
import 'summernote/dist/summernote-bs5.min.js';
import 'fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js';



// Declares utility functions
(function () {
    'use strict';

    // Checks if an email address is valid
    function isEmailValid(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    // Bootstrap Touchspin
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

    // Initializes Font Awesome picker
    if ($('.icon-picker').length) {
        $('.icon-picker').iconpicker({
            animation: false,
            inputSearch: true
        });
    }

    // Initializes wysiwyg editor
    if ($('.wysiwyg').length) {
        $('.wysiwyg').summernote({
            height: 500,
        });
    }

    // Tags input
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

    /*
        // Datetimepickers
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
})();
