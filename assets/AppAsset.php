<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class AppAsset extends AssetBundle {

	public $basePath  = '@webroot';

	public $baseUrl   = '@web';

	public $depends    = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];

	public $css        = [
		'font-awesome/4.6.1/css/font-awesome.min.css',
		'fonts/fonts.googleapis.com.css',
		'css/booking.css',
		'css/ace.min.css',
		'css/bootstrap-timepicker.min.css',
		'css/style.css',
	];

	public $js         = [
		'js/booking.js',
		'js/jquery.mask.min.js',
		'js/ace-extra.min.js',
		'js/html5shiv.min.js',
		'js/respond.min.js',
        'js/jquery.timepicker.js',
		'js/jquery-ui.custom.min.js',
		'js/jquery.ui.touch-punch.min.js',
		'js/ace-elements.min.js',
		'js/ace.min.js',
		'js/fuelux.wizard.min.js',
		'js/jquery.validate.min.js',
		'js/additional-methods.min.js',
		'js/bootstrap-timepicker.min.js',
		'js/bootbox.min.js',
		'js/manager.js',
		'js/app.js',
	];

	public $jsOptions  = ['position' => View::POS_HEAD];
}
