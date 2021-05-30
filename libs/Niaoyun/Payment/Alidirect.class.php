<?php
namespace Niaoyun\Payment;
use Niaoyun\Payment\PayInterface\Ipay;

class Alidirect implements Ipay
{
	private $payment_method;
	
	private $config = [
	'alidirect_pid' => '',
	'alidirect_key' => '',
	'alidirect_uid' => '',
	];
	
	private $configAdmin =[
	'alidirect_pid' => ['name'=>'商户ID','show'=>true],
	'alidirect_key' => ['name'=>'商户密钥','show'=>true],
	'alidirect_uid' => ['name'=>'支付宝PID','show'=>true],
	];
	
	public function __construct()
	{
		foreach($this->configAdmin as $k=>$v)
		{
			$this->config[$k] = C('recharge')[substr(__CLASS__,strrpos(__CLASS__,'\\')+1).$k];
		}
	}
	
	public function getConfigAdmin()
	{
		return $this->configAdmin;
	}
	
	public function getPayName()
	{
		return '支付宝免签约接口';
	}
	
	public function pay($parameters)
	{
	    $alidirect_uid = $this->config["alidirect_uid"];
	    $payAmount = $parameters["total_fee"];
	    $out_trade_no = $parameters["out_trade_no"];
	    $order = M("users_recharge")->where("orderNo = '$out_trade_no'")->find();
	    if(!$order)
	    {
	        
	    }
	    else
	    {
	        $title = $order["id"];
	    }
	    
	    $html_text = "../../alidirectV2/?p=".base64_encode($title."||".$payAmount."||".$alidirect_uid);
	    $data = ['code'=>200,'html_text'=>$html_text,'msg'=>'success'];
	    return $data;
	}
	
	public function wapPay($parameters)
	{
	    $alidirect_uid = $this->config["alidirect_uid"];
	    $payAmount = $parameters["total_fee"];
	    $out_trade_no = $parameters["out_trade_no"];
	    $order = M("users_recharge")->where("orderNo = '$out_trade_no'")->find();
	    if(!$order)
	    {
	        
	    }
	    else
	    {
	        $title = $order["id"];
	    }
	    
	    $html_text = "../../alidirectV2/?p=".base64_encode($title."||".$payAmount."||".$alidirect_uid);
	    $data = ['code'=>200,'type'=>'redirect','url'=>$html_text,'msg'=>'success'];
	    return $data;
	}
	
	public function getConfig()
	{
	    return $this->config;
	}
}