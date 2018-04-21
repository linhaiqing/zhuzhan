function autoScroll(obj) {
    //console.log(11111)
    $(obj).find("#marqueebox").animate({

        marginTop: "-50px"
    }, 500, function () {
        $(this).css({marginTop: "0px"}).find(".up_li:first").appendTo("#marqueebox");
    })



}
$(function () {
    setInterval('autoScroll("#up_zzjs")', 3000);

    $(".links_li a img").mouseover(function () {
        $(this).addClass('hover');
    });
    $(".links_li a img").mouseout(function () {
        $(this).removeClass('hover');
    });
})
