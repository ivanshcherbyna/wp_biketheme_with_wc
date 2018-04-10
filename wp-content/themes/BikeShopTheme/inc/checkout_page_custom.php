<?php
function add_payment_privat(){
if ( is_cart() || is_checkout() ) {
    echo "This is the cart, or checkout page!";die;
    echo'<form method="POST" accept-charset="utf-8" action="https://www.liqpay.ua/api/3/checkout">
	<input type="hidden" name="data" value="eyJ2ZXJzaW9uIjozLCJhY3Rpb24iOiJwYXkiLCJwdWJsaWNfa2V5IjoiaTU4ODI0NjkwMzAyIiwiYW1vdW50IjoiNSIsImN1cnJlbmN5IjoiVUFIIiwiZGVzY3JpcHRpb24iOiLQnNC+0Lkg0YLQvtCy0LDRgCIsInR5cGUiOiJidXkiLCJsYW5ndWFnZSI6InJ1In0=" />
	<input type="hidden" name="signature" value="WvJLMwHzKxqrERE1Bj9yFcQqi8o=" />
	<button style="border: none !important; display:inline-block !important;text-align: center !important;padding: 7px 20px !important;
		color: #fff !important; font-size:16px !important; font-weight: 600 !important; font-family:OpenSans, sans-serif; cursor: pointer !important; border-radius: 2px !important;
		background: rgb(122,183,43) !important;"onmouseover="this.style.opacity=\'0.5\';" onmouseout="this.style.opacity=\'1\';">
		<img src="https://static.liqpay.ua/buttons/logo-small.png" name="btn_text"
			style="margin-right: 7px !important; vertical-align: middle !important;"/>
		<span style="vertical-align:middle; !important">Оплатить 5 UAH</span>
	</button>
</form>';
    }

}
