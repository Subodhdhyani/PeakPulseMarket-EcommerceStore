/*
//In this directly normal hide function not working that's why settimeout used
$(document).ready(function () {
    $(".spinner-container").show();

    if (document.readyState === "complete") {
        setTimeout(function () {  
            $(".spinner-container").hide();
        }, 100); 
    }
});

$(window).on("load", function () {
    setTimeout(function () {  
        $(".spinner-container").hide();
    }, 100); 
});
*/

$(document).ready(function () {
    $(".spinner-container").show();

    if (document.readyState === "complete") {
        $(".spinner-container").fadeOut("slow", function () {
            $(this).css("display", "none");
        });
    }
});

$(window).on("load", function () {
    $(".spinner-container").fadeOut("slow", function () {
        $(this).css("display", "none");
    });
});
/*
// Fallback timeout in case onload doesn't fire
setTimeout(function () {
    $(".spinner-container").fadeOut("slow", function () {
        $(this).css("display", "none");
    });
}, 3000);
*/
