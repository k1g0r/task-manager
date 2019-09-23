<?php

use common\models\Clients;
use common\models\Tasks;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Отчет по задачам клиента: ' . $client->name;

// Собираем данные в массив перед выводом
$data = [];

Pjax::begin(['id' => 'report-for-client', 'enablePushState' => false]);

// формируем форму для фильтарциии отчеты (клиент, статус, поля для вывода)
echo Html::beginForm(['stats/report-for-client'], 'get', ['data-pjax' => 'report-for-client']);

echo Html::dropDownList('client_id', '', Clients::getMyClientNames(), ['class' => 'form-control']);

echo Html::dropDownList('status', $status ?? Tasks::STATUS_WAIT_PAYMENT, Tasks::getStatuses(), ['class' => 'form-control']);

echo Html::submitButton('Отправить', ['class' => 'btn btn-block btn-info']);

echo Html::endForm();

// формируем таблицу для вывода отчета
$trs = '';
if ($models) {
    $full = [
        'time' => 0,
        'price' => 0
    ];
    foreach ($models as $project) {
        if ($project->tasks) {
            foreach ($project->tasks as $task) {
                $timeForTotal = $task->getTimeForTotal() ?? $task->time;
                $full['price'] += $task->total;
                $full['time'] += $timeForTotal;
                $trs .= "<tr>
                            <td>{$task->id}</td>
                            <td>{$project->name}</td>
                            <td>{$task->name}</td>
                            <td>{$task->hoursPrice} руб.</td>
                            <td>{$task->getTimeStr($timeForTotal)}</td>
                            <td>{$task->total} руб.</td>
                        </tr>";
            }
        }
    }

    // Итог
    $fullTime = Tasks::timeStr($full['time']);
    $fullPrice = $full['price'];
    $trs .= "<tr style='font-weight: bold'><td colspan='5' align='right'>$fullTime</td><td>$fullPrice руб.</td></tr>";
}

?>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Проект</th>
        <th>Название</th>
        <th>руб/час</th>
        <th>Время</th>
        <th>Сумма</th>
    </tr>
    </thead>
    <tbody>
        <?= $trs ?>
    </tbody>
</table>

<?php Pjax::end();
