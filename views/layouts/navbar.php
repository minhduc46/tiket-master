<?php
use app\controllers\MapController;
use app\models\Order;
use kartik\grid\GridView;
use navatech\role\helpers\RoleChecker;
use yii\helpers\Html;
use yii\helpers\Url;

$controller = Yii::$app->controller->id;
$action     = Yii::$app->controller->action->id;
if($controller == 'map' && $action == 'view') {
	$event           = Yii::$app->request->queryParams['id'];
	$chairTimeOut    = Order::getAllChairTimeOut($event);
	$arrChairTimeOut = Order::getAllArrChairTimeOut($event);
	$Owe             = Order::getAllOwe($event);
	$cx              = Order::getAllCX($event);
	$sumOwe          = Order::getSumOwe($event);
}
?>
<style>
	.style {
		width: 617px;
		height: 282px;
		left: 514px;
		right: auto;
		top: 14px;
	}
</style>
<div id="navbar" class="navbar navbar-default">
	<script type="text/javascript">
		try {
			ace.settings.check('navbar', 'fixed')
		} catch(e) {
		}
	</script>

	<div class="navbar-container" id="navbar-container">
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<div class="navbar-header pull-left">
			<a href="<?= Url::home() ?>" class="navbar-brand">
				<small>
					<i class="fa fa-leaf"></i>
					Quản lý đặt vé
				</small>
			</a>
		</div>

		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<?php if($controller == 'map' && $action == 'view'&& RoleChecker::isAuth(MapController::className(),'view') && RoleChecker::isAuth(MapController::className(),'map-order')): ?>
					<li class="grey dropdown-modal">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false" about="<?= $chairTimeOut['id'] ?>" id="timeOut">
							<i class="ace-icon fa fa-clock-o"></i>
							<span class="badge badge-grey"><?= $chairTimeOut['number'] ?></span>
						</a>

					</li>

					<li class="green dropdown-modal">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false" id="xk">
							<i class="ace-icon fa fa-university"></i>
							<span class="badge badge-success"><?= $cx['number'] ?></span>
						</a>

					</li>
					<li class="purple dropdown-modal">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false" id="owe">
							<i class="ace-icon fa fa-paypal"></i>
							<span class="badge badge-important"><?= $Owe['number'] ?></span>
						</a>

					</li>
					<li class="red dropdown-modal">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false" id="SumOwe">
							<i class="ace-icon fa fa-money"></i>
							<span class="badge badge-success"><?php if($sumOwe[0]['number']!='0₫')
							    echo  1;
							    else
							        echo 0;
							    ?></span>
						</a>
					</li>
				<?php endif; ?>
				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<span class="user-info">
									<small>Chào,</small>
							<?= \app\models\User::findOne(Yii::$app->user->id)->username ?>
								</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="<?= Url::to(['/user/settings/account']) ?>">
								<i class="ace-icon fa fa-user"></i>
								Profile
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a data-method="post" href="<?= Url::to(['/user/logout']) ?>">
								<i class="ace-icon fa fa-power-off"></i>
								Logout
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div><!-- /.navbar-container -->
</div>


