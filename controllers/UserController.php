<?php
namespace app\controllers;

use app\components\Controller;
use app\models\LoginForm;
use dektrium\user\controllers\AdminController;
use dektrium\user\controllers\SecurityController;
use navatech\role\filters\RoleFilter;

use Yii;
use yii\filters\VerbFilter;

class UserController extends SecurityController {
	public $layout='@app/views/layouts/login';
}
