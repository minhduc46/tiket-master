<?php
/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 8/26/2016
 * Time: 11:44 AM
 */
?>
<table class="table table-striped table-bordered table-hover dataTable">
	<thead>
	<tr>
		<th>Giá vé</th>
		<th>Tổng</th>
		<th>Đã bán</th>
		<th>Chưa bán</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($seatStatistic as $seat): ?>
		<tr>
			<td>

				<span class="seat-color" style="background: <?php echo $seat['color'] ?>"></span><?php echo $seat['price'] ?>
			</td>
			<td>
				<strong><?php echo $seat['total'] ?></strong>
			</td>
			<td>
				<?php echo $seat['sold'] ?>
			</td>
			<td>
				<?php echo $seat['instock'] ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
