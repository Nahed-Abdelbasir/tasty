/* console $ */
$(document).ready(function () {
    

  "use strict";
    
     //use typed plugin for p(.typed)
    
    $(function(){
    var typed = new Typed(".typed", {
        strings:["we are an independant design and development agancy"],
        startDelay:1000,
        typeSpeed:70,
        loopCount:1,
        showCursor:false
     });
    
    });
    
    //use niceScroll plugin for body
    
        $("body").niceScroll({
        cursorcolor: "#ef5050", // change cursor color in hex
        cursorwidth: "12px", // cursor width in pixel (you can also write "5px")
        cursorborder: "1px solid #fff", // css definition for cursor border
        cursorborderradius: "5px", // border radius in pixel for cursor
        scrollspeed: 60, // scrolling speed
        mousescrollstep: 40, // scrolling speed with mouse wheel (pixel)
        zindex: 9999999999999, // change z-index for scrollbar div
    });
    
    // header height
    
    $(function(){
       $("header").height($(window).height());
    
    });
    
    
    // confirm message
    
    $(function(){
    $(".confirm").click(function () {
        return confirm("Are you sure ? ");
    });
    });
    
    
    // hide placeholder on focus
    
    $("[placeholder]").focus(function () {
        $(this).attr("data-value" , $(this).attr("placeholder"));
        $(this).attr("placeholder" , "");
        
    }).blur(function (){
        $(this).attr("placeholder" , $(this).attr("data-value"));
    });
    
    
    // add astrick on required field
    
    $(".form-control").each(function () {
        
        if($(this).attr("required") == "required" ){
            $(this).after("<span class='astrick'>*</span>");
        }
        
    });
    
    
    // toggle between login and sign up
    
    $(".user-form span").click(function () {
        $(".user-form span").removeClass("select");  
        $(this).addClass("select");
        $(".user-form div").removeClass("active");
        $($(this).attr("data-class")).addClass("active");
    });
    
    
    // toggle between products
    
    $(".products ul a li").click(function () {
        $(".products ul a li").removeClass("choosed");  
        $(this).addClass("choosed");
    });
    
    

    
});

