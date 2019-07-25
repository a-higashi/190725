$("input[name=ok]").click(function(e) {  
    e.preventDefault();
    $(this).attr("disabled", "disabled"); 
    $('form').submit();
});
