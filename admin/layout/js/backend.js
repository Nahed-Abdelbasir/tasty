/* console $ */
$(function (){
  "use strict";
    
    
   // trigger the selectBoxIt
    
    $("select").selectBoxIt({
        autoWidth: false
    });
    
    
    // confirm message
    
    $(".confirm").click(function () {
        return confirm("Are you sure ? ");
    });
    
    
    // hide placeholder on focus
    
    $("[placeholder]").focus(function () {
        $(this).attr("data-value" , $(this).attr("placeholder"));
        $(this).attr("placeholder" , "");
        
    }).blur(function (){
        $(this).attr("placeholder" , $(this).attr("data-value"));
    });
    
    
    // add astrick on required field
    
    $("input").each(function () {
        
        if($(this).attr("required") === "required" ){
            $(this).after("<span class='astrick'>*</span>");
        }
        
    });
    
    
    // adjust elements height to be the same
    
    var theMaxHeight = 0;
    
    $(".items .info").each(function () {
        if($(this).height() > theMaxHeight){
            theMaxHeight = $(this).height();
        }
        
    });
    
    $(".info").height(theMaxHeight);
    
});