<?php
namespace ApiNotify\Controller;

class AlidirectNotifyController extends PaymentNotifyController
{
	private $payConfig = [];
	
	function _initialize()
	{
		parent::_initialize();
		$className = substr(__CLASS__,strrpos(__CLASS__,'\\')+1,-16);
		$class = new \ReflectionClass("\Niaoyun\Payment\\$className");
		$obj = $class->newInstance();
		$this->payConfig = $obj->getConfig();
		$this->failedReturnMsg = "Fail";
		$this->successReturnMsg = "Success";
	}
	
	public function checkSign($parameters)
	{
	    if(!$_POST)
	    {
	        exit("本页面是《支付宝免签约即时到账辅助》的通知网址，仅限用于填写到支付宝免签约即时到账辅助的通知网址设置项中与辅助软件通信，而不是直接访问<br /><br />官方网站：<a href='http://www.zfbjk.com' target='_blank'>http://www.zfbjk.com</a><br />客服QQ：40386277<br />E-mail：support@zfbjk.com");
	    }
	    $alidirect_pid = $this->payConfig["alidirect_pid"];
	    $alidirect_key = $this->payConfig["alidirect_key"];
	    $tradeNo = $parameters["tradeNo"];
	    $Money = $parameters["Money"];
	    $title = $parameters["title"];
	    $memo = $parameters["memo"];
	    $alipay_account = $parameters["alipay_account"];
	    $Gateway = $parameters["Gateway"];
	    $Sign = $parameters["Sign"];
	    $orderid = $parameters["orderid"];
	    
	    if($orderid&&is_numeric($orderid))
	    {
	        $order = M("users_recharge")->where("id = ".$orderid)->find();
	        if($order&&$order["status"]==1)
	        {
	            exit("success");
	        }
	        exit;
	    }

		if(strtoupper(md5($alidirect_pid . $alidirect_key . $tradeNo . $Money .  iconv("utf-8","gb2312",$title) .  iconv("utf-8","gb2312",$memo))) == strtoupper($Sign))
		{
		    if(!is_numeric($title))
		    {
		        exit("FAIL");
		    }
		    else
		    {
		        return true;
		    }
		}
		else
		{
			exit("Fail");
		}
	}
	
	public function getPayStatus($parameters)
	{
	    $order = M("users_recharge")->where("id = ".$parameters["title"])->find();
	    if(!$order)
	    {
	        exit("IncorrectOrder");
	    }
	    elseif($parameters['Money']!=$order["money"])
	    {
	        exit("fail");
	    }
	    else
	    {
	        $this->orderNo = $order["orderno"];
	        $this->trade_no = $parameters["tradeNo"];
	        $this->total_fee = $order["money"];
	        $this->payAccount = $order['payaccount'];
	        return true;
	    }
	}
}