<?php
/* @var $this \yii\web\View */
/* @var $content string */
use app\assets\AppAsset;
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

<div class="main-container" id="main-container">
	<script type="text/javascript">
		try {
			ace.settings.check('main-container', 'fixed')
		} catch(e) {
		}
	</script>

	<div class="main-content">
		<div class="main-content-inner">
			<div class="space-32"></div>
				<script type="text/javascript">
					try {
						ace.settings.check('breadcrumbs', 'fixed')
					} catch(e) {
					}
				</script>

			</div>
			<div class="page-content">
				<div class="row">
					<div class="col-xs-12">
						<?= $content ?>
					</div><!-- /.col -->
				</div><!-- /.row -->
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
<?php $this->endBody() ?>
<script>
	$(document).on("click", ".btn-back", function() {
		window.history.back();
	});
</script>
</body>
</html>
<?php $this->endPage() ?>
