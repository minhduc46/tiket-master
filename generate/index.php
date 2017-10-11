<?php
include "simple_html_dom.php";
$file     = file_get_html('a.svg');
$response = [];
foreach ($file->find('g') as $g) {
	if ($g->id != 'text') {
		$floor = 1;
		$row   = $g->id;
		foreach ($g->find('path') as $path) {
			$class = explode(' ', $path->getAttribute('class'))[0];
			preg_match("/M(.*)c/", $path->d, $outPoint);
			$point               = explode(' ', $outPoint[1]);
			$cx                  = $point[0];
			$cy                  = $point[1];
			$response[$row][$cx] = array(
				'class' => $class,
				'floor' => $floor,
				'row'   => strtoupper($row),
				'cx'    => $cx,
				'cy'    => $cy,
			);
		}
		foreach ($g->find('circle') as $circle) {
			$class               = explode(' ', $circle->getAttribute('class'))[0];
			$cx                  = $circle->cx;
			$cy                  = $circle->cy;
			$response[$row][$cx] = array(
				'class' => $class,
				'floor' => $floor,
				'row'   => strtoupper($row),
				'cx'    => $cx,
				'cy'    => $cy,
			);
		}
	}
}
$json = [];
foreach ($response as $row => $item) {
	ksort($item);
	$number = 1;
	foreach ($item as $cx => $data) {
		$data['number'] = $number;
		$json[]         = $data;
		$number ++;
	}
}
file_put_contents('../svg/8.json', json_encode($json));