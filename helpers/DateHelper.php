<?php
namespace app\helpers;

use DateTime;

class DateHelper {

	/**
	 * @param        $current_date
	 * @param string $source_format
	 * @param string $destination_format
	 *
	 * @return string
	 */
	public static function format($current_date, $source_format = 'd-m-Y', $destination_format = 'Y-m-d') {
		$date = DateTime::createFromFormat($source_format, $current_date);
		if (!$date) {
			return $current_date;
		} else {
			return $date->format($destination_format);
		}
	}
}