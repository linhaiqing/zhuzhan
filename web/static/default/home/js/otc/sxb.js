function downloadSxb(b,d,a){if(b==""){Wrong("用户ID不能为空!",{callback:"Close()"});return}a=!a?0:a;d=!d?false:true;if(d){createSxb(b,a)}else{var c=zb.vipDomain+"/u/sxb/validate?userId="+b+"&callback=?";$.getJSON(c,function(e){if(e.isSuc){createSxb(b,a)}else{Wrong(e.des,{callback:"Close()"})}})}}function createSxb(b,a){var c=zb.vipDomain+"/u/sxb/createSxb?userId="+b+"&reqType="+a+"&callback=?";$.getJSON(c,function(d){if(d.isSuc){var e=d.datas;if(e.busy=="true"){Info("获取十星保过于频繁，请稍后再试!",{callback:"Close()"});return}if(isEmpty(e.sid)&&!isEmpty(e.uid)){Wrong("内部错误，请联系客服!",{callback:"Close()"});return}if(e.existOldSxb=="true"){Ask2({Msg:"您当前资产信息跟上次获取十星保的凭证一致，系统将为您返回上次的十星保凭证，是否继续?",callback:"showSxb("+e.uid+",'"+e.sid+"',"+e.reqType+")"})}else{showSxb(e.uid,e.sid,e.reqType)}}else{Wrong(d.des,{callback:"Close()"})}})}function showSxb(b,c,a){Iframe({Url:zb.vipDomain+"/u/sxb/showSxb?userId="+b+"&sxbId="+c+"&reqType="+a,zoomSpeedIn:200,zoomSpeedOut:200,Width:480,Height:730,isShowIframeTitle:false,IsCloseOnModal:true})}function isEmpty(a){if(a==null||a=="null"||a==""){return true}else{return false}};