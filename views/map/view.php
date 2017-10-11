<?php
/**@var $event Event */
use app\assets\AppAsset;
use app\components\View;
use app\controllers\MapController;
use app\models\Agency;
use app\models\Branch;
use app\models\Event;
use app\models\Order;
use app\models\Price;
use app\models\User;
use kartik\select2\Select2;
use navatech\role\helpers\RoleChecker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**@var $form ActiveForm */
/**@var $order Order */
/**
 * @var         $this View
 * @var string  $seatData
 * @var Price[] $prices
 */
$this->title                   = 'Bản đồ';
$this->params['breadcrumbs'][] = $this->title;
$bundle                        = AppAsset::register($this);
?>
<link rel="stylesheet" href="<?= $bundle->baseUrl ?>/web/css/map-style.css">

</link>
<style>
	.seat-color {
		width: 20px;
		height: 20px;
		border-radius: 50%;
		display: block;
		float: left;
		margin-right: 5px;
	}

	<?php
	 foreach($prices as $price):?>
	.fil<?php echo $price->class;?> {
		fill: <?php echo $price->color?>;
	}

	<?php endforeach;?>
</style>

	<?php if (RoleChecker::isAuth(MapController::className(), 'view')): ?>
		<div class="page-header position-relative">
			<h1>Bản đồ chương trình "<?php echo $event->name ?>"</h1>
			<?php if (RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
				<button style="float: right" class="btn btn-primary btn-statistic down" type="button">
					<i class="fa fa-angle-down"></i> Thống kê
				</button>
			<?php endif; ?>
		</div>
		<div class="row-fluid statistic" style="display: none;">
		</div>
	<?php endif; ?>
	<div class="row">
		<div class="col-sm-8">
			<div id="svgContainer">
				<div id="svgMain">
					<svg id="container" width="1570" height="2300">
						<?php echo file_get_contents(Yii::getAlias('@app') . '/svg/path' . $event->id . '.dat'); ?>
						<?php echo $seatData; ?>
					</svg>
				</div>
				<div id="center"></div>
			</div>
		</div>
		<?php if (RoleChecker::isAuth(MapController::className(), 'view')): ?>
			<div class="col-sm-4">
				<div class="leftNav">
					<div class="seats-list" style="display: none;">
					</div>
					<div class="price-list">
						<div class="col-xs-12" style="height: 0;min-height: 1px;"></div>
						<div class="col-xs-12">
							<?php foreach ($prices as $key => $price): ?>
							<div class="col-xs-4">
								<div class="price" style="background: <?php echo $price->color ?>"><?= $key + 1 ?></div>
								<?php echo number_format($price->price); ?>
							</div>
							<?php if ($key % 3 == 2 && $key < (count($prices) - 1)): ?>
						</div>
						<div class="col-xs-12">
							<?php endif; ?>
							<?php endforeach; ?>
							<div class="col-xs-4">
								<div class="price sold">X</div>
								Đã bán
							</div>
						</div>
					</div>
					<?php $form = ActiveForm::begin(['id' => 'order-form']);; ?>
					<?php if (RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
						<div class="control-group col-xs-12">
							<h4 class="header smaller lighter blue" style="text-align: center">Đặt vé</h4>
						</div>
						<div class="control-group col-xs-12 scroll">
							<div class="col-xs-12">
								<div class="col-xs-1 trash"></div>
								<div class="col-xs-3">Vị trí</div>
								<div class="col-xs-3">Tầng</div>
								<div class="col-xs-4">Giá vé</div>
							</div>
							<div class="seat-info new">
								<div class="col-xs-12" style="height: 0;min-height: 1px;"></div>
							</div>
							<div class="seat-loading col-xs-12" style="display: none;">
								Vui lòng chờ giây lát...
							</div>
						</div>
					<?php endif; ?>
					<?php echo $form->field($order, 'event_id')->hiddenInput([
						'value' => $event->id,
						'id'    => 'Order_event_id',
					])->label(false); ?>
					<?php echo $form->field($order, 'number')->hiddenInput(['id' => 'Order_number'])->label(false); ?>
					<input type="hidden" name="owner" id="Order_owner" value="0">

					<div class="col-xs-12">
						<div class="control-group customer-info" style="text-align: center;">
							<div class="col-xs-12">
								<div class="col-xs-10">
									<h4 class="header smaller lighter blue">Thông tin khách hàng</h4>
								</div>
								<div class="widget-toolbar hidden-480 col-xs-2" id="print_tab" style="display: none">
									<a class="btn-pdfprint" target="_blank" id="print">
										<i class="ace-icon fa fa-print"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="control-group customer-info col-xs-12" style="display: none;">
							<div class="col-xs-5">
								<?php echo Html::activeLabel($order, 'number', ['class' => 'control-label']); ?>
							</div>

							<div class="controls col-xs-7">
								<a href="#" target="_blank" class="order-number"></a>
							</div>
						</div>
						<div class="control-group customer-info col-xs-12" style="display: none;">
							<div class="col-xs-5">
								<label class="control-label" for="Order_updated_date">Thời gian</label>
							</div>

							<div class="controls col-xs-7">
								<a class="order-updated_date"></a>
							</div>
						</div>
						<div class="control-group customer-info col-xs-12" style="display: none;">
							<div class="col-xs-6">
								<label class="control-label" for="Order_print">In hóa đơn</label>
							</div>

							<div class="controls col-xs-6">

							</div>
						</div>
						<?php if (RoleChecker::isAuth('map', 'check-user') && RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
							<div class="control-group customer-info">

								<div class="controls">
									<?php if (Yii::$app->user->id == 1) : ?>
										<?php echo $form->field($order, 'user_id')->widget(Select2::className(), [
											'data' => ArrayHelper::map(User::getAllUser(false), 'id', 'username'),
											'id'   => 'order-user_id',
										]);
										?>
									<?php else : ?>
										<?php echo $form->field($order, 'user_id')->widget(Select2::className(), [
											'data' => ArrayHelper::map(User::getAllUser(), 'id', 'username'),
										]); ?>
									<?php endif; ?>
									<?php echo Html::error($order, 'user_id'); ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="control-group customer-info">
									<?php echo Html::activeLabel($order, 'customer_name', ['class' => 'control-label']); ?>
									<div class="controls">
										<?php echo $form->field($order, 'customer_name')->textInput(['id' => 'Order_customer_name'])->label(false); ?>
									</div>
								</div>
							</div>
							<?php if (RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
								<div class="col-sm-6">
									<div class="control-group customer-info">
										<?php echo Html::activeLabel($order, 'customer_phone', ['class' => 'control-label',]); ?>

										<div class="controls">
											<?php echo $form->field($order, 'customer_phone')->textInput([
												'id'       => 'Order_customer_phone',
												'readonly' => "readonly",
											])->label(false); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<?php if (RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
							<div class="control-group customer-info">
								<?php echo Html::activeLabel($order, 'customer_address', ['class' => 'control-label']); ?>

								<div class="controls">
									<?php echo $form->field($order, 'customer_address')->textInput([
										'id'       => 'Order_customer_address',
										'readonly' => "readonly",
									])->label(false); ?>
								</div>
							</div>
							<div class="control-group customer-info">
								<?php echo Html::activeLabel($order, 'note', ['class' => 'control-label']); ?>

								<div class="controls">
									<?php echo $form->field($order, 'note')->textarea(['readonly' => "readonly"])->label(false); ?>
								</div>
							</div>
							<div class="control-group customer-info">
								<?php echo Html::activeLabel($order, 'discount', ['class' => 'control-label']); ?>

								<div class="controls discount-type">
									<?php echo $form->field($order, 'discount')->textInput([
										'class'    => 'col-xs-6',
										'readonly' => "readonly",
										'id'       => 'Order_discount',
									])->label(false); ?>
									<input type="radio" value="0" name="discount_type" id="Order_discount_type_0">
									<label for="Order_discount_type_0">
										<i class="fa fa-percent">
										</i>
									</label>
									<input type="radio" value="1" name="discount_type" id="Order_discount_type_1">
									<label for="Order_discount_type_1">
										<i class="fa fa-money"></i>
									</label>
									<?php echo $form->field($order, 'discount_type')->hiddenInput()->label(false); ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="control-group customer-info">
									<?php echo Html::activeLabel($order, 'agency_id', ['class' => 'control-label']); ?>

									<div class="controls">
										<?php echo $form->field($order, 'agency_id')->widget(Select2::className(), [
											'data'     => ArrayHelper::map(Agency::find()->all(), 'id', 'name'),
											'readonly' => "readonly",
											'id'       => 'Order_agency_id',
										])->label(false); ?>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="control-group customer-info">
									<?php echo Html::activeLabel($order, 'status', ['class' => 'control-label']); ?>

									<div class="controls">
										<?php echo $form->field($order, 'status')->dropDownList(Order::getArrStatus(), [
											'readonly' => "readonly",
											'id'       => 'Order_status',
										])->label(false); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<?php if (RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
								<div class="col-sm-6">
									<div class="control-group customer-info">
										<?php echo Html::activeLabel($order, 'remain', ['class' => 'control-label']); ?>

										<div class="controls">
											<?php echo $form->field($order, 'remain')->textInput([
												'id'       => 'Order_remain',
												'readonly' => "readonly",
											])->label(false); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<div class="col-sm-6">
								<div class="control-group customer-info">
									<label class="control-label">Tổng tiền</label>

									<div class="controls">
										<?php echo $form->field($order, 'total')->textInput([
											'readonly' => 'readonly',
											'id'       => 'Order_total',
											'value'    => 0,
										])->label(false); ?>
										<input id="Order_total_hidden" name="Order[total]" type="hidden" value="0">
									</div>
								</div>
							</div>
						</div>
						<?php if (RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
							<div class="control-group customer-info col-xs-12">
								<div class="col-xs-4">
									<label class="control-label">Nhân viên</label>
								</div>
								<div class="col-xs-8">
									<div class="controls">
										<strong class="text-success"><?php echo $this->user->username ?></strong>
										-
										<strong class="text-danger"><?php
											$user = User::findOne(Yii::$app->user->id);
											if ($user == null) {
												echo "Unknown";
											} else {
												$brand = Branch::findOne([$user->branch]);
												if ($brand != null) {
													echo $brand->name;
												} else {
													echo "unknown";
												}
											}
											?></strong>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php if (RoleChecker::isAuth(MapController::className(), 'map-order')): ?>
							<div class="control-group customer-info">
								<div class="col-xs-4">
									<button class="btn btn-primary btn-order submit-button">Đặt vé</button>
								</div>
								<div class="col-xs-4">
									<button class="btn btn-success reset-button">Làm mới</button>
								</div>
								<div class="col-xs-4">
									<button class="btn btn-danger delete-button">Xóa</button>
								</div>
							</div>
						<?php endif; ?>
						<div class="captcha" style="display: none;">

							<div class="control-group customer-info">
								<div class="control-group col-xs-12" style="margin-top: 20px">
									<div class="col-xs-7">
										<label class="control-label">Mã xác nhận: </label>
									</div>
									<div class="col-xs-5">
										<span class="captcha-number" style="font-weight: bold;margin-left: 45px;"></span>
									</div>
								</div>
							</div>
							<div class="control-group customer-info col-xs-12" style="margin-top: 20px">
								<div class="col-xs-7">
									<label class="control-label">Nhập mã xác nhận:</label>
								</div>
								<div class="controls col-xs-1">
									<input maxlength="2" name="Order[captcha]" id="Order_captcha" type="text">
								</div>
								<div class="col-xs-2">

								</div>
							</div>
							<div class="control-group">
								<div class="error"></div>
							</div>
							<div class="control-group customer-info col-xs-12" style="margin-top: 20px">
								<div class="col-xs-6">
									<button class="btn btn-primary btn-order-confirm">Hoàn tất</button>
								</div>
								<div class="col-xs-6">
									<button class="btn btn-danger btn-order-back">Quay lại</button>
								</div>
							</div>
							<div class="order-loading" style="display: none;">
								Vui lòng chờ giây lát...
							</div>
						</div>
						<div class="success"></div>
					</div>
					<?php ActiveForm::end() ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<button class="btn btn-info" id="bootbox-confirm" style="display: none;">Notifier</button>
<script type="text/javascript">
	clear();
	notifier();
	$("#Order_discount").mask('000,000,000,000,000,000', {reverse: true});
	$("#Order_remain").mask('000,000,000,000,000,000', {reverse: true});
	var editOrder = false;
	var isEdited  = false;
	var need      = false;
	var locked    = false;
	var topY      = 140;
	var firstHtml = '<div class="span12" style="height: 0;min-height: 1px;"></div>';
	$(document).on('click', 'svg g', function(e) {
		if($(this).attr('data-number') != undefined) {
			if(locked) {
				alert('Vui lòng hoàn thành hoặc hủy bỏ đơn hàng trước khi thao tác tiếp!');
				e.preventDefault();
			} else if($("#svgMain").hasClass('zoomed')) {
				var th     = $(this);
				var booked = th.attr('data-booked'), price_id = th.attr('data-price-id'), row = th.attr('data-row'), floor = th.attr('data-floor'), number = th.attr('data-number');
				$(".seat-loading").fadeIn('fast');
				if($(this).attr('class') == 'active') {
					$.ajax({
						type   : 'post',
						cache  : false,
						url    : '<?php echo Url::to(['check-seat'])?>',
						data   : 'OrderSeat[price_id]=' + price_id + '&OrderSeat[row]=' + row + '&OrderSeat[number]=' + number + '&OrderSeat[floor]=' + floor + '&booked=' + booked + '&event_id=<?php echo $event->id?>',
						success: function(res) {
							res = jQuery.parseJSON(res);
							if(res.code == 1) {
								if(!editOrder && $(".seat-info").hasClass('old')) {
									$("#order-form input[type='text'], #order-form textarea,#Order_status").val('').removeAttr('readonly');
									$("#Order_total_hidden").val(0);
									$("#Order_number").val('');
									$("a.order-number").closest(".customer-info").fadeOut('fast');
									$("a.order-updated_date").closest(".customer-info").fadeOut('fast');
									$(".seat-info").removeClass('old').addClass('new').html(firstHtml + res.html);
									$(".btn-order").removeClass('edit-button').addClass('submit-button').text('Đặt vé');
									$(".user_info").text('<?php echo $this->user->username ?>');
									$(".branch_info").text('<?php echo $this->user->branch->name ?>');
								} else {
									$(".seat-info").append(res.html);
								}
								$("#Order_owner").val(res.owner);
								$('#print_tab').hide();
								$("#Order_discount").val(0);
								$('#Order_status').val(4);
								$('#Order_remain').val(0);
								isEdited  = editOrder;
								var total = $("#Order_total_hidden").val() * 1;
								$("#Order_total_hidden").val((total + res.price * 1));
								$('#Order_total').val($("#Order_total_hidden").val());
								var amount = $("#Order_total_hidden").val() - $("#Order_discount").val().replace(/,/g, "");
								$("#order-form input[type='text'], #order-form textarea, #order-form select").removeAttr('readonly');
								$("#Order_total").attr('readonly', true).val(numberFormat(amount));
								$(".success, .error").html('').hide();
								if(res.order_number != undefined && res.order_number != '') {
									need = true;
									$("#bootbox-confirm").click();
								}
							} else {
								alert('Lỗi không xác định');
							}
							$(".seat-loading").fadeOut('fast');
						}
					});
				} else {
					if(booked == '1') {
						$('.order-item').remove();
						$(this).attr('class', 'active');
						$.ajax({
							type   : 'post',
							cache  : false,
							url    : '<?php echo Url::to(['check-seat'])?>',
							data   : 'OrderSeat[price_id]=' + price_id + '&OrderSeat[row]=' + row + '&OrderSeat[number]=' + number + '&OrderSeat[floor]=' + floor + '&booked=' + booked + '&event_id=<?php echo $event->id?>',
							success: function(res) {
								res = jQuery.parseJSON(res);
								if(res.code == 1) {
									editOrder = false;
									$("svg").find("g[data-booked='0'][class='active']").attr('class', '');
									$("#Order_total_hidden").val(res.total);
									$('#Order_remain').val(numberFormat(res.remain));
									$("#Order_discount").val(numberFormat(res.discount));
									$("#Order_number").val(res.number);
									$('#print_tab').show();
									$('#print').attr("href", '<?php echo Url::to(['order/print'])?>?event_id=' + res.event_id + '&number=' + res.number);
									$("a.order-number").attr("href", '<?php echo Url::to(['order/view'])?>?event_id=' + res.event_id + '&number=' + res.number).text(pad(res.number, 7)).closest(".customer-info").fadeIn('fast');
									$("a.order-updated_date").html(res.updated_date).closest(".customer-info").fadeIn('fast');
									$(".seat-info").removeClass('new').addClass('old').html(firstHtml + res.html);
									$("#Order_customer_name").val(res.customer_name);
									$("#Order_customer_phone").val(res.customer_phone);
									$("#Order_customer_address").val(res.customer_address);
									$("#Order_discount_type").val(res.discount_type);
									$('#Order_status').val(res.status);
									$('#order-note').val(res.note);
									$("#Order_owner").val(res.owner);
									$(".user_info").text(res.username);
									$(".branch_info").text(res.branchname);
									if(res.discount_type == 0) {
										$("#Order_discount_type_0").attr('checked', true).next().addClass('checked');
										$("#Order_discount_type_1").removeAttr('checked').next().removeClass('checked');
										$("#Order_total").val(numberFormat(res.total - (res.discount * res.total / 100)));
									} else {
										$("#Order_discount_type_1").attr('checked', true).next().addClass('checked');
										$("#Order_discount_type_0").removeAttr('checked').next().removeClass('checked');
										$("#Order_total").val(numberFormat(res.total - res.discount));
									}
									$(".trash i").addClass('gray').removeClass('red');
									$(".btn-order").removeClass('submit-button').addClass('edit-button').text('Sửa vé');
									$("#order-form input[type='text'], #order-form textarea, #Order_status").attr('readonly', true);
									$(".success, .error").html('').hide();
								} else {
									alert('Lỗi không xác định');
								}
								$(".seat-loading").fadeOut('fast');
							}
						});
					} else {
						$('.order-item').each(function() {
							var id = row + '-' + number + '-' + floor;
							if($(this).attr('data-id') == id) {
								var price = $("#Order_total_hidden").val() - $(this).attr('data-price');
								$("#Order_total_hidden").val(price);
								$(this).remove();
								$(".submit-button").addClass('submit-button').removeClass('edit-button').text('Đặt vé');
								$("#order-form input[type='text'], #order-form textarea, #order-form select").removeAttr('readonly');
								$("#Order_total").attr('readonly', true).val(numberFormat(price));
								$(".success, .error").html('').hide();
							}
						});
						$(".seat-loading").fadeOut('fast');
					}
				}
			}
		}
	});
	$(document).on('keyup', "#order-form input,#order-form textarea", function() {
		isEdited = true;
	});
	$(document).on('change', "input[name='discount_type']", function() {
		$(".discount-type label").removeClass('checked');
		$("#Order_discount_type").val($(this).val());
		$("#Order_discount").val(0);
		$("#Order_total").val(numberFormat($("#Order_total_hidden").val()));
	});
	$(document).on('click', "#Order_discount", function() {
		$(this).select();
	});
	$(document).on('keyup', "#Order_discount", function() {
		var val   = $(this).val().replace(',', "") * 1,
			total = $("#Order_total_hidden").val() * 1;
		if($("#Order_discount_type_0").is(":checked")) {
			if(val <= 100) {
				total -= (val * total / 100);
			} else {
				alert('Chiết khấu không được lớn hơn 100%!');
				$(this).val(0);
			}
		} else {
			if(val <= total) {
				total -= val;
			} else {
				alert('Chiết khấu không được lớn hơn tổng tiền!');
				$(this).val(0);
			}
		}
		$("#Order_total").val(numberFormat(total));
	});
	$(document).on('keyup', "#Order_remain", function() {
		var val   = $(this).val().replace(/,/g, ""),
			total = $("#Order_total_hidden").val() * 1;
		if(val > total) {
			alert('Tiền nợ không thể lớn hơn tổng tiền');
			val = 0;
			//$('#Order_remain').val();
		}

		$("#Order_remain").val(numberFormat(val));
	});
	$(document).on('click', '.trash i.red', function() {
		if(editOrder || $(this).closest('.seat-info').hasClass('new')) {
			var item               = $(this).closest(".order-item"),
				Order_total_hidden = $("#Order_total_hidden"),
				total              = Order_total_hidden.val(),
				discount           = ($("#Order_discount").val().replace(/,/g, "") * 1),
				itemAttr           = item.attr('data-id').split('-');
			var conf               = confirm('Bạn có chắc muốn xóa bỏ ghế ' + itemAttr[0] + itemAttr[1] + ' không?');
			if(conf) {
				total -= item.attr('data-price');
				isEdited = editOrder;
				Order_total_hidden.val(total);
				$("#Order_total").val(numberFormat((total - discount)));

				$(this).closest(".order-item").remove();
				$("svg#container").find("g[data-row='" + itemAttr[0] + "'][data-number='" + itemAttr[1] + "'][data-floor='" + itemAttr[2] + "']").attr('class', '');
			}
		}
	});
	$(document).on('click', ".btn-order", function() {
		if($("#Order_owner").val() == 0) {
			alert('Bạn không được phép sửa đơn hàng này!');
			return false;
		}
		if($(this).hasClass('edit-button') && !editOrder) {
			editOrder = true;
			isEdited  = editOrder;
			$("#order-form input[type='text'], #order-form textarea,  #order-form select").removeAttr('readonly');
			$("#Order_total").attr('readonly', true);

			$(".btn-order").text('Hoàn thành').removeClass("edit-button").addClass("submit-button");
			$(".trash i").addClass('red').removeClass('gray');
		} else {
			if(editOrder && !isEdited) {
				clear();
				return false;
			}
			var customer_name = $("#Order_customer_name").val(), customer_phone = $("#Order_customer_phone").val(), customer_address = $("#Order_customer_address").val();
			var chosen        = $(".seat-info").find(".col-xs-12").size();
			if(chosen > 0) {
				if(customer_name != '' && customer_phone != '' && customer_address != '') {
					$(".captcha-number").text(random());
					$(this).closest('.customer-info').slideUp('fast');
					$("#order-form input[type='text'], #order-form textarea,#Order_status").attr('readonly', true);
					$("#Order_captcha").removeAttr('readonly');
					$(".captcha").slideDown('fast');
					$(".btn-order-confirm").closest('.customer-info').slideDown('fast');
					$('#container').parent().addClass('locked');
					locked = true;
				} else {
					alert('Vui lòng điền đầy đủ thông tin!');
				}
			} else {
				alert('Bạn chưa chọn ghế nào!');
			}
		}
		return false;
	});
	$(document).on('click', '.btn-order-confirm', function() {
		$(this).closest(".control-group.customer-info").fadeOut('fast').next().fadeIn('fast');
		var formData = $('#order-form').serializeArray();
		var i        = 0;
		$('.order-item').each(function() {
			formData.push({
				name : 'Order[seat][' + i + '][price-id]',
				value: $(this).attr('data-price-id')
			});
			formData.push({
				name : 'Order[seat][' + i + '][id]',
				value: $(this).attr('data-id')
			});
			i++;
		});
		var conf = $('#Order_captcha').val();
		if(conf == '' || conf != $('.captcha-number').text()) {
			$('.error').text('Mã xác nhận không đúng').show();
		} else {
			$('.error').hide();
			$.ajax({
				type   : 'post',
				cache  : false,
				url    : '<?php echo Url::to(['order/create'])?>',
				data   : formData,
				success: function(res) {
					res = jQuery.parseJSON(res);
					if(res.code == 0) {
						$.each(res.result, function() {
							var th = $(this)[0];
							$("svg#container").find("g[data-row='" + th.row + "'][data-number='" + th.number + "'][data-floor='" + th.floor + "']").attr('class', 'active').attr('data-booked', '1');
						});
						statistic();
						clear();
						$(".success").html('Đặt vé thành công! Mã đơn hàng <a href="<?php echo Url::to(['order/view'])?>?number=' + res.number + '&event_id=' + res.event_id + '"><b>' + res.number + '</b></a>').show();
					} else if(res.code == 1) {
						alert("Ghế bạn chọn đã có người đặt rồi!");
						need = true;
						$("#bootbox-confirm").click();
						clear();
					} else {
						alert(res.message + " Vì vậy bạn không thể đặt hàng!");
						clear();
					}
				},
				error  : function(response) {
					alert(response.responseText);
					clear();
				}
			});
		}
		return false;
	});
	$(document).on('click', '.btn-order-back', function() {
		$(".captcha").slideUp('fast').prev().slideDown('fast');
		$("#Order_captcha").val('');
		$("#order-form input[type='text'], #order-form textarea, #Order_status").removeAttr('readonly');
		$("#Order_total").attr('readonly', true);
		$('#container').parent().removeClass('locked');
		locked = false;
		return false;
	});
	$(document).on('click', '.reset-button', function() {
		clear();
		return false;
	});
	$(document).on('click', '.delete-button', function() {
		if($("#Order_owner").val() == 0) {
			alert('Bạn không được phép sửa đơn hàng này!');
			return false;
		}
		var seat_info      = $(".seat-info"),
			Order_number   = $(".order-number"),
			Order_event_id = $("#Order_event_id");
		if(seat_info.find(".col-xs-12").size() > 0) {
			if(seat_info.hasClass('old')) {
				var conf = confirm('Bạn có chắc muốn xóa bỏ đơn hàng ' + pad(Order_number.text(), 7) + ' không? Mọi lịch sử về đơn hàng này cũng sẽ bị xóa bỏ.');
				if(conf) {
					$.ajax({
						type   : "POST",
						cache  : false,
						url    : '<?php echo Url::to(['order/delete'])?>',
						data   : 'number=' + Order_number.text() + '&event_id=' + Order_event_id.val(),
						success: function(response) {
							response = jQuery.parseJSON(response);
							if(response.code == 0) {
								alert('Xóa đơn hàng thành công!');
								$.each(response.return, function(key, value) {
									$("svg#container").find("g[data-price-id='" + value.price_id + "'][data-row='" + value.row + "'][data-number='" + value.number + "'][data-floor='" + value.floor + "']").attr('class', '').attr('data-booked', 0);
								});
								clear();
							} else {
								alert('Lỗi không xác định!');
							}
						}
					});
				}
			} else {
				clear();
			}
		} else {
			alert('Chưa có thông tin nào để xóa!');
		}
		return false;
	});
	$(document).on('click', '.btn-statistic', function() {
		if($(this).hasClass('down')) {
			$(this).removeClass('down').addClass('up').html('<i class="fa fa-angle-up"></i> Thống kê');
			statistic();
			seatStatistic();
			$(".row-fluid.statistic").slideDown('slow');
			$(".seats-list").slideDown("slow");
			$(".price-list").slideUp("slow");
			topY += 250;
		} else {
			$(this).addClass('down').removeClass('up').html('<i class="fa fa-angle-down"></i> Thống kê');
			$(".row-fluid.statistic").slideUp('slow');
			$(".seats-list").slideUp("slow");
			$(".price-list").slideDown("slow");
			topY -= 250;
		}
	});
	$("#bootbox-confirm").on(ace.click_event, function() {
		if(need) {
			bootbox.dialog({
				'message': "Dữ liệu của bạn đã cũ! Làm mới ngay.",
				buttons  : {
					"success": {
						"label"    : "Làm mới ngay",
						"className": "btn-small btn-primary",
						"callback" : function() {
							window.location.reload(true);
						}
					}
				}
			});
		} else {
			bootbox.dialog({
				'message': "Dữ liệu của bạn có thể đã cũ! Làm mới ngay, hoặc bỏ qua trong 5 phút tiếp theo.",
				buttons  : {
					"success": {
						"label"    : "Làm mới ngay",
						"className": "btn-small btn-primary",
						"callback" : function() {
							window.location.reload(true);
						}
					},
					"danger" : {
						"label"    : "Tiếp tục bỏ qua",
						"className": "btn-small",
						"callback" : function() {
							notifier();
						}
					}
				}
			});
		}
	});

	function statistic() {
		$.ajax({
			type   : "POST",
			cache  : false,
			url    : '<?php echo Url::to([
				'map/statistic',
				'id' => $event->id,
			])?>',
			success: function(response) {
				$(".row-fluid.statistic").html(response);
			}
		});
	}
	function seatStatistic() {
		$.ajax({
			type   : "POST",
			cache  : false,
			url    : '<?php echo Url::to([
				'map/seat-statistic',
				'id' => $event->id,
			])?>',
			success: function(response) {
				$(".seats-list").html(response);
			}
		});
	}
	function clear() {
		$(".captcha").slideUp('fast').prev().slideDown('fast');
		$("#Order_captcha").val('');
		$(".seat-info").removeClass('old').addClass('new').html(firstHtml);
		$("svg#container").find("g[data-booked='0'][class='active']").attr('class', '');
		$("#order-form input[type='text'], #order-form textarea,#Order_status").val('').attr('readonly', true);
		$("#Order_total_hidden").val(0);
		$("#Order_number").val('');
		$("a.order-number").closest(".customer-info").fadeOut('fast');
		$("a.order-updated_date").closest(".customer-info").fadeOut('fast');
		$(".btn-order").addClass('submit-button').removeClass('edit-button').text('Đặt vé');
		$("#Order_total").attr('readonly', true).val(0);
		$('#Order_customer_phone').val('');
		$('#Order_customer_address').val('');
		$("#Order_customer_name").val('');
		$(".success, .error").html('').hide();
		$("#Order_discount_type").val(0);
		$("#Order_discount_type_0").attr('checked', true).next().addClass('checked');
		$("#Order_discount_type_1").removeAttr('checked').next().removeClass('checked');
		$(".order-loading").fadeOut('fast');
		$(".seat-loading").fadeOut('fast');
		$('#container').parent().removeClass('locked');

		editOrder = false;
		locked    = false;
		isEdited  = false;
	}
	function random() {
		var random = Math.floor(Math.random() * (99 - 1 + 1) + 1);
		if(random < 10) {
			return '0' + random;
		}
		return random;
	}
	function notifier() {
		setTimeout(function() {
			$("#bootbox-confirm").click();
		}, 300000);
	}
</script>