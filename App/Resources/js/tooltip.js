/**
 * Jquery file
 * This come is based off of the code from the "Create a Tooltip
 * with JQuery with Chris Converse at "
 * http://www.lynda.com/jQuery-tutorials/Adding-jQuery-document-ready-mouse-events/105376/111426-4.html"
 *
 * This code has been modified by Shawn Legge on 7/10/2015.
 */

$(document).ready(function() {

    $('#tooltip_container').hide();

    // Tooltip
    $('.tool-tip').mouseover(function(e) {

        // data type - text
        if ( $(this).attr('data-tip-type') == 'text' ){
            $('#tooltip_container').html( $(this).attr('data-tip-source') );
        }

        // data type - html
        if ( $(this).attr('data-tip-type') == 'html' ){
            var elementToGet = '#' + $(this).attr('data-tip-source');
            var newHTML = $(elementToGet).html();
            $('#tooltip_container').html( newHTML );
        }
        $('#tooltip_container').css({'display':'block','opacity':0}).animate({opacity:1},250);
    }).mousemove(function(e) {
        var tooltipWidth = $('#tooltip_container').outerWidth();
        var tooltipHeight = $('#tooltip_container').outerHeight();

        // width detection
        var pageWidth = $('body').width();
        if ( e.pageX > pageWidth / 2 ){
            $('#tooltip_container').css('left',( e.pageX - tooltipWidth + 20 ) + 'px');
        }else{
            $('#tooltip_container').css('left',( e.pageX - 20 ) + 'px');
        }

        // height detection
        if ( e.pageY > 100 ){
            $('#tooltip_container').css('top',( e.pageY - (tooltipHeight+20) ) + 'px' );
        }else{
            $('#tooltip_container').css('top',( e.pageY + 20 ) + 'px' );
        }

        /* debug */ $('.bodywidth').html( pageWidth );
        /* debug */ $('.xpos').html(e.pageX);
        /* debug */ $('.ypos').html(e.pageY);

    }).mouseout(function() {
        $('#tooltip_container').animate({opacity:0},250, function(){
            $('#tooltip_container').css('display','none')
        });
    });

});