<?php
use app\controllers\AgencyController;
use app\controllers\BranchController;
use app\controllers\EventController;
use app\controllers\OrderController;
use app\controllers\StatisticController;
use app\controllers\user\AdminController;
use navatech\role\controllers\DefaultController;
use navatech\role\helpers\RoleChecker;
use yii\widgets\Menu;

?>
<script type="text/javascript">
	try {
		ace.settings.check('main-container', 'fixed')
	} catch(e) {
	}
</script>
<div id="sidebar" class="sidebar responsive menu-min">
	<script type="text/javascript">
		try {
			ace.settings.check('sidebar', 'fixed')
		} catch(e) {
		}
	</script>

	<div class="sidebar-shortcuts" id="sidebar-shortcuts">
		<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
			<button class="btn btn-success">
				<i class="ace-icon fa fa-signal"></i>
			</button>

			<button class="btn btn-info">
				<i class="ace-icon fa fa-pencil"></i>
			</button>

			<button class="btn btn-warning">
				<i class="acmenu-icon fa fa-picture-oe-icon fa fa-users"></i>
			</button>

			<button class="btn btn-danger">
				<i class="ace-icon fa fa-cogs"></i>
			</button>
		</div>

		<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
			<span class="btn btn-success"></span>

			<span class="btn btn-info"></span>

			<span class="btn btn-warning"></span>

			<span class="btn btn-danger"></span>
		</div>
	</div>
	<?= Menu::widget([
		'options'         => ['class' => 'nav nav-list'],
		'submenuTemplate' => '<ul class="submenu">{items}</ul>',
		'activeCssClass'  => 'active',
		'activateParents' => true,
		'linkTemplate'    => '<a href="{url}">{label}</a><b class="arrow"></b>',
		'encodeLabels'    => false,
		'items'           => [
			[
				'url'   => ['/'],
				'label' => '<i class="menu-icon fa fa-tachometer"></i><span class="menu-text"> Bảng điều khiển </span>',
			],
			[
				'url'      => '#',
				'visible'  => RoleChecker::isAuth(EventController::className()),
				'label'    => '<i class="menu-icon fa fa-list"></i><span class="menu-text"> Chương trình </span><b class="arrow fa fa-angle-down"></b>',
				'template' => '<a href="{url}" class="dropdown-toggle">{label}</a><b class="arrow"></b>',
				'items'    => [
					[
						'visible' => RoleChecker::isAuth(EventController::className(), 'create'),
						'url'     => ['/event/create'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Thêm mới chương trình',
					],
					[
						'visible' => RoleChecker::isAuth(EventController::className(), 'index'),
						'url'     => ['/event/index'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Danh sách chương trình',
					],
				],
			],
			[
				'url'      => '#',
				'label'    => '<i class="menu-icon fa fa-star"></i><span class="menu-text"> Đơn hàng </span><b class="arrow fa fa-angle-down"></b>',
				'template' => '<a href="{url}" class="dropdown-toggle">{label}</a><b class="arrow"></b>',
				'visible'  => RoleChecker::isAuth(OrderController::className()) || RoleChecker::isAuth(StatisticController::className()),
				'items'    => [
					[
						'visible' => RoleChecker::isAuth(OrderController::className(), 'index'),
						'url'     => ['/order/index'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Danh sách đơn hàng',
					],
					[
						'visible' => RoleChecker::isAuth(StatisticController::className(), 'index'),
						'url'     => ['/statistic/index'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Thống kê',
					],
				],
			],
			[
				'url'      => '#',
				'visible'  => RoleChecker::isAuth(BranchController::className()),
				'label'    => '<i class="menu-icon fa fa-user-md"></i><span class="menu-text"> Đơn vị bán hàng </span><b class="arrow fa fa-angle-down"></b>',
				'template' => '<a href="{url}" class="dropdown-toggle">{label}</a><b class="arrow"></b>',
				'items'    => [
					[
						'url'     => ['/branch/create'],
						'visible' => RoleChecker::isAuth(BranchController::className(), 'create'),
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Thêm mới đơn vị',
					],
					[
						'visible' => RoleChecker::isAuth(BranchController::className(), 'index'),
						'url'     => ['/branch/index'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Danh sách đơn vị',
					],
				],
			],
			[
				'url'      => '#',
				'visible'  => RoleChecker::isAuth(AgencyController::className()),
				'label'    => '<i class="menu-icon fa fa-users"></i><span class="menu-text"> Đại lý </span><b class="arrow fa fa-angle-down"></b>',
				'template' => '<a href="{url}" class="dropdown-toggle">{label}</a><b class="arrow"></b>',
				'items'    => [
					[
						'visible' => RoleChecker::isAuth(AgencyController::className(), 'create'),
						'url'     => ['/agency/create'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Thêm mới đại lý',
					],
					[
						'visible' => RoleChecker::isAuth(AgencyController::className(), 'index'),
						'url'     => ['/agency/index'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Danh sách đại lý',
					],
				],
			],
			[
				'url'      => '#',
				'label'    => '<i class="menu-icon fa fa-database"></i><span class="menu-text"> Tài khoản </span><b class="arrow fa fa-angle-down"></b>',
				'visible'  => RoleChecker::isAuth(AdminController::className()) || RoleChecker::isAuth(DefaultController::className()),
				'template' => '<a href="{url}" class="dropdown-toggle">{label}</a><b class="arrow"></b>',
				'items'    => [
					[
						'visible' => RoleChecker::isAuth('user/admin', 'create'),
						'url'     => ['/user/admin/create'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Thêm mới tài khoản',
					],
					[
						'visible' => RoleChecker::isAuth('user/admin', 'index'),
						'url'     => ['/user/admin/index'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Danh sách tài khoản',
					],
					[
						'visible' => RoleChecker::isAuth('role/default', 'index'),
						'url'     => ['/role/default/index'],
						'label'   => '<i class="menu-icon fa fa-caret-right"></i> Quyền hạn tài khoản',
					],
				],
			],
		],
	]) ?>
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div>

	<script type="text/javascript">
		try {
			<?php if (!(Yii::$app->controller->id == 'map' && Yii::$app->controller->action->id == 'view')) : ?>
			ace.settings.check('sidebar', 'collapsed');
			<?php endif; ?>
		} catch(e) {
		}
	</script>
</div>