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

class HDate {

	const MYSQL_FORMAT = 'Y-m-d H:i:s';
	const MYSQL_DATE_FORMAT = 'Y-m-d';
	const MYSQL_DATETIME_NONE = '0000-00-00 00:00:00';

	public static function getSmart($timestamp, $datestr = '%d.%m.%Y %G:%i', $time = true) {
		if ($timestamp == '') {
			$timestamp = time();
		}

		$datestr = str_replace('%\\', '', preg_replace("/([a-z]+?){1}/i", "\\\\\\1", $datestr));
		$timestamp = date($datestr, $timestamp);

		$monthes = array(
			'', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
			'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
		);
		$date = strtotime($timestamp);

		//Время
		if ($time) {
			$time = ' в G:i';
		} else {
			$time = '';
		}

		//Сегодня, вчера, завтра
		if (date('Y') == date('Y', $date)) {
			if (date('z') == date('z', $date)) {
				$result_date = date('сегодня' . $time, $date);
			} elseif (date('z') == date('z', mktime(0, 0, 0, date('n', $date), date('j', $date) + 1, date('Y', $date)))) {
				$result_date = date('вчера' . $time, $date);
			} elseif (date('z') == date('z', mktime(0, 0, 0, date('n', $date), date('j', $date) - 1, date('Y', $date)))) {
				$result_date = date('завтра' . $time, $date);
			} elseif (date('z') == date('z', mktime(0, 0, 0, date('n', $date), date('j', $date) + 2, date('Y', $date)))) {
				$result_date = date('2 дня назад' . $time, $date);
			} elseif (date('z') == date('z', mktime(0, 0, 0, date('n', $date), date('j', $date) + 3, date('Y', $date)))) {
				$result_date = date('3 дня назад' . $time, $date);
			}

			if (isset($result_date)) {
				return $result_date;
			}
		}

		//Месяца
		$month = $monthes[date('n', $date)];

		//Года
		if (date('Y') != date('Y', $date)) {
			$year = 'Y г.';
		} else {
			$year = '';
		}

		$result_date = date('j ' . $month . ' ' . $year . $time, $date);
		return $result_date;
	}

	private static $_periods = array(
		0 => array(
			'words' => array('секунда', 'секунды', 'секунд'),
			'diff' => 1
		),
		1 => array(
			'words' => array('минута', 'минуты', 'минут'),
			'diff' => 60
		),
		2 => array(
			'words' => array('час', 'часа', 'часов'),
			'diff' => 3600
		),
		3 => array(
			'words' => array('день', 'дня', 'дней'),
			'diff' => 86400
		),
//		4 => array(
//			'words' => array('неделя', 'недели', 'недель'),
//			'diff' => 604800
//		),
		4 => array(
			'words' => array('месяц', 'месяца', 'месяцев'),
			'diff' => 2630880
		),
		5 => array(
			'words' => array('год', 'года', 'лет'),
			'diff' => 31570560
		),
		6 => array(
			'words' => array('десятилетие', 'десятилетия', 'десятилетий'),
			'diff' => 315705600
		),
	);

	/** Сколько времени до событиая
	 * @param $date timestamp
	 * @return string
	 */
	public static function getAwait($date) {
		$str = '';
		$restTime = 0;

		$currentTime = time();
		$diffTime = $currentTime - $date;

		for ( $i = 0 ; $i < count(self::$_periods)-1 ; $i++ ){
			$num = $diffTime / self::$_periods[$i]['diff'];

			if($num <= 1){
				break;
			} else {
				$restTime = $diffTime - (floor($num) * self::$_periods[$i]['diff']);
				$str = sprintf("%d %s ", floor($num), HDate::_getPhrase($num, self::$_periods[$i]['words']));
			}
		}

		if($restTime > 86400){
			$str .= self::getAwait($currentTime - $restTime);
		}

		return $str;
	}

	private static function _getPhrase($number, $titles) {
		$cases = array(2, 0, 1, 1, 1, 2);
		return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}

	/** Возраст от даты
	 * @param $day
	 * @param $month
	 * @param $year
	 * @return string
	 */
	public static function dateAge($day, $month, $year) {
		$yearResult = date('Y') - $year;

		if ($month > date('m') || $month == date('m') && $day > date('d')) {
			$yearResult = date('Y') - $year - 1;
		}

		return Yii::t('main', '{n} год|{n} года|{n} лет', $yearResult);
	}

	/** echo rdate(‘j M Y г. (N) в H:i’);
	 * @param $param
	 * @param int $time
	 * @return string
	 */
	public static function rdate($param, $time = 0) {
		if (intval($time) == 0) {
			$time = time();
		}
		$MN = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		$MonthNames[] = $MN[date('n', $time) - 1];
		$MN = array('', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье');
		$MonthNames[] = $MN[date('w', $time)];
		$arr[] = 'M';
		$arr[] = 'N';
		if (strpos($param, 'M') === false) {
			return date($param, $time);
		} else {
			return date(str_replace($arr, $MonthNames, $param), $time);
		}
	}

	public static function formatDate($date, $now = null){
		$time = strtotime($date);

		if($now){
			$time = strtotime($now, $time);
		}
		$ret = HDate::rdate('d M Y, H:i', $time);

		return $ret;
	}


	public $timezones = array(
		'Pacific/Midway'       => "(GMT-11:00) Midway Island",
		'US/Samoa'             => "(GMT-11:00) Samoa",
		'US/Hawaii'            => "(GMT-10:00) Hawaii",
		'US/Alaska'            => "(GMT-09:00) Alaska",
		'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
		'America/Tijuana'      => "(GMT-08:00) Tijuana",
		'US/Arizona'           => "(GMT-07:00) Arizona",
		'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
		'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
		'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
		'America/Mexico_City'  => "(GMT-06:00) Mexico City",
		'America/Monterrey'    => "(GMT-06:00) Monterrey",
		'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
		'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
		'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
		'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
		'America/Bogota'       => "(GMT-05:00) Bogota",
		'America/Lima'         => "(GMT-05:00) Lima",
		'America/Caracas'      => "(GMT-04:30) Caracas",
		'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
		'America/La_Paz'       => "(GMT-04:00) La Paz",
		'America/Santiago'     => "(GMT-04:00) Santiago",
		'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
		'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
		'Greenland'            => "(GMT-03:00) Greenland",
		'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
		'Atlantic/Azores'      => "(GMT-01:00) Azores",
		'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
		'Africa/Casablanca'    => "(GMT) Casablanca",
		'Europe/Dublin'        => "(GMT) Dublin",
		'Europe/Lisbon'        => "(GMT) Lisbon",
		'Europe/London'        => "(GMT) London",
		'Africa/Monrovia'      => "(GMT) Monrovia",
		'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
		'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
		'Europe/Berlin'        => "(GMT+01:00) Berlin",
		'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
		'Europe/Brussels'      => "(GMT+01:00) Brussels",
		'Europe/Budapest'      => "(GMT+01:00) Budapest",
		'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
		'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
		'Europe/Madrid'        => "(GMT+01:00) Madrid",
		'Europe/Paris'         => "(GMT+01:00) Paris",
		'Europe/Prague'        => "(GMT+01:00) Prague",
		'Europe/Rome'          => "(GMT+01:00) Rome",
		'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
		'Europe/Skopje'        => "(GMT+01:00) Skopje",
		'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
		'Europe/Vienna'        => "(GMT+01:00) Vienna",
		'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
		'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
		'Europe/Athens'        => "(GMT+02:00) Athens",
		'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
		'Africa/Cairo'         => "(GMT+02:00) Cairo",
		'Africa/Harare'        => "(GMT+02:00) Harare",
		'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
		'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
		'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
		'Europe/Kiev'          => "(GMT+02:00) Kyiv",
		'Europe/Minsk'         => "(GMT+02:00) Minsk",
		'Europe/Riga'          => "(GMT+02:00) Riga",
		'Europe/Sofia'         => "(GMT+02:00) Sofia",
		'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
		'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
		'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
		'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
		'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
		'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
		'Asia/Tehran'          => "(GMT+03:30) Tehran",
		'Europe/Moscow'        => "(GMT+04:00) Moscow",
		'Asia/Baku'            => "(GMT+04:00) Baku",
		'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
		'Asia/Muscat'          => "(GMT+04:00) Muscat",
		'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
		'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
		'Asia/Kabul'           => "(GMT+04:30) Kabul",
		'Asia/Karachi'         => "(GMT+05:00) Karachi",
		'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
		'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
		'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
		'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
		'Asia/Almaty'          => "(GMT+06:00) Almaty",
		'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
		'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
		'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
		'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
		'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
		'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
		'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
		'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
		'Australia/Perth'      => "(GMT+08:00) Perth",
		'Asia/Singapore'       => "(GMT+08:00) Singapore",
		'Asia/Taipei'          => "(GMT+08:00) Taipei",
		'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
		'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
		'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
		'Asia/Seoul'           => "(GMT+09:00) Seoul",
		'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
		'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
		'Australia/Darwin'     => "(GMT+09:30) Darwin",
		'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
		'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
		'Australia/Canberra'   => "(GMT+10:00) Canberra",
		'Pacific/Guam'         => "(GMT+10:00) Guam",
		'Australia/Hobart'     => "(GMT+10:00) Hobart",
		'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
		'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
		'Australia/Sydney'     => "(GMT+10:00) Sydney",
		'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
		'Asia/Magadan'         => "(GMT+12:00) Magadan",
		'Pacific/Auckland'     => "(GMT+12:00) Auckland",
		'Pacific/Fiji'         => "(GMT+12:00) Fiji",
	);
}