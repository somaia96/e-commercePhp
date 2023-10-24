$(function () {
    'use strict';
    // dashboard
    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        if($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }else{
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
    });
    $("select").selectBoxIt({
        autoWidth:false
    });
    //hide placeholder
        $('[placeholder]').focus(function () {
            $(this).attr('data-text', $(this).attr('placeholder'));
            $(this).attr('placeholder', '');
        }).blur(function () {
            $(this).attr('placeholder', $(this).attr('data-text'));
        });
        //  add require to all field which require
        $('input').each(function(){
            if($(this).attr('required') === "required") {
                $(this).after('<span class="aster">*</span>');
            }
        });
        $('.confirm').click(function(){
            return confirm('Are You Sure?');
        });
        //category view option
        $('.cat h3').click(function(){
            $(this).next('.full-view').fadeToggle(50);
        });
        $('.option span').click(function(){
            $(this).addClass('active').siblings('span').removeClass('active');
            if($(this).data('view') === 'full') {
                $('.cat .full-view').fadeIn(50);
            } else {
                $('.cat .full-view').fadeOut(50);
            }
        });
});