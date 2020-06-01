<?php

namespace frontend\controllers;

use common\models\Clients;
use common\models\Tasks;
use frontend\models\ProjectsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * ClientsController implements the CRUD actions for Clients model.
 */
class StatsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Clients models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Выводим текстовый отчет по задачам для указанного клиента
     * @param $client_id
     * @param int $status
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionReportForClient($client_id, $status = Tasks::STATUS_WAIT_PAYMENT)
    {
        $client = Clients::findOne(['id' => $client_id]) ;

        if (!$client || !in_array($client->id, Clients::getMyClientIds())) {
            throw new ForbiddenHttpException('нет доступа');
        }

        $searchModel = new ProjectsSearch();
        $searchModel->client_id = $client_id;

        $models = $searchModel
            ->getQuery()
            ->with([
                'tasks' => function ($query) use ($status) {
                    if ($status) {
                        $query->andWhere(['status' => $status]);
                    }
                }
            ])
            ->all();

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('report-for-client', [
                'client' => $client,
                'models' => $models,
                'status' => $status
            ]);
        } else {
            return $this->render('report-for-client', [
                'client' => $client,
                'models' => $models,
                'status' => $status
            ]);
        }

    }
    /**
     * Выводим отчет по выплаченным суммам по месецам за определенный год
     * @param $year
     * @return mixed
     */
    public function actionReportOnYear($year = null)
    {
        // получаем нужный год
        $year = $year ? $year : date("Y");
        // создаем временной интервал
        $dateInterval = [
            'from' => strtotime('01.01.' . $year),
            'to' => strtotime('31.12.' . $year),
        ];

        // Разбиваем в массив по месецам
        $wait = $this->tasksOnMonth($dateInterval, 'waiting_at');
        $payment = $this->tasksOnMonth($dateInterval, 'payment_at');

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('report-on-year', [
                'wait' => $wait,
                'payment' => $payment,
            ]);
        } else {
            return $this->render('report-on-year', [
                'wait' => $wait,
                'payment' => $payment,
            ]);
        }

    }

    /**
     * Разбиваем массив по месецам
     * @param $dateInterval
     * @param $fieldDate
     * @return array
     */
    protected function tasksOnMonth($dateInterval, $fieldDate)
    {
        // Выбираем все задачи за этот период (ожидание оплаты и оплаченные)
        $tasks = Tasks::find()
            ->where(['>', $fieldDate, $dateInterval['from']])
            ->andWhere(['<', $fieldDate, $dateInterval['to']])
            ->all();

        $r = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
        ];
        if ($tasks) {
            foreach ($tasks as $task) {
                $m = intval(date("m", $task->$fieldDate)) - 1;
                $r[$m] += $task->total;
            }
        }

        return $r;
    }

}
