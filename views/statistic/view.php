<?php
use app\assets\AppAsset;
use app\models\Branch;
use app\models\Event;
use kartik\datecontrol\DateControl;
use kartik\field\FieldRange;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => 'Bản đồ', 'url' => Url::to(['map/view','id'=>$_GET['id'] ])];

$this->params['breadcrumbs'][] = 'Thống kê';
$this->title = 'Thống kê';
$bundle = AppAsset::register($this);
$a      = 111;
?>
<link rel="stylesheet" href="<?= $bundle->baseUrl ?>/web/css/map-style.css">

</link>
<div class="page-content">
	<div class="row-fluid">
		<div class="span12">
			<?php $form = ActiveForm::begin([
				'id' => 'statistic-form',
			]) ?>
			<div class="page-header position-relative">
				<h3>Thống kê chương trình "<?php echo Event::findOne($_GET['id'])->name ?>"</h3>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<table class="table table-bordered table-hover">
						<tbody>
						<tr>
							<td><strong>Giá vé</strong></td>
							<?php foreach($seats as $seat): ?>
								<td>
									<span class="seat-color" style="background: <?php echo $seat['color'] ?>"></span><?php echo $seat['price'] ?>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<td><strong>Chưa bán</strong></td>
							<?php foreach($seats as $seat): ?>
								<td>
									<?php echo $seat['instock'] ?>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<td><strong>Đã bán</strong></td>
							<?php foreach($seats as $seat): ?>
								<td>
									<?php echo $seat['sold'] ?>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<td><strong>Tổng số</strong></td>
							<?php foreach($seats as $seat): ?>
								<td>
									<strong><?php echo $seat['total'] ?></strong>
								</td>
							<?php endforeach; ?>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<hr>
			<div class="row-fluid">
				<div class="col-xs-12">

					<div class="control-group">
						<div class="controls">

							<div class="input-append col-xs-8">
								<?= FieldRange::widget([
									'form'           => $form,
									'model'          => $order,
									'label'          => '',
									'attribute1'     => 'start_date',
									'attribute2'     => 'end_date',
									'type'           => FieldRange::INPUT_WIDGET,
									'widgetClass'    => DateControl::classname(),
									'widgetOptions1' => [
										'displayFormat' => 'php:d-m-Y',
										'saveFormat'    => 'php:Y-m-d',
										'options'       => [
											'pluginOptions' => ['autoclose' => true,],
										],
									],
									'widgetOptions2' => [
										'displayFormat' => 'php:d-m-Y',
										'saveFormat'    => 'php:Y-m-d',
										'options'       => [
											'pluginOptions' => ['autoclose' => true,],
										],
									],
								]); ?>

								<span class="add-on">
							<i class="icon-calendar"></i>
						</span>
							</div>

							<div class="input-append">

								<span class="add-on">
							<i class="icon-calendar"></i>
						</span>
							</div>

							<div class="input-append col-xs-3">
								<span>Của</span>
								<?php echo $form->field($order, 'branch_id')->widget(Select2::className(), ['data' => ArrayHelper::map(Branch::find()->asArray()->all(), 'id', 'name')])->label(false) ?>
							</div>
							<div class="col-xs-1">
								<br>
								<button type="submit" class="btn btn-small btn-primary btn-submit xemchitiet">
									<i class="icon-pencil"></i>Xem chi tiết
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="col-xs-12">
					<div id="sample-table-2_wrapper" class="dataTables_wrapper" role="grid">
						<?= GridView::widget([
							'dataProvider' => $model,
							'bordered'     => true,
							'responsive'   => true,
							'rowOptions'   => [
								'style' => [
									'text-align' => 'center',
								],
							],
							'toolbar'      => [
								'{export}',
								'{toggleData}',
							],
							'columns'      => [
								['class' => 'yii\grid\SerialColumn'],
								[
									'attribute' => 'user_id',
									'header'    => 'Nhân viên bán vé',
									'value'     => function ($data) {
										return $data["user"]->username;
									},
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Số vé',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										return number_format($model->seat_count);
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'attribute' => 'discount',
									'header'    => 'Chiết khấu',
									'value'     => function ($data) {
										return number_format($data->discount);
									},
								],
								[

									'header'    => 'Tổng tiền',
									'value'     => function ($data) {
										return number_format($data->grandTotal).'đ';
									},
								],
								[
									'header'    => 'Tổng nợ',
									'value'     => function ($data) {
										return number_format($data->oweTotal).'đ';
									},
								]
							],
							'panel'        => [
								'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách thống kê') . '</i>',
								'footer'  => false,
							],
						]) ?>
					</div>

				</div>
			</div>
			<?php ActiveForm::end() ?>
		</div>
	</div>

	<?php
	$fromdate = strtotime($order->start_date);
	$todate   = strtotime($order->end_date);

	$ql_seat  = $hp_seat = $ib_seat = $ql_total = $hp_total = $ib_total = 0;
	$arr_ds   = array();
	for($i = $fromdate; $i <= $todate; $i += 86400) {
		$date     = date('Y-m-d', $i);

		$arr_ds[] = "['Ngày " . date('d', $i) . "'," . $order->getCountChart($_GET['id'], $date, 1)/1000000 . "," . $order->getCountChart($_GET['id'], $date, 0). "," . $order->getCountChart($_GET['id'], $date, 2)/1000 . "]";
	}
	$dataString = join(',', $arr_ds);
	?>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("visualization", "1", {packages: ["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data_div = google.visualization.arrayToDataTable([
				['Ngày', 'Tổng tiền vé', 'Tổng số vé','Tổng nợ'],
				<?php echo $dataString ?>
			]);

			var options_div = {
				title : 'Bảng biểu',
				vAxes : {
					0: {
						logScale: false,
						maxValue: 4,
						title: 'Tỷ lệ: 1/1,000,000'
					},
					1: {
						logScale: false,
						maxValue: 4,
						title: 'Tỷ lệ 1/1,000'
					}

				},
				series: {
					0: {targetAxisIndex: 0},
					1: {targetAxisIndex: 1},
					2: {targetAxisIndex: 1},
				}
			};

			var chart_div = new google.visualization.ComboChart(document.getElementById('chart_div'));
			chart_div.draw(data_div, options_div);
		}

		google.load("visualization", "1", {packages: ["table"]});

	</script>
	<div style="margin:20px 0px;background:#fff;">
		<span style="font-weight: bold;margin: 20px 0px 20px 180px;font-size: 15px;color: black;">Thống kê</span>

		<div id="table_div" style="width: 95%;margin:0px auto;"></div>
	</div>
	<div id="chart_div" style="height: 500px;"></div>

	<script>
		$(document).ready(function() {

			$('textarea').remove();
		});

	</script>

</div>
