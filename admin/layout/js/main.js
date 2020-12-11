$(function () {
    'use strict';
    //Hide placeholder
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    //Add Asterisk On Required Field
    $('input').each(function () {
        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    //convert Password Faild To Text Field On Hover
    var passField = $('.password');
    $('.show-pass').hover(function () {
        passField.attr('type', 'text')
    }, function () {
        passField.attr('type', 'password')
    });

    //confirmation Message On Button
    $('.confirm').click(function () {
        return confirm('Are You Sure?');
    })
});


