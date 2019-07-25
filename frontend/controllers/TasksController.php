<?php

namespace frontend\controllers;

use Yii;
use common\models\Tasks;
use frontend\models\TasksSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class TasksController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tasks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TasksSearch();
        $count = [
            Tasks::STATUS_ACTIVE => $searchModel->getQuery(['TasksSearch' => ['status' => Tasks::STATUS_ACTIVE]])->count(),
            Tasks::STATUS_DELETED => $searchModel->getQuery(['TasksSearch' => ['status' => Tasks::STATUS_DELETED]])->count(),
            Tasks::STATUS_DISABLED => $searchModel->getQuery(['TasksSearch' => ['status' => Tasks::STATUS_DISABLED]])->count(),
            Tasks::STATUS_MODERATE => $searchModel->getQuery(['TasksSearch' => ['status' => Tasks::STATUS_MODERATE]])->count(),
            Tasks::STATUS_WAIT => $searchModel->getQuery(['TasksSearch' => ['status' => Tasks::STATUS_WAIT]])->count(),
            Tasks::STATUS_WAIT_PAYMENT => $searchModel->getQuery(['TasksSearch' => ['status' => Tasks::STATUS_WAIT_PAYMENT]])->count(),
            Tasks::STATUS_PAYMENT => $searchModel->getQuery(['TasksSearch' => ['status' => Tasks::STATUS_PAYMENT]])->count(),
        ];

        return $this->render('index', [
            'count' => $count,
        ]);
    }

    public function actionAjaxIndex()
    {
        if (!Yii::$app->request->isAjax) {
            return;
        }
        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 10]);

        return $this->renderAjax('_indexAjax', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tasks model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tasks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tasks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tasks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tasks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tasks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tasks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
