<?php
/* @var $this \yii\web\View */
/* @var $content string */
use app\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">


</head>
<body>
<?php $this->beginBody() ?>
	<?=$content?>
<?php $this->endBody() ?>

</body>
<script>

</script>
</html>

