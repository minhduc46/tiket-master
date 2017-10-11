<div class="col-xs-6 statistic-detail ">
<table class="table table-bordered">
	<tr class="text-center">
		<td colspan="5">Tổng số vé đã bán</td>
	</tr>
	<tr>
		<td>Đơn vị phân phối</td>
		<td>Số vé</td>
		<td>Tiền chiết khấu</td>
		<td>Tiền nợ</td>
		<td>Doanh thu</td>
	</tr>
	<tr>
		<?php
		$allResult = array(
			'revenue'  => 0,
			'discount' => 0,
			'ticket'   => 0,
			'remain'   => $count,
			'sumRemain'=>0
		);
		foreach ($all as $allStatistic) :
			$allResult['revenue'] += $allStatistic['revenue'];
			$allResult['discount'] += $allStatistic['discount'];
			$allResult['ticket'] += $allStatistic['ticket'];
			$allResult['remain'] -= $allStatistic['ticket'];
			$allResult['sumRemain'] += $allStatistic['remain1'];
			?>
				<td class="red"><?php echo $allStatistic['name'] ?></td>
				<td><?php echo number_format($allStatistic['ticket']) ?></td>
				<td><?php echo number_format($allStatistic['discount']) ?></td>
				<td><?php echo number_format($allStatistic['remain1']) ?></td>
				<td class="green"><?php echo number_format($allStatistic['revenue']) ?></td>
		<?php endforeach; ?>
	</tr>
	<tr>
		<td>Tổng số</td>
		<td><?php echo number_format($allResult['ticket']) ?></td>
		<td><?php echo number_format($allResult['discount']) ?></td>
		<td><?php echo number_format($allResult['sumRemain']) ?></td>
		<td class="green"><?php echo number_format($allResult['revenue']) ?></td>
	</tr>
	<tr>
		<td>Còn trống</td>
		<td class="red"><?php echo number_format($allResult['remain']) ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

</table>
</div>
<div class="col-xs-6 statistic-detail">
	<table class="table table-bordered">
		<tr class="text-center">
			<td colspan="5">Số vé đã bán hôm nay</td>
		</tr>
		<tr>
			<td>Đơn vị phân phối</td>
			<td>Số vé</td>
			<td>Tiền chiết khấu</td>
			<td>Tiền nợ</td>
			<td>Doanh thu</td>
		</tr>
		<tr>
			<?php
			$todayResult = array(
				'revenue'  => 0,
				'discount' => 0,
				'ticket'   => 0,
				'sumRemain'=>0
			);
			foreach ($today as $todayStatistic) :
				$todayResult['revenue'] += $todayStatistic['revenue'];
				$todayResult['discount'] += $todayStatistic['discount'];
				$todayResult['ticket'] += $todayStatistic['ticket'];
				$todayResult['sumRemain'] += $todayStatistic['remain1'];
				?>
				<td class="red"><?php echo $todayStatistic['name'] ?></td>
				<td><?php echo number_format($todayStatistic['ticket']) ?></td>
				<td><?php echo number_format($todayStatistic['discount']) ?></td>
				<td><?php echo number_format($todayStatistic['remain1']) ?></td>
				<td class="green"><?php echo number_format($todayStatistic['revenue']) ?></td>
			<?php endforeach; ?>
		</tr>
		<tr>
			<td>Tổng số</td>
			<td><?php echo number_format($todayResult['ticket']) ?></td>
			<td><?php echo number_format($todayResult['discount']) ?></td>
			<td><?=number_format($todayResult['sumRemain'])?></td>
			<td class="green"><?php echo number_format($todayResult['revenue']) ?></td>
		</tr>
		<tr>
			<td>Còn trống</td>
			<td class="red"><?php echo number_format($allResult['remain']) ?></td>
			<td>
			</td>
			<td></td>
			<td></td>
		</tr>

	</table>
</div>

