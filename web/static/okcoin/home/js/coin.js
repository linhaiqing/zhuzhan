$(function(){

    $.get("/ajax/get_market_list_data", {},
        function(data){



            if(data.status == 1){
                var tot = '';
                var json = data.url;
                //console.log(data);
                $.each(json, function (i, obj) {

                    //console.log(obj);
                    //console.log(i);
                        tot +='<div id="up_li" class="up_li">';
                        tot +='<a href="market.do?symbol=0">'+'<span>'+obj['title']+'</span>';
                        tot +='<span class="number">'+'最新成交价:'+ '<strong>'+obj['new_price']+'</strong>'+'</span>'+' &nbsp;&nbsp;'+ '<span>'+'24小时成交量:'+ '<strong>'+obj['volume']+'</strong>'+'</span>';
                        tot +='</a>' +'<br>'+ '<a href="/market.do?symbol=1">'+'<span>'+obj['title']+'</span>';
                        tot +='<span class="number">'+'最新成交价:'+ '<strong>'+obj['new_price']+'</strong>'+'</span>'+ '&nbsp;&nbsp;'+ '<span class="">'+ '24小时成交量:'+ '<strong>'+obj['volume']+'</strong>'+'</span>';
                        tot +='</a>'
                        tot +='</div>'

                });

            }

            $("#marqueebox").html(tot);
        }, "json");


});




