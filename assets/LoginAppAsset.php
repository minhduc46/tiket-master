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
class LoginAppAsset extends AssetBundle {

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
		'css/ace.min.css',
		'css/ace-rtl.min.css'
	];

	public $js         = [

	];

	public $jsOptions  = ['position' => View::POS_HEAD];
}
