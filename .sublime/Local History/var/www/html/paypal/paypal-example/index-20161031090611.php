
    <?php
	
    	$paypal_redirect = 'https://www.sandbox.paypal.com/cgi-bin/webscr/?';
    	echo $paypal_redirect;

		$paypal_args = array(
			'cmd' => '_xclick',
			'amount' =>10,
			'business' => 'smagic39@gmail.com',
			'item_name' => 'ORder test',
			'email' => 'rms616@gmail.com',
			'no_shipping' => '1',
			'no_note' => '1',
			'currency_code' => 'USD',
			'item_number' => 12312312,
			'charset' => 'UTF-8',
			'custom' => 1,
			'rm' => '2',
			'return' => 'http://localhost/paypal-example/success.html',
			'notify_url' => 'http://localhost/paypal-example/listen.html'
		);
		var_dump($paypal_args);
		//var_dump(http_build_query($paypal_args)); exit;
		$paypal_redirect .= http_build_query($paypal_args);
		echo $paypal_redirect;
		header("Location: $paypal_redirect");
  	  ?>
 