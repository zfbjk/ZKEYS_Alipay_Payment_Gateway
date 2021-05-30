<?php header("Content-type: text/html; charset=utf-8");
$p = isset($_REQUEST["p"])?$_REQUEST["p"]:"";
$p = base64_decode($p);
$para = explode('||',$p);
if(count($para)!=2)
{
    exit("非法请求");
}
$payAmount = $para[1];
$title = $para[0];
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="format-detection" content="telephone=no,email=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>微信扫码支付</title>
    <link href="images/wechat_pay.css" rel="stylesheet" media="screen">
	<script src="images/jquery-1.10.2.min.js"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico_log ico-3"></span>
    </h1>

    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount" id="money">￥<?php echo number_format($payAmount,2)?></div>
        <div class="qrcode-img-wrapper" data-role="qrPayImgWrapper">
            <div data-role="qrPayImg" class="qrcode-img-area">
                <div class="ui-loading qrcode-loading" data-role="qrPayImgLoading" style="display: none;">加载中</div>
                <div style="position: relative;display: inline-block;">
                    <img id='show_qrcode' alt="加载中..." src="wx.jpg" width="210" height="210" style="display: block;">
                </div>
            </div>


        </div>
		<div style="font-size:16px; color:#FF0000; font-weight:bold;">付款时请输入付款备注<?php echo $title;?></div>

        <div class="tip">
            <div class="ico-scan"></div>
            <div class="tip-text">
                <p>请使用微信扫一扫</p>
                <p>扫描二维码完成支付</p>
            </div>
        </div>


    </div>
    <div class="foot">
        <div class="inner">
            <p>手机用户可保存上方二维码到手机中</p>
            <p>在微信扫一扫中选择“相册”即可</p>
        </div>
    </div>
    <div id="copyright">Copyright &copy; 2005-<?php echo date("Y");?> <a href="http://www.zfbjk.com" target="_blank">Zfbjk</a> All Rights Reserved</div>
</div>
<script>
$(document).ready(function() {
		timer = setInterval(function(){
		  $.ajax({
			url:"../ApiNotify/AlidirectNotify/notify",
			type: "post",
			data: {act:"check",orderid:"<?php echo $title;?>"},
			success: function(d){
				if(d == "success" ){
					clearInterval(timer);
					alert("支付成功！");
					location.href = '../user/payment/record.html';
				}
			}
		  });
		},3000);
	})
</script>
</body>
</html>
