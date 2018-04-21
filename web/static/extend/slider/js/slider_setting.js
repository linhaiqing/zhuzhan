<!--上传新图片-->
$(document).ready(function () {
        //响应文件添加成功事件
        $("#inputfile").change(function () {
            //创建FormData对象
            var data = new FormData();
            //为FormData对象添加数据
            $.each($('#inputfile')[0].files, function (i, file) {
                data.append('upload_file' + i, file);
            });
            //发送数据
            $.ajax({
                url: '/ajax/upload/?action=uploadimage',
                type: 'POST',
                data: data,
                dataType: "json",
                cache: false,
                contentType: false,		//不可缺参数
                processData: false,		//不可缺参数
                success: function (data) {
                    console.log(data);
                    console.log(data.state);
                    if (data.state == "SUCCESS") {
                        $("#slider-img-list").append('<li><div data-id="'+data.url+'"><img src="'+data.url+'"><button type="text" onclick="$(this).parent().remove();">删除</button><input type="text" value="" placeholder="输入图片链接"> <input type="text" value="" placeholder="输入图片说明"> </div> </li>');
                    } else {
                        layer.msg(data.state);
                    }
                },
                error: function () {
                    alert('上传出错');
                    $(".loading").hide();	//加载失败移除加载图片
                }
            });

        });
    });



function save_slider(){
    saveOrder();

    var data = {
        data_idname:data_idname,
        data_slider_height:data_slider_height,
        data_slider_arrset:data_slider_arrset,
        data_slider_navigator:data_slider_navigator,
        data_slider_action:data_slider_action,
        data_slider_action_data:data_slider_action_data,
        data_img:data_img
    };
    $.post("/ajax/sliderSave",data,function(ret){
        console.log(ret);
        layer.msg(ret.info);
    },"json");

}

//tab切换
$(function () {
    window.onload = function () {
        var $li = $('#slider-tab > li');
        var $ul = $('#slider-content > ul');

        $li.mouseover(function () {
            var $this = $(this);
            var $t = $this.index();
            $li.removeClass();
            $this.addClass('current');
            $ul.css('display', 'none');
            $ul.eq($t).css('display', 'block');
        })
    }
});

//轮播图 基础设置
$(document).ready(function () {
    //动画模式选择
    $('.slider_action').change(function () {
        var transitionName = $(this).children('option:selected').val();
        data_slider_action      = transitionName;
        data_slider_action_data = JSON.stringify([_SlideshowTransitionC[transitionName]]);
        slider_obj.$SetSlideshowTransitions([_SlideshowTransitionC[transitionName]]);
    });

    //左右轮播模式切换
    $('.slider_arrset').change(function () {
        var id = $(this).children('option:selected').val();
        data_slider_arrset = id;
        if (id == 999) {
            $("#slider-body-arrow-l").hide();
            $("#slider-body-arrow-r").hide();
            return;
        } else {
            $("#slider-body-arrow-l").show();
            $("#slider-body-arrow-r").show();
        }
        $("#slider-body-arrow-l").removeClass();
        $("#slider-body-arrow-l").addClass("slider-body-arrowleft-" + id);

        $("#slider-body-arrow-r").removeClass();
        $("#slider-body-arrow-r").addClass("slider-body-arrowright-" + id);
        layer.tips('切换成功', '#slider-body-arrow-l', {
            tipsMore: true,
            tips: [2, '#0FA6D8'] //还可配置颜色
        });
        layer.tips('切换成功', '#slider-body-arrow-r', {
            tipsMore: true,
            tips: [4, '#0FA6D8'] //还可配置颜色
        });
    });

    //中部切换按钮样式切换
    $('.slider_navigator').change(function () {
        var id = $(this).children('option:selected').val();
        data_slider_navigator = id;
        if (id == 999) {
            $("#slider-body-navigator").hide();
            return;
        } else {
            $("#slider-body-navigator").show();
        }
        $("#slider-body-navigator").removeClass();
        $("#slider-body-navigator").addClass("sbn-" + id);
        layer.tips('切换成功', '#slider-body-navigator', {
            tips: [1, '#0FA6D8'] //还可配置颜色
        });
    });

    //轮播图高度切换
    $('.slider_height').change(function () {
        var value = $(this).children('option:selected').val();
        if (value == 0) {
            value = window.prompt("输入自定义高度", "")
        }
        data_slider_height = value;
        $("#slider-show").html(value);
        $(".slider").css("height", value);
        $(".slider-body div").css("height", value);
        $(".slider-body img").css("height", value);
        $("#slider-body-arrow-l").css("top", (value - $("#slider-body-arrow-l").height()) / 2);
        $("#slider-body-arrow-r").css("top", (value - $("#slider-body-arrow-r").height()) / 2);

    });

});

//保存图片拖拽顺序
function saveOrder() {
    var data = $("#slider-img-list div").map(function () {
        return $(this).attr("data-id") + "#" + $(this).find(':input:eq(1)').val() + "#" + $(this).find(':input:eq(2)').val();
    }).get();
    data_img = data.join("|");
    if(!data_img){
        layer.msg('请先上传图片');
        return false;
    }
};

//开启面板弹框
function chose_slider() {
    layer.open({
        type: 1,
        title: "轮播图设置",
        skin: 'layui-layer-demo', //样式类名
        closeBtn: 1, //不显示关闭按钮
        shift: 2,
        shade: 0,
        area: ['1000px', '500px'],
        shadeClose: true, //开启遮罩关闭
        content: $("#slider-set").html(),
        success: function () {
            $("ul:first").dragsort();
            $("#slider-img-list").dragsort({
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
            });
        }
    });
}

$(document).ready(function () {
    $('#slide-shadow-bg').height($(window).height());
    $('#slide-shadow-bg').width($(window).width());
    $('#slide-shadow-bg').css('left',0);
    $('#slide-shadow-bg').css('top',0);
    $('#slide-shadow-bg').show();
    $("#slider").css("z-index",999);
    $('html,body').animate({scrollTop: $("#slider").offset().top - ($(window).height() - $("#slider").height())/2}, 800);
})

chose_slider();

//<!--轮播图开始初始化-->
slider_obj_init = function () {
    if (!!window.ActiveXObject || "ActiveXObject" in window){
        var width = $("#slider").parent().width();
        $("#slider").css("width",width);
        $("#slider" + " .slider-body").css("width",width);
        $("#slider" + " .slider-body").css("height",$(".slider").height());
    }

    var jssor_1_options = {
        $AutoPlay: true,
        //幻灯片持续时间
        $SlideDuration: 800,
        //幻灯宽松
        $SlideEasing: $Jease$.$OutQuint,
        //箭头导航选项
        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$
        },
        //子导航选项
        $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$
        },
        $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
            $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
            $Transitions: [],            //[Required] An array of slideshow transitions to play slideshow
            $TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
            $ShowLink: true                                 //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
        }
    };

    slider_obj = new $JssorSlider$("slider", jssor_1_options);
    slider_obj.$SetSlideshowTransitions(JSON.parse(data_slider_action_data));
};
slider_obj_init();

//<!--轮播图特效字典-->
var _SlideshowTransitionC = {};
var _SlideshowTransitionCodes = {};
var _SlideshowTransitions = [];

//----------- Fade Effects --------------
{
    _SlideshowTransitionC["Fade"] = {$Duration: 1200, $Opacity: 2};
    _SlideshowTransitionCodes["Fade"] = "{$Duration:1200,$Opacity:2}";

    _SlideshowTransitionC["Fade in L"] = {
        $Duration: 1200,
        x: 0.3,
        $During: {$Left: [0.3, 0.7]},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in L"] = "{$Duration:1200,x:0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in R"] = {
        $Duration: 1200,
        x: -0.3,
        $During: {$Left: [0.3, 0.7]},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in R"] = "{$Duration:1200,x:-0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in T"] = {
        $Duration: 1200,
        y: 0.3,
        $During: {$Top: [0.3, 0.7]},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in T"] = "{$Duration:1200,y:0.3,$During:{$Top:[0.3,0.7]},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in B"] = {
        $Duration: 1200,
        y: -0.3,
        $During: {$Top: [0.3, 0.7]},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in B"] = "{$Duration:1200,y:-0.3,$During:{$Top:[0.3,0.7]},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in LR"] = {
        $Duration: 1200,
        x: 0.3,
        $Cols: 2,
        $During: {$Left: [0.3, 0.7]},
        $ChessMode: {$Column: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in LR"] = "{$Duration:1200,x:0.3,$Cols:2,$During:{$Left:[0.3,0.7]},$ChessMode:{$Column:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in LR Chess"] = {
        $Duration: 1200,
        y: 0.3,
        $Cols: 2,
        $During: {$Top: [0.3, 0.7]},
        $ChessMode: {$Column: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in LR Chess"] = "{$Duration:1200,y:0.3,$Cols:2,$During:{$Top:[0.3,0.7]},$ChessMode:{$Column:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in TB"] = {
        $Duration: 1200,
        y: 0.3,
        $Rows: 2,
        $During: {$Top: [0.3, 0.7]},
        $ChessMode: {$Row: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in TB"] = "{$Duration:1200,y:0.3,$Rows:2,$During:{$Top:[0.3,0.7]},$ChessMode:{$Row:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in TB Chess"] = {
        $Duration: 1200,
        x: 0.3,
        $Rows: 2,
        $During: {$Left: [0.3, 0.7]},
        $ChessMode: {$Row: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in TB Chess"] = "{$Duration:1200,x:0.3,$Rows:2,$During:{$Left:[0.3,0.7]},$ChessMode:{$Row:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade in Corners"] = {
        $Duration: 1200,
        x: 0.3,
        y: 0.3,
        $Cols: 2,
        $Rows: 2,
        $During: {$Left: [0.3, 0.7], $Top: [0.3, 0.7]},
        $ChessMode: {$Column: 3, $Row: 12},
        $Easing: {
            $Left: $JssorEasing$.$EaseInCubic,
            $Top: $JssorEasing$.$EaseInCubic,
            $Opacity: $JssorEasing$.$EaseLinear
        },
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade in Corners"] = "{$Duration:1200,x:0.3,y:0.3,$Cols:2,$Rows:2,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$ChessMode:{$Column:3,$Row:12},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out L"] = {
        $Duration: 1200,
        x: 0.3,
        $SlideOut: true,
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out L"] = "{$Duration:1200,x:0.3,$SlideOut:true,$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out R"] = {
        $Duration: 1200,
        x: -0.3,
        $SlideOut: true,
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out R"] = "{$Duration:1200,x:-0.3,$SlideOut:true,$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out T"] = {
        $Duration: 1200,
        y: 0.3,
        $SlideOut: true,
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out T"] = "{$Duration:1200,y:0.3,$SlideOut:true,$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out B"] = {
        $Duration: 1200,
        y: -0.3,
        $SlideOut: true,
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out B"] = "{$Duration:1200,y:-0.3,$SlideOut:true,$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out LR"] = {
        $Duration: 1200,
        x: 0.3,
        $Cols: 2,
        $SlideOut: true,
        $ChessMode: {$Column: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out LR"] = "{$Duration:1200,x:0.3,$Cols:2,$SlideOut:true,$ChessMode:{$Column:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out LR Chess"] = {
        $Duration: 1200,
        y: -0.3,
        $Cols: 2,
        $SlideOut: true,
        $ChessMode: {$Column: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out LR Chess"] = "{$Duration:1200,y:-0.3,$Cols:2,$SlideOut:true,$ChessMode:{$Column:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out TB"] = {
        $Duration: 1200,
        y: 0.3,
        $Rows: 2,
        $SlideOut: true,
        $ChessMode: {$Row: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out TB"] = "{$Duration:1200,y:0.3,$Rows:2,$SlideOut:true,$ChessMode:{$Row:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out TB Chess"] = {
        $Duration: 1200,
        x: -0.3,
        $Rows: 2,
        $SlideOut: true,
        $ChessMode: {$Row: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out TB Chess"] = "{$Duration:1200,x:-0.3,$Rows:2,$SlideOut:true,$ChessMode:{$Row:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade out Corners"] = {
        $Duration: 1200,
        x: 0.3,
        y: 0.3,
        $Cols: 2,
        $Rows: 2,
        $During: {$Left: [0.3, 0.7], $Top: [0.3, 0.7]},
        $SlideOut: true,
        $ChessMode: {$Column: 3, $Row: 12},
        $Easing: {
            $Left: $JssorEasing$.$EaseInCubic,
            $Top: $JssorEasing$.$EaseInCubic,
            $Opacity: $JssorEasing$.$EaseLinear
        },
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade out Corners"] = "{$Duration:1200,x:0.3,y:0.3,$Cols:2,$Rows:2,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$SlideOut:true,$ChessMode:{$Column:3,$Row:12},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade Fly in L"] = {
        $Duration: 1200,
        x: 0.3,
        $During: {$Left: [0.3, 0.7]},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in L"] = "{$Duration:1200,x:0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in R"] = {
        $Duration: 1200,
        x: -0.3,
        $During: {$Left: [0.3, 0.7]},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in R"] = "{$Duration:1200,x:-0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in T"] = {
        $Duration: 1200,
        y: 0.3,
        $During: {$Top: [0.3, 0.7]},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in T"] = "{$Duration:1200,y:0.3,$During:{$Top:[0.3,0.7]},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in B"] = {
        $Duration: 1200,
        y: -0.3,
        $During: {$Top: [0.3, 0.7]},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in B"] = "{$Duration:1200,y:-0.3,$During:{$Top:[0.3,0.7]},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in LR"] = {
        $Duration: 1200,
        x: 0.3,
        $Cols: 2,
        $During: {$Left: [0.3, 0.7]},
        $ChessMode: {$Column: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in LR"] = "{$Duration:1200,x:0.3,$Cols:2,$During:{$Left:[0.3,0.7]},$ChessMode:{$Column:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in LR Chess"] = {
        $Duration: 1200,
        y: 0.3,
        $Cols: 2,
        $During: {$Top: [0.3, 0.7]},
        $ChessMode: {$Column: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in LR Chess"] = "{$Duration:1200,y:0.3,$Cols:2,$During:{$Top:[0.3,0.7]},$ChessMode:{$Column:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in TB"] = {
        $Duration: 1200,
        y: 0.3,
        $Rows: 2,
        $During: {$Top: [0.3, 0.7]},
        $ChessMode: {$Row: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in TB"] = "{$Duration:1200,y:0.3,$Rows:2,$During:{$Top:[0.3,0.7]},$ChessMode:{$Row:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in TB Chess"] = {
        $Duration: 1200,
        x: 0.3,
        $Rows: 2,
        $During: {$Left: [0.3, 0.7]},
        $ChessMode: {$Row: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in TB Chess"] = "{$Duration:1200,x:0.3,$Rows:2,$During:{$Left:[0.3,0.7]},$ChessMode:{$Row:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly in Corners"] = {
        $Duration: 1200,
        x: 0.3,
        y: 0.3,
        $Cols: 2,
        $Rows: 2,
        $During: {$Left: [0.3, 0.7], $Top: [0.3, 0.7]},
        $ChessMode: {$Column: 3, $Row: 12},
        $Easing: {
            $Left: $JssorEasing$.$EaseInCubic,
            $Top: $JssorEasing$.$EaseInCubic,
            $Opacity: $JssorEasing$.$EaseLinear
        },
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly in Corners"] = "{$Duration:1200,x:0.3,y:0.3,$Cols:2,$Rows:2,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$ChessMode:{$Column:3,$Row:12},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out L"] = {
        $Duration: 1200,
        x: 0.3,
        $SlideOut: true,
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out L"] = "{$Duration:1200,x:0.3,$SlideOut:true,$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out R"] = {
        $Duration: 1200,
        x: -0.3,
        $SlideOut: true,
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out R"] = "{$Duration:1200,x:-0.3,$SlideOut:true,$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out T"] = {
        $Duration: 1200,
        y: 0.3,
        $SlideOut: true,
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out T"] = "{$Duration:1200,y:0.3,$SlideOut:true,$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out B"] = {
        $Duration: 1200,
        y: -0.3,
        $SlideOut: true,
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out B"] = "{$Duration:1200,y:-0.3,$SlideOut:true,$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out LR"] = {
        $Duration: 1200,
        x: 0.3,
        $Cols: 2,
        $SlideOut: true,
        $ChessMode: {$Column: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out LR"] = "{$Duration:1200,x:0.3,$Cols:2,$SlideOut:true,$ChessMode:{$Column:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out LR Chess"] = {
        $Duration: 1200,
        y: 0.3,
        $Cols: 2,
        $SlideOut: true,
        $ChessMode: {$Column: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out LR Chess"] = "{$Duration:1200,y:0.3,$Cols:2,$SlideOut:true,$ChessMode:{$Column:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out TB"] = {
        $Duration: 1200,
        y: 0.3,
        $Rows: 2,
        $SlideOut: true,
        $ChessMode: {$Row: 12},
        $Easing: {$Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out TB"] = "{$Duration:1200,y:0.3,$Rows:2,$SlideOut:true,$ChessMode:{$Row:12},$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out TB Chess"] = {
        $Duration: 1200,
        x: 0.3,
        $Rows: 2,
        $SlideOut: true,
        $ChessMode: {$Row: 3},
        $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out TB Chess"] = "{$Duration:1200,x:0.3,$Rows:2,$SlideOut:true,$ChessMode:{$Row:3},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Fly out Corners"] = {
        $Duration: 1200,
        x: 0.3,
        y: 0.3,
        $Cols: 2,
        $Rows: 2,
        $During: {$Left: [0.3, 0.7], $Top: [0.3, 0.7]},
        $SlideOut: true,
        $ChessMode: {$Column: 3, $Row: 12},
        $Easing: {
            $Left: $JssorEasing$.$EaseInCubic,
            $Top: $JssorEasing$.$EaseInCubic,
            $Opacity: $JssorEasing$.$EaseLinear
        },
        $Opacity: 2,
        $Outside: true
    };
    _SlideshowTransitionCodes["Fade Fly out Corners"] = "{$Duration:1200,x:0.3,y:0.3,$Cols:2,$Rows:2,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$SlideOut:true,$ChessMode:{$Column:3,$Row:12},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Outside:true}";

    _SlideshowTransitionC["Fade Clip in H"] = {
        $Duration: 1200,
        $Delay: 20,
        $Clip: 3,
        $Assembly: 260,
        $Easing: {$Clip: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade Clip in H"] = "{$Duration:1200,$Delay:20,$Clip:3,$Assembly:260,$Easing:{$Clip:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade Clip in V"] = {
        $Duration: 1200,
        $Delay: 20,
        $Clip: 12,
        $Assembly: 260,
        $Easing: {$Clip: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade Clip in V"] = "{$Duration:1200,$Delay:20,$Clip:12,$Assembly:260,$Easing:{$Clip:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade Clip out H"] = {
        $Duration: 1200,
        $Delay: 20,
        $Clip: 3,
        $SlideOut: true,
        $Assembly: 260,
        $Easing: {$Clip: $JssorEasing$.$EaseOutCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade Clip out H"] = "{$Duration:1200,$Delay:20,$Clip:3,$SlideOut:true,$Assembly:260,$Easing:{$Clip:$JssorEasing$.$EaseOutCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade Clip out V"] = {
        $Duration: 1200,
        $Delay: 20,
        $Clip: 12,
        $SlideOut: true,
        $Assembly: 260,
        $Easing: {$Clip: $JssorEasing$.$EaseOutCubic, $Opacity: $JssorEasing$.$EaseLinear},
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade Clip out V"] = "{$Duration:1200,$Delay:20,$Clip:12,$SlideOut:true,$Assembly:260,$Easing:{$Clip:$JssorEasing$.$EaseOutCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}";

    _SlideshowTransitionC["Fade Stairs"] = {
        $Duration: 800,
        $Delay: 30,
        $Cols: 8,
        $Rows: 4,
        $Formation: $JssorSlideshowFormations$.$FormationStraightStairs,
        $Assembly: 2050,
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade Stairs"] = "{$Duration:800,$Delay:30,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:2050,$Opacity:2}";

    _SlideshowTransitionC["Fade Random"] = {$Duration: 1000, $Delay: 80, $Cols: 8, $Rows: 4, $Opacity: 2};
    _SlideshowTransitionCodes["Fade Random"] = "{$Duration:1000,$Delay:80,$Cols:8,$Rows:4,$Opacity:2}";

    _SlideshowTransitionC["Fade Swirl"] = {
        $Duration: 800,
        $Delay: 30,
        $Cols: 8,
        $Rows: 4,
        $Formation: $JssorSlideshowFormations$.$FormationSwirl,
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade Swirl"] = "{$Duration:800,$Delay:30,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationSwirl,$Opacity:2}";

    _SlideshowTransitionC["Fade ZigZag"] = {
        $Duration: 800,
        $Delay: 30,
        $Cols: 8,
        $Rows: 4,
        $Formation: $JssorSlideshowFormations$.$FormationZigZag,
        $Assembly: 260,
        $Opacity: 2
    };
    _SlideshowTransitionCodes["Fade ZigZag"] = "{$Duration:800,$Delay:30,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationZigZag,$Assembly:260,$Opacity:2}";
}
var server_data_slider_action = data_slider_action;
$Jssor$.$Each(_SlideshowTransitionC, function (slideshowTransition, name) {
    if(server_data_slider_action == name){
        $(".slider_action").append("<option value='" + name + "' selected>" + name + " 模式</option>");
        slider_obj.$SetSlideshowTransitions([slideshowTransition]);
    }else{
        $(".slider_action").append("<option value='" + name + "'>" + name + " 模式</option>");
    }
    console.log(name);
    _SlideshowTransitions.push(slideshowTransition);
});
