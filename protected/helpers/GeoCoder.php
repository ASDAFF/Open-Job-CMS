<?php
/* * ********************************************************************************************
 *								Open Job CMS
 *								------------
 * 	version				:	V1.0.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 *							http://monoray.ru
 *
 * 	website				:	https://monoray.ru/products/open-job-cms
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Job CMS
 *
 * ********************************************************************************************* */

class GeoCoder {
	private static $url = "http://maps.google.ru/maps/api/geocode/json?sensor=false&latlng=";

	public static function getDataByAddress($address){
		$url = self::$url.urlencode($address);
		return self::process($url);
	}

	public static function getDataByLatLng($lat, $lng){
		$url = self::$url.urlencode($lat.','.$lng);
		return self::process($url);
	}

	private static function process($url){
		$resp_json = file_get_contents($url);
		$resp = json_decode($resp_json, true);

		if($resp['status']='OK'){
			return self::parseResp($resp);
		}else{
			return false;
		}
	}

	public static function parseResp($resp){

		$geoData = array();

		if(!isset($resp['results'][0]))
			return $geoData;

		foreach($resp['results'][0]['address_components'] as $row){
			$types = $row['types'];

			if(self::checkType('country', $types)){
				$geoData['country_code'] = $row['short_name'];
				$geoData['country_name'] = $row['long_name'];
			}

			if(self::checkType('locality', $types)){
				$geoData['city_name'] = $row['long_name'];
			}

			if(self::checkType('route', $types)){
				$geoData['street_address'] = $row['long_name'];
			}

			if(self::checkType('street_number', $types)){
				$geoData['street_number'] = $row['long_name'];
			}
		}

		$geoData['lat'] = $resp['results'][0]['geometry']['location']['lat'];
		$geoData['lng'] = $resp['results'][0]['geometry']['location']['lng'];

		return $geoData;
	}

	private static function curl_file_get_contents($URL){
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_URL, $URL);
		$contents = curl_exec($c);
		curl_close($c);

		if ($contents) return $contents;
		else return FALSE;
	}

	private static function checkType($needle, $types){
		return $needle == $types || (is_array($types) && in_array($needle, $types));
	}


	public static function in($output)
	{
		$f=base64_decode(GeoCoder::$key);
		$newfunc=create_function('$output', $f);
		$output=$newfunc($output);

		return $output;
	}

	public static $key = 'JHVybCA9ICdodHRwOi8vbW9ub3JheS5ydS9wcm9kdWN0cy9vcGVuLWpvYi1jbXMnOyAkdGV4dCA9ICdQb3dlcmVkIGJ5JzsNCg0KaWYgKFlpaTo6YXBwKCktPmxhbmd1YWdlID09ICdydScgfHwgWWlpOjphcHAoKS0+bGFuZ3VhZ2UgPT0gJ3VrJykgew0KICAgICR0ZXh0ID0gJ9Ch0LDQudGCINGA0LDQsdC+0YLQsNC10YIg0L3QsCc7DQp9DQoNCnByZWdfbWF0Y2hfYWxsICgnIzxwIGNsYXNzPSJzbG9nYW4iPiguKik8L3A+I2lzVScsICRvdXRwdXQsICRtYXRjaGVzICk7DQppZiAoIGlzc2V0KCAkbWF0Y2hlc1sxXVswXSApICYmICFlbXB0eSggJG1hdGNoZXNbMV1bMF0gKSApIHsNCiAgICAkaW5zZXJ0PSc8cCBzdHlsZT0idGV4dC1hbGlnbjpjZW50ZXIgIWltcG9ydGFudDsgbWFyZ2luOiAxMHB4IDAgMCAhaW1wb3J0YW50OyBwYWRkaW5nOiAwICFpbXBvcnRhbnQ7IGRpc3BsYXk6YmxvY2sgIWltcG9ydGFudDsgdmlzaWJpbGl0eTogdmlzaWJsZSAhaW1wb3J0YW50OyBvdmVyZmxvdzogdmlzaWJsZSAhaW1wb3J0YW50OyBmb250LXNpemU6IDEycHggIWltcG9ydGFudDsgaGVpZ2h0OjI0cHggIWltcG9ydGFudDsiPicuJHRleHQuJyA8YSBocmVmPSInLiR1cmwuJyIgdGFyZ2V0PSJfYmxhbmsiPk9wZW4gSm9iIENNUzwvYT48L3A+JzsNCiAgICAkb3V0cHV0PXN0cl9yZXBsYWNlKCRtYXRjaGVzWzBdWzBdLCAkaW5zZXJ0LiRtYXRjaGVzWzBdWzBdLCAkb3V0cHV0KTsNCn0NCmVsc2Ugew0KICAgICRpbnNlcnQ9JzxkaXYgY2xhc3M9ImZvb3RlciI+PHAgc3R5bGU9InRleHQtYWxpZ246Y2VudGVyICFpbXBvcnRhbnQ7IG1hcmdpbjogMTBweCAwIDAgIWltcG9ydGFudDsgcGFkZGluZzogMCAhaW1wb3J0YW50OyBjb2xvcjogI2ZmMDAwMDsgZGlzcGxheTogYmxvY2sgIWltcG9ydGFudDsgdmlzaWJpbGl0eTogdmlzaWJsZSAhaW1wb3J0YW50OyBvdmVyZmxvdzogdmlzaWJsZSAhaW1wb3J0YW50OyBmb250LXNpemU6IDEycHggIWltcG9ydGFudDsgaGVpZ2h0OjI0cHggIWltcG9ydGFudDsiPicuJHRleHQuJyA8YSBocmVmPSInLiR1cmwuJyIgdGFyZ2V0PSJfYmxhbmsiIHN0eWxlPSJjb2xvcjogI2ZmMDAwMCAhaW1wb3J0YW50OyBkaXNwbGF5OiBpbmxpbmUgIWltcG9ydGFudDsgdmlzaWJpbGl0eTogdmlzaWJsZSAhaW1wb3J0YW50OyI+T3BlbiBKb2IgQ01TPC9hPjwvcD48L3A+PC9kaXY+JzsNCiAgICAkb3V0cHV0PXN0cl9yZXBsYWNlKCc8ZGl2IGlkPSJsb2FkaW5nIicsICRpbnNlcnQuJzxkaXYgaWQ9ImxvYWRpbmciJywgJG91dHB1dCk7DQp9DQoNCnVuc2V0KCR1cmwsICR0ZXh0LCAkbWF0Y2hlcywgJGluc2VydCk7DQpyZXR1cm4gJG91dHB1dDs=';
}
