<?php
/* @var $this \yii\web\View */
/* @var $content string */
use app\assets\AppAsset;
use app\widgets\ListOrder;
use app\widgets\Navigation;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body class="no-skin">
<?php $this->beginBody() ?>
<?= $this->render('navbar') ?>
<div class="main-container" id="main-container">
	<script type="text/javascript">
		try {
			ace.settings.check('main-container', 'fixed')
		} catch(e) {
		}
	</script>
	<?= $this->render('sidebar') ?>
	<div class="main-content">
		<div class="main-content-inner">
			<div class="breadcrumbs" id="breadcrumbs">
				<script type="text/javascript">
					try {
						ace.settings.check('breadcrumbs', 'fixed')
					} catch(e) {
					}
				</script>
				<?= Breadcrumbs::widget([
					'encodeLabels' => false,
					'homeLink'     => [
						'label' => '<i class="ace-icon fa fa-home home-icon"></i>Trang chủ',
						'url'   => Yii::$app->homeUrl,
					],
					'links'        => array_key_exists('breadcrumbs', $this->params) ? $this->params['breadcrumbs'] : [],
				]) ?>
			</div>
			<div class="page-content">
				<?= ListOrder::widget() ?>
				<?= $content ?>
			</div><!-- /.page-content -->
		</div>
	</div><!-- /.main-content -->
	<div class="footer">
		<div class="footer-inner">
			<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Powered by <a href="http://navatech.vn"><?= file_get_contents(Yii::getAlias('@app/web/images/logo_navatech.svg')) ?></a></span>
						</span>
			</div>
		</div>
	</div>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
</div><!-- /.main-container -->
<?=Navigation::widget()?>
<?php $this->endBody() ?>
<script>
	$(document).on("click", ".btn-back", function() {
		window.history.back();
	});
</script>
</body>
</html>
<?php $this->endPage() ?>
