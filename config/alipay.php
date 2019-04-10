<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016092500595603",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEA6B7cqed6oxTsjrlnK7EK23adW1MDyJqcl0piLzY3keX9yNr1HPN0Oni7Ws0lb/Xw2mM5FQwicDH1na5MTJI2fEwCRZeyEmr+1Mbq6/M1yVNL3yPIgSfS+TdLhF1sH4o08RC7vOZPC7TVJ3C/b/OSiUrPjK34FlB0wG4ytxpTBQJrBQ2Lbdd1AxGXQppd7NP+rjd7Q5k8LXK5Omd+0qZ07Mt1Dk+oIj+iclHj0gY1bFXe2I82l/yG4eS8eRBFm9RQ3tAZHTOhfECTm83QYT53ijP4BUkbSR+3qZ92lHhls8/1bAkdN7xJhnHFrFfd/XCneSAbMHlrgv19X9pMcedgxwIDAQABAoIBAE2efFWIZVcBWTVExD40/pdyq53tPZeoM/LXWcvFyoFMMjgnF83tjxE6bBQqA3nT+Q1eHqluHFn+Ks7miPcV8YhQ9nWFC3PjOYG3Xrk14+eFphpg4dxmj8LsfaEuiEhns9pkqRK6xPX6K0j8B9gM0BR+4rZ9OjdkAhaTAvx9ngNPL4LH/w0nZznbiKLpOXTPXjDRjnw9MVUuNVpaj48MS533wUD053HhciBpzioOnaLWmRxfOg9fzQgdGpyX0Pm+GYD3mZhpdqQhPdip8FbfHDLUAO/alwkzYhyP65bRTsrs4+ky6paAad28l4T4BUdhuzArsjOlA0b0HTE4Zso/TrkCgYEA/lvxjFJOv9A/GgGsYaVT3igX4UK/3DezRevtIEQlFVTvTzrSBZWT0lnSdRt3pIa0aBjRh1A8s5/SrhSUAasgdMPzKqVw2Hg58LAa7qClm/O5J2LlEK3y0gk1tbTnXA6PBqKZkDBBAkY6heL6qQU6pFl0740O0ypjTolI/LGUlDsCgYEA6Z4xYyVpR7dr9HtByMN0dQyOm3dEqad2vRGSWj00L509o5Gy3+E/F9VadCuDvae6TKA4Z8tO1kjbHBM5UuLS9vi6fOZxP2yToeHUlTqj32KQl49p9BwrU/h9wBf9SHFJT3BC3yUwBZZfiSDOMvqhAg/UctJHsRODaf1F7kL32OUCgYEAov8uafpwpnB/j13VJnm4pdtRejO5GRjElCEcwpmIngPgmwgZgCSbJqgSuqLQZ6k+wJpu4uRjEcRwUMe1FOChmtHa6NA94SoiDv/7MkHV/nsPgpLYMXkW6sumFHwJ6q8Vrm3SNFkHv7pbVGLguCE2tAzzZ3MgNeeq3bASCoSNrvMCgYAPiOtz0lsL39CX81JOjWDDH9Cj2eyA85fbvr1irss3l1cv91JTvOC7Lv7S1snt5KdozQazMDvP2Axp84VOU7L/pU6RW6mQNCIlE6VVPw8CncVLrse26eCftth126KGHLJpS+fGeuyUkolLzxinHy9xuafA8ua9ibSlaagqzd05WQKBgQDqCoesnisLe0L/eLzWCgcwP8za5U94noRoSAesNwTPRnpF4ky+MxORsc7NvCXwAoYLmAtzUYVfjPuC4E9hqLCjwgrHWzmZD7p7cGhm+B3QGgZyvjZiaITrk9c9txJWg03kRiyrTEYVJY89tctl5FuPtddusx6a4EQqmc4tQ3Leaw==",
		
		//异步通知地址
		'notify_url' => "http://www.wxshop.com/notify",
		
		//同步跳转
		'return_url' => "http://www.wxshop.com/return",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAo+KAQs+zJe4tcV4jBroQpTKAotV0mSfkGLjguhgcV+lwns6wjmUkkOYOsBT+w4qv0ZVvnBFQ41Cin+GWC/Gn8FRobMuZQzXDjjtThu5Y3rPzH1vji4RqQagGj9sPnMcoenmJPlQ5sWGswgOitSAH+tlWxJSPXYjGT+JvOjjlQAZbS9S/0RGnt80FCRNnL7Gufirwwcn7vobmffKM4DUZ681zVlwFjlM0RWM17h56nNDKMGEmTrZvPjxqaPmw5nPt+tOrmhaCFFnp+mOicbEtvKbnoFF2CSGq2NU5YTWyh6QvHsA6LrqcC4sbwEWEVI9sMtewxyx+5l7Djynd0S+xXwIDAQAB",
		
	    //支付宝格式
        'mode'=>'dev'
);
return $config;
