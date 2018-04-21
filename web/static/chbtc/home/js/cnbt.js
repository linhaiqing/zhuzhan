/**
 * Created by Administrator on 2016/12/21.
 */

$(function () {

    $(".links_img").mouseover(function () {
        $(this).addClass('hover');
    });
    $(".links_img").mouseout(function () {
        $(this).removeClass('hover');
    })
    setInterval('price()', 1000);


})


var last_val = {
    "new_price": 0,
    "max_price": 0,
    "min_price": 0,
    "sell_price": 0,
    "shou_price": 0
};




function price() {


    $.get("/ajax/get_market_data", {}, function (data) {
      //  console.log(data)
        if (data.status == 1) {
            //  console.log(data.url)
            $('.item h4 span').text(data.url['title']);

            $('.ppp b').each(function(i){

                if(i==0){
                    var new_price =parseFloat(data.url['new_price']).toFixed(2)
                    $(this).text(new_price);
                }else if(i==1){
                    var max_price =parseFloat(data.url['max_price']).toFixed(2)
                    $(this).text('￥'+max_price);

                }else if(i==2){
                    var min_price =parseFloat(data.url['min_price']).toFixed(2)
                    $(this).text('￥'+min_price);
                }else if(i==3){
                    var volume =parseFloat(data.url['volume']).toFixed(2)
                    $(this).text(volume);

                }
            })

            $('.price').each(function (i) {

                if (i == 0) {
                    var num = (parseFloat(data.url['new_price']) + Math.random(0, 1)).toFixed(2);


                    if (last_val.new_price) {

                        if (num > last_val.new_price) {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.new_price = num;
                        } else if (num < last_val.new_price) {

                            $(this).text('￥' + num + '↓') .css({'color': 'green'});
                            last_val.new_price = num;

                        } else {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.new_price = num;
                        }

                    } else {
                        last_val.new_price = num;
                        $(this).text('￥' + num);

                    }

                } else if (i == 1) {
                    var num = (parseFloat(data.url['max_price']) + Math.random(0, 1)).toFixed(2);
                    if (last_val.max_price) {

                        if (num > last_val.max_price) {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.max_price = num;
                        } else if (num < last_val.max_price) {

                            $(this).text('￥' + num + '↓') .css({'color': 'green'});
                            last_val.max_price = num;

                        } else {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.max_price = num;
                        }

                    } else {
                        last_val.max_price = num;
                        $(this).text('￥' + num);

                    }


                } else if (i == 2) {
                    var num = (parseFloat(data.url['min_price']) + Math.random(0, 1)).toFixed(2);
                    if (last_val.min_price) {

                        if (num > last_val.min_price) {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.min_price = num;
                        } else if (num < last_val.min_price) {

                            $(this).text('￥' + num + '↓') .css({'color': 'green'});
                            last_val.min_price = num;

                        } else {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.min_price = num;
                        }

                    } else {
                        last_val.min_price = num;
                        $(this).text('￥' + num);

                    }


                } else if (i == 3) {
                    var num = (parseFloat(data.url['sell_price']) + Math.random(0, 1)).toFixed(2);
                    if (last_val.sell_price) {

                        if (num > last_val.sell_price) {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.sell_price = num;
                        } else if (num < last_val.sell_price) {

                            $(this).text('￥' + num + '↓') .css({'color': 'green'});
                            last_val.sell_price = num;

                        } else {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.sell_price = num;
                        }

                    } else {
                        last_val.sell_price = num;
                        $(this).text('￥' + num);

                    }
                } else if (i == 4) {
                    var num = (parseFloat(data.url['shou_price']) + Math.random(0, 1)).toFixed(2);
                    if (last_val.shou_price) {

                        if (num > last_val.shou_price) {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.shou_price = num;
                        } else if (num < last_val.shou_price) {

                            $(this).text('￥' + num + '↓') .css({'color': 'green'});
                            last_val.shou_price = num;

                        } else {

                            $(this).text('￥' + num+'↑') .css({'color': 'red'});
                            last_val.shou_price = num;
                        }

                    } else {
                        last_val.shou_price = num;
                        $(this).text('￥' + num);

                    }
                }


            })

        }
    }, 'json');
}




