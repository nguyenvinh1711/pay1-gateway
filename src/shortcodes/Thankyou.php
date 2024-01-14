<?php
/**
 * 
 * 
 * @author vinhsmile
 * @since  1.0.0
 */

namespace pay1\Shortcodes;

class Thankyou
{
	public function __construct()
	{
		add_shortcode('pay1_thankyou', array($this, 'callback'));
	}


	public function callback($atts)
	{
        $content = "<div style=\"margin-left: 100px;width: 250px;float: left\">";
        $content.= "<div style=\"color: red;font-size: 20px\">".$_GET["message"]."</div>";
        $content.= "<div >Mã giao dich:&nbsp<b>" . $_GET["pay1_TxnRef"] . "</b></div>";
        $content.= "<div >Số tiền: &nbsp<b>" . $_GET["amount"] . "</b></div>";
        $content.= "<div >Ngân hàng: &nbsp<b>" . $_GET["pay1_BankCode"] . "</b></div>";
        $content.= "<div><a style=\"color: green\" href=" . get_site_url() . ">Về trang chủ</a></div>";
        $content.= "</div>";
        echo $content;
	}

}