<?php header("Content-type: text/html; charset=utf-8");
$p = isset($_REQUEST["p"])?$_REQUEST["p"]:"";
$p = base64_decode($p);
$para = explode('||',$p);
if(count($para)!=3)
{
    exit("非法请求");
}
$optEmail = $para[2];
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
    <title>支付宝扫码支付</title>
    <link href="images/wechat_pay.css" rel="stylesheet" media="screen">
	<script src="images/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="./images/qrcode.js"></script>
<style type="text/css">
#weixin-tip{position: fixed; left:0; top:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80); width: 100%; height:100%; z-index: 100;}
#weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}
#weixin-tip img{width:100%;}
</style>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico_log ico-1"></span>
    </h1>

    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount" id="money">￥<?php echo number_format($payAmount,2)?></div>
        <div class="qrcode-img-wrapper" data-role="qrPayImgWrapper">
            <div data-role="qrPayImg" class="qrcode-img-area">
                <div class="ui-loading qrcode-loading" data-role="qrPayImgLoading" style="display: none;">加载中</div>
                <div style="position: relative;display: inline-block;" id="show_qrcode">
				<?php if(!is_numeric($optEmail)||strlen($optEmail)!=16){ ?>
				<img id='show_qrcode' alt="加载中..." src="zfb.jpg" width="210" height="210" style="display: block;">
				<?php }?>
                </div>
            </div>


        </div>
		<?php if(is_numeric($optEmail)&&strlen($optEmail)==16){ ?>
		<div style="font-size:16px; color:#FF0000; font-weight:bold;"><a href="https://ds.alipay.com/?scheme=alipays%3a%2f%2fplatformapi%2fstartapp%3fappId%3d20000123%26actionType%3dscan%26biz_data%3d%7b%22s%22%3a%22money%22%2c%22u%22%3a%22<?php echo $optEmail;?>%22%2c%22a%22%3a%22<?php echo $payAmount;?>%22%2c%22m%22%3a%22<?php echo $title;?>%22%7d" target="_blank">点击立即支付</a></div>
		<?php }else{ ?>
		<div style="font-size:16px; color:#FF0000; font-weight:bold;">付款时请输入付款备注<?php echo $title;?></div>
		<?php }?>
        <div class="tip">
            <div class="ico-scan"></div>
            <div class="tip-text">
                <p>请使用支付宝扫一扫</p>
                <p>扫描二维码完成支付</p>
            </div>
        </div>


    </div>
    <div class="foot">
        <div class="inner">
            <p>手机用户可保存上方二维码到手机中</p>
            <p>在支付宝扫一扫中选择“相册”即可</p>
        </div>
    </div>
    <div id="copyright">Copyright &copy; 2005-<?php echo date("Y");?> <a href="http://www.zfbjk.com" target="_blank">Zfbjk</a> All Rights Reserved</div>
</div>

<script>
<?php if(is_numeric($optEmail)&&strlen($optEmail)==16){ ?>
var qrcode = new QRCode('show_qrcode', {
  text: 'alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={"s": "money","u": "<?php echo $optEmail;?>","a": "<?php echo $payAmount;?>","m":"<?php echo $title;?>"}',
  width: 210,
  height: 210,
  colorDark : '#000000',
  colorLight : '#ffffff',
  correctLevel : QRCode.CorrectLevel.H
});
<?php }?>
		var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight;
		var ua = navigator.userAgent.toLowerCase();
		if (ua.match(/MicroMessenger/i) == "micromessenger") {
			var div = document.createElement('div');
			div.id = 'weixin-tip';
		    var ua = navigator.userAgent.toLowerCase();
			if (ua.match(/android/i) == "android")
			{
				div.innerHTML = '<p><img src="./images/android_browser_tips.png" alt="请使用手机自带浏览器打开"/></p>';
			}
			else
			{
				div.innerHTML = '<p><img src="./images/ios_browser_tips.png" alt="请使用手机自带浏览器打开"/></p>';
			}
			document.body.appendChild(div);
		}
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
