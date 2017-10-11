<?php
/**
 * @var \app\models\Order $model
 */
use app\helpers\DateHelper;

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/web/css/print.css"/>
</head>
<body>
<div class="invoice-box">

	<div class="fleft company-information">
		<div class="fleft logo-company">
			<img src="/web/images/logo_company.png">
		</div>
		<div class="fleft company-info">
			<b class="text-bold"><?= Yii::$app->params['company_name'] ?></b>
			<p><?= Yii::$app->params['company_address'] ?></p>
			<p><?= Yii::$app->params['company_phone'] ?></p>
		</div>
	</div>
	<div class="fleft event-information">
		<p>Chương trình</p>
		<b class="text-bold"><?= $model->event->name ?></b>
		<p>Mã đơn hàng: <b>#<?= str_pad($model->number, 7, '0', STR_PAD_LEFT) ?></b></p>
	</div>
	<div class="customer-information">
		<p class="fleft col8">Khách hàng: <b><?= $model->customer_name ?></b></p>
		<p class="fright col4">Điện thoại: <?= $model->customer_phone ?></p>
		<p class="fleft col8">Địa chỉ: <?= $model->customer_address ?></p>
		<p class="fright col4">Thời gian đặt: <?= DateHelper::format($model->booked_date, 'Y-m-d H:i:s', 'd/m/Y') ?></p>
		<div class="note">
			<span style="position: relative; background: #fff">Ghi chú:</span> <?= $model->note ?>

		</div>
	</div>
	<table cellpadding="0" cellspacing="0">
		<tr class="heading">
			<th>
				STT
			</th>
			<th>
				LOẠI VÉ
			</th>
			<th>
				SỐ LƯỢNG
			</th>
			<th>
				ĐƠN GIÁ
			</th>
			<th>
				THÀNH TIỀN
			</th>
		</tr>
		<tbody>
		<?php
		foreach ($model->getOrderSeatArray() as $num => $orderSeat):?>
			<tr class="item <?= $num == (count($model->getOrderSeatArray()) - 1) ? 'last' : '' ?>" style="border-bottom: 2px dotted;">
				<td><b><?php echo $num + 1; ?></b></td>
				<td><?php echo $orderSeat['seats'] ?></td>
				<td><?php echo $orderSeat['count'] ?></td>
				<td align="right"><?php echo number_format($orderSeat['price']) ?></td>
				<td align="right">
					<b><?php echo number_format($orderSeat['total']); ?></b>
				</td>
			</tr>
			<?php
			$num ++;
		endforeach; ?>

		<tr class="hr">
			<td colspan="5">
				<hr class="total">
			</td>
		</tr>
		<tr class="total first">
			<td colspan="3"></td>
			<td>
				Tổng:
			</td>
			<td align="right">
				<b><?= number_format($model->subTotal) ?></b>
			</td>
		</tr>
		<?php if ($model->discount > 0): ?>
			<tr class="hr">
				<td colspan="5">
					<hr class="total">
				</td>
			</tr>
			<tr class="total">
				<td colspan="3"></td>
				<td>
					Chiết khấu:
				</td>
				<td align="right">
					<b><?php echo number_format($model->discount); ?><?php echo $model->getDiscountType() ?></b>
				</td>
			</tr>
		<?php endif; ?>
		<tr class="hr">
			<td colspan="5">
				<hr class="total">
			</td>
		</tr>
		<tr class="total">
			<td colspan="3"></td>
			<td>
				<b>Tổng cộng:</b>
			</td>
			<td align="right">
				<b><?php echo number_format($model->grandTotal); ?></b>
			</td>
		</tr>
		</tbody>

	</table>
	<div class="order-info">
		<div class="order-info-item">
			<p>Khách hàng</p>
		</div>
		<div class="order-info-item">
			<p>Shiper</p>
		</div>
		<div class="order-info-item">
			<p>Nhân viên bán hàng</p>

		</div>
	</div>
</div>
</body>
</html>

