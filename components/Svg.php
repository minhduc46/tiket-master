<?php
namespace app\components;
/**
 * Created by PhpStorm.
 * User: notte_000
 * Date: 14/08/2015
 * Time: 3:35 CH
 */
class Svg {

	public static function drawSeat($seats, $radius, $bookedSeats, $prices) {
		$price_id = 0;

		foreach ($prices as $price) {
			if ($price->class == str_replace("fil", "", $seats['class'])) {
				$price_id = $price->id;
				break;
			}
		}
		$booked = 0;
		$active = '';
		$seat   = array_diff_key($seats, array_flip(array(
			'cx',
			'cy',
			'class',
		)));
		/*echo '<pre>';
		print_r($bookedSeats);
		print_r($seat);
		die;*/
		if (in_array($seat, $bookedSeats)) {
			$booked = 1;
			$active = 'active';
		}
		$html = '<g transform="translate(' . $seats['cx'] . ',' . $seats['cy'] . ')" data-price-id="' . $price_id . '" data-row="' . $seats['row'] . '" data-floor="' . $seats['floor'] . '" data-booked="' . $booked . '" data-number="' . $seats['number'] . '" class="' . $active . '">';
		$html .= '<circle r="' . $radius . '" class="' . $seats['class'] . '"></circle>';
		$html .= '<text text-anchor="middle" dominant-baseline="central" font-size="' . (2 * $radius / sqrt(2)) . '">' . $seats['number'] . '</text>';
		$html .= '</g>';

		return $html;
	}
}