<?php
/**
 * @var View    $this
 * @var Event[] $events
 */
use app\components\View;
use app\models\Event;
use yii\helpers\Url;

$this->title                   = 'Bảng điều khiển';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
	<div class="alert alert-block alert-success">
		<i class="ace-icon fa fa-check green"></i>
		Chào mừng bạn quay trở lại!
		<?php if ($events != null): ?>
			Dưới đây là các chương trình bán vé bạn có thể truy cập:
		<?php else : ?>
			Hiện không có chương trình bán vé nào đang diễn ra
		<?php endif; ?>
	</div>
	<div class="list-event text-center">
		<?php foreach ($events as $event): ?>
			<a href="<?= Url::to([
				'/map/view',
				'id' => $event->id,
			]) ?>">
				<div class="alert alert-info" role="alert">
					<?= $event->name ?>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</div>
