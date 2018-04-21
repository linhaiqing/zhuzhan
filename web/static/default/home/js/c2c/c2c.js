/**
 * Created by Administrator on 2017/11/21.
 */


function topPrice(){
    $.get('/c2c/top',{coin:$quName},function(data){
        if(data.status == 1){
            $('#new_price').html(data.info.new_price);
            $('#max_price').html(data.info.max_price);
            $('#min_price').html(data.info.min_price);
            $('#sell').html(data.info.sell);
            $('#buy').html(data.info.buy);
            $('#volume').html(formatCount(data.info.num));
            if(data.info.range>0){
                $('#change').html('+'+data.info.range+'%').addClass('red');
            }else{
                $('#change').html(data.info.range+'%').addClass('green');
            }

            setTimeout('topPrice()',50000);
        }
    },'json')
}

//保留2位
function formatCount(count) {
    var countokuu = (count / 100000000).toFixed(3)
    var countwan = (count / 10000).toFixed(3);

    if (count > 100000000)
        return countokuu.substring(0, countokuu.lastIndexOf('.') + 3) + '{#lang("亿")}'
    if (count > 10000)
        return countwan.substring(0, countwan.lastIndexOf('.') + 3) + '{#lang("万")}'
    else
        return count
}

// 计算总额
function getNum(){
    var price = $('#coin-price').val();
    var count = $('#count').val();

    if(price && count){

        var strPrice = price.toString();
        var strCount = count.toString();

        var mum   = price*count;

        if(strPrice.indexOf('.') > 0 && strCount.indexOf('.')<0){ //单价浮点型 数量整型
            var np = price.toString().split(".")[1].length;
            mum    = (parseInt(price*Math.pow(10,np))*(count*Math.pow(10,np)))/Math.pow(10,np*2);

        }

        if(strPrice.indexOf('.') < 0 && strCount.indexOf('.') > 0){ //单价整型 数量浮点型
            var nc = count.toString().split(".")[1].length;
            mum    = ((price*Math.pow(10,nc))*parseInt(count*Math.pow(10,nc)))/Math.pow(10,nc*2);
        }

        if(strPrice.indexOf('.') > 0 && strCount.indexOf('.') > 0){ // 单价数量 皆为浮点型
            var np    = price.toString().split(".")[1].length;
            var nc    = count.toString().split(".")[1].length;
            mum    = (parseInt(price*Math.pow(10,np))*parseInt(count*Math.pow(10,nc)))/Math.pow(10,nc+np);
        }
        if(mum){
            $('#num').val(mum);
        }

    }
}

//买入 卖出 提交
function trade_up(a){
    var id  = $(a).attr('data-id');
    var type= $(a).attr('data-type');
    var psw = $('#t-psw').val();
    if(type == 2){
        var alipay = $('#t-alipay').val();
        var weChat = $('#t-weChat').val();
        var bank = $('#t-bank-choose').val();
        var tel = $('#t-tel').val();
        if(!alipay && !weChat && !bank){
            $('#t-alipay').focus();
            layer.msg('请至少输入一种付款方式',{icon:5,anim:6});
            return false;
        }

    }
        if(psw == '' || psw == null){
            $('#t-psw').focus();
            layer.msg('请输入密码',{icon:5,anim:6});
            return false;
        }
    $.post('/c2c/tradeUp',{
        id:id,
        type:type,
        alipay:alipay,
        weChat:weChat,
        bank:bank,
        tel:tel,
        paypassword:psw
    }, function(data){
            if(data.status == 1){
                layer.msg(data.info, {icon:6});
                setTimeout(function(){
                    location.reload();
                },1000)
            }else{
                layer.msg(data.info, {icon:5,anim:6});
            }
        },'json')
}

//挂单提交
function form_up(){
    var type = $('.layui-input-block input[name="type"]:checked').val();
    var coin = $('#coin-choose').val();
    var price= $('#coin-price').val();
    var num  = $('#num').val();
    var alipay=$('#alipay').val();
    var wechat=$('#wechat').val();
    var bank = $('#bank-choose option:selected').val();
    var psw  = $('#form-psw').val();
   if(coin == '' || coin == null){
      $('.coin-choose .layui-select-title .layui-unselect').focus();
       layer.msg('请选择币种',{icon:5,anim: 6});
       return false;
   }
    if(price == '' || price == null){
        $('#coin-price').focus();
        layer.msg('请输入单价',{icon:5});
        return false;
    }
    if(num == '' || num == null){
        $('#num').focus();
        layer.msg('请输入总价',{icon:5});
        return false;
    }
    if(!alipay && !wechat && !bank){
        $('#alipay').focus();
        layer.msg('请至少输入一种付款方式',{icon:5});
        return false;
    }

    if(psw == '' || psw == null){
        $('#form-psw').focus();
        layer.msg('请输入交易密码',{icon:5});
        return false;
    }
    $.post('/c2c/applyOrder',{
        type:type,
        coin:coin,
        price:price,
        mum:num,
        alipay:alipay,
        weChat:wechat,
        bank:bank,
        paypassword:psw
    },function(data){
        if(data.status == 1){
            layer.msg(data.info, {icon:6});
            setTimeout(function(){
                location.reload();
            },1000)
        }else{
            layer.msg(data.info, {icon:5})
        }
    },'json')



}

function checkInfo(a){
    var info = $(a).data('info');
    layer.alert(info,{title:'交易信息'});
}
// 记录表 状态操作
function statusTo(id,status){
    layer.prompt({title: '输入交易密码，并确认', formType: 1}, function(pass){
      if(pass){
          $.post('/c2c/statusTo',{
              id:id,
              status:status,
              paypassword:pass
          },function(data){
              if(data.status == 1){
                  layer.msg(data.info,{icon:6});
                  setTimeout(function(){
                      location.reload();
                  },1000)
              }else{
                  layer.msg(data.info,{icon:5,anim:6})
              }
          },'json')
      }
    });
}

function cancel(id,guadan){
    var url = guadan ? '/c2c/homeCancel' : '/c2c/cancel';
    layer.prompt({title: '输入交易密码，并确认', formType: 1}, function(pass){
        if(pass){
            $.post(url,{
                id:id,
                paypassword:pass
            },function(data){
                if(data.status == 1){
                    layer.msg(data.info,{icon:6});
                    setTimeout(function(){
                        location.reload();
                    },1000)
                }else{
                    layer.msg(data.info,{icon:5,anim:6})
                }
            },'json')
        }
    });
}