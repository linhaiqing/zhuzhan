/**
 * Created by Administrator on 2017/11/21.
 */
$(".bk-c2c-nav a").on("click", function () {
    var idx=$(this).index();
    $(this).addClass("active").siblings("a").removeClass("active");
    $(".bk-c2c-contlist > div").eq(idx).addClass("active").siblings("div").removeClass("active");
});
layui.use('form', function(){
    var form = layui.form;
    //监听提交
    //form.on('submit(formDemo)', function(data){
    //    layer.msg(JSON.stringify(data.field));
    //    return false;
    //});
});
function form_up(){
    var type = $('.layui-input-block input[name="type"]').val();
    var coin = $('#choose').val();
    var price= $('#coin-price').val();
    var num  = $('#num').val();
}
