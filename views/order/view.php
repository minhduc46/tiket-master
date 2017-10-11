<?php
use app\assets\AppAsset;
use app\models\Order;
use yii\helpers\Url;

/**
 * @var Order   $model
 * @var Order[] $diffs
 */
$this->title                   = 'Chi tiết đơn hàng';
$this->params['breadcrumbs'][] = $this->title;
$bundle                        = AppAsset::register($this);
?>
<link rel="stylesheet" href="<?php echo $bundle->baseUrl ?>/web/css/print.css" media="print"/>
<div class="page-content">
	<div class="col-sm-12">
		<div class="space-6"></div>
		<div class="row">
			<div class="col-sm-12">
				<div class="widget-box transparent invoice-box">
					<div class="widget-header widget-header-large">
						<h3 class="header smaller lighter blue action-buttons">Chi tiết đơn hàng "<?php echo str_pad($model->number, 7, "0", STR_PAD_LEFT) ?>"</h3>

						<div class="widget-toolbar no-border invoice-info">
							<span class="invoice-info-label">Mã đơn hàng:</span>
							<span class="red">#<?php echo str_pad($model->number, 7, "0", STR_PAD_LEFT); ?></span>

							<br>
							<span class="invoice-info-label">Thời gian đặt:</span>
							<span class="blue"><?php echo $model->booked_date; ?></span>
						</div>
						<div class="widget-toolbar hidden-480">
							<a class="btn-pdfprint" href="<?= Url::to([
								'print',
								'number'   => $model->number,
								'event_id' => $model->event_id,
							]) ?>" target="_blank">
								<i class="ace-icon fa fa-print"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main padding-24">
							<div class="row">
								<div class="row col-xs-12">
									<div class="col-xs-6">
										<div class="row">
											<div class="col-sm-12 label label-large label-info arrowed-in arrowed-right">
												<b>Thông tin khách hàng</b>
											</div>
										</div>

										<div class="row">
											<ul class="unstyled spaced">
												<li>
													<i class="icon-caret-right blue"></i>
													Họ tên khách hàng:
													<b><?php echo $model->customer_name; ?></b>
												</li>

												<li>
													<i class="icon-caret-right blue"></i>
													Địa chỉ khách hàng:
													<b><?php echo $model->customer_address; ?></b>
												</li>

												<li>
													<i class="icon-caret-right blue"></i>
													Điện thoại:
													<b class="red"><?php echo $model->customer_phone; ?></b>
												</li>

											</ul>
										</div>
									</div>
									<!--/col-sm--->

									<div class="col-xs-6">
										<div class="row">
											<div class="col-sm-12 label label-large label-success arrowed-in arrowed-right">
												<b>Thông tin đơn hàng</b>
											</div>
										</div>

										<div class="row">
											<ul class="unstyled spaced">
												<li>
													<i class="icon-caret-right green"></i>
													Chương trình:
													<b><?php echo $model->event->name; ?> </b>
												</li>

												<li>
													<i class="icon-caret-right green"></i>
													Thời gian đặt:
													<b><?php echo $model->booked_date; ?> </b>
												</li>

												<li>
													<i class="icon-caret-right green"></i>
													Số lượng vé:
													<b><?php echo count($model->orderSeats); ?> </b>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="space"></div>
								<div class="row">
									<table class="table table-striped table-bordered">
										<thead>
										<tr>
											<th class="center">#</th>
											<th>Loại vé</th>
											<th class="hidden-phone">Số lượng</th>
											<th>Vị trí</th>
											<th class="hidden-480" style="text-align: right">Thành tiền</th>
										</tr>
										</thead>

										<tbody>
										<?php
										$num = 1;
										foreach ($model->getOrderSeatArray() as $orderSeat): ?>
											<tr>
												<td class="center col-sm-1"><?php echo $num; ?></td>
												<td class="col-sm-2">
													<?php echo number_format($orderSeat['price']) ?>
												</td>
												<td class="col-sm-1">
													<?php echo $orderSeat['count'] ?>
												</td>
												<td class="col-sm-6"><?php echo $orderSeat['seats'] ?></td>
												<td class="col-sm-2" style="text-align: right">
													<b><?php echo number_format($orderSeat['total']); ?></b></td>
											</tr>
											<?php
											$num ++;
										endforeach; ?>
										</tbody>
									</table>
								</div>

								<div class="hr hr8 hr-double hr-dotted"></div>

								<div class="row">
									<div class="col-sm-6">
										Ghi chú:
										<b><?php echo $model->note; ?></b>
									</div>
									<div class="col-sm-offset-3 col-sm-3 pull-right total">
										<h5 style="margin: 0">
											<label style="display: inline-block">Tiền vé :</label>
											<span class="red"><b><?php echo number_format($model->subTotal); ?> ₫</b></span>
										</h5>
										<h5 style="margin: 0">
											<label style="display: inline-block">Chiết khấu :</label>
											<span class="red"><b><?php echo number_format($model->discount); ?><?php echo $model->getDiscountType() ?></b></span>
										</h5>
										<h4>
											<label style="display: inline-block">Tổng tiền :</label>
											<span class="green"><b><?php echo number_format($model->grandTotal); ?> ₫</b></span>
										</h4>
									</div>
								</div>
								<div class="space-6"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($diffs != null) : ?>
		<div class="col-sm-12 order-history">
			<div class="row">
				<h3 class="header smaller lighter blue action-buttons">Lịch sử thay đổi đơn hàng "<?php echo str_pad($model->number, 7, "0", STR_PAD_LEFT) ?>"
				</h3>

				<div class="col-sm-12">
					<table class="table table-striped table-bordered">
						<thead>
						<tr>
							<th class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">#</th>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<th class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<b class="blue"><?php echo $diffs[$i]->updated_date ?></b>
								</th>
							<?php endfor; ?>
						</tr>
						</thead>
						<tbody>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Họ tên khách hàng</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<?php echo $diffs[$i]->customer_name ?>
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Địa chỉ khách hàng</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<?php echo $diffs[$i]->customer_address ?>
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Điện thoại</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<?php echo $diffs[$i]->customer_phone ?>
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Vị trí ghế</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<?php
									if ((count($diffs) + 1) == 2 && $i == 0) {
										echo $diffs[0]->getSeatString(0, array_diff(explode(", ", $diffs[0]->getSeatString(0)), explode(", ", $diffs[1]->getSeatString(0))), array_diff(explode(", ", $diffs[1]->getSeatString(0)), explode(", ", $diffs[0]->getSeatString(0))));
									} else if ((count($diffs) + 1) == 2 && $i == 1) {
										echo $diffs[1]->getSeatString(0, array_diff(explode(", ", $diffs[1]->getSeatString(0)), explode(", ", $diffs[0]->getSeatString(0))), array_diff(explode(", ", $diffs[1]->getSeatString(0)), explode(", ", $diffs[1]->getSeatString(0))));
									} else {
										if ($i < 2 && $diffs[$i + 1] !== null) {
											echo $diffs[$i]->getSeatString(0, array_diff(explode(", ", $diffs[$i]->getSeatString(0)), explode(", ", $diffs[$i + 1]->getSeatString(0))), array_diff(explode(", ", $diffs[$i + 1]->getSeatString(0)), explode(", ", $diffs[$i]->getSeatString(0))));
										} else {
											echo $diffs[$i]->getSeatString(0);
										}
									}
									?>
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Số lượng ghế</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<?php echo count($diffs[$i]->orderSeats) ?>
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Tiền vé</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<?php echo number_format($diffs[$i]->subTotal) ?> ₫
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Tiền chiết khấu</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<?php echo number_format($diffs[$i]->discount) . $diffs[$i]->getDiscountType() ?>
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Tổng tiền</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<b class="green"><?php echo number_format($diffs[$i]->grandTotal) ?> ₫</b>
								</td>
							<?php endfor; ?>
						</tr>
						<tr class="edit-history">
							<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> left">Người thực hiện</td>
							<?php for ($i = 0; $i < count($diffs); $i ++): ?>
								<td class="col-sm-<?php echo(12 / (count($diffs) + 1)) ?> center">
									<b class="red"><?php echo $diffs[$i]->user->username ?></b>
								</td>
							<?php endfor; ?>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>