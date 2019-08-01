<?php

namespace frontend\controllers;


use common\models\Tasks;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class EditTableController extends Controller
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
                    'change' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Изменяем у выбранной модели значение
     * @param $model
     * @return null
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionChange($model)
    {

        $r = ['output' => '', 'message' => ''];

        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (\Yii::$app->request->isPost) {
            $attribute = \Yii::$app->request->post('name');
            $value = \Yii::$app->request->post('value');
            $id = \Yii::$app->request->post('pk');

            if ($attribute && $value && $id) {

                // Проверяем есть ли доступ
                $accessModels = [Tasks::class];

                if (!in_array($model, $accessModels)) {
                    throw new ForbiddenHttpException('нет доступа');
                }

                $m = $model::findOne($id);

                if (!$m) {
                    throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
                }

                $m->$attribute = $value;

                if ($m->validate()) {
                    $m->save();
                    $r['output'] = $m->$attribute;
                } else {
                    $r['message'] = $m->errors;
                }

            } else {
                $r['message'] = 'отсуствуют обязательные параметры';
            }

            return $r;

        }

        return $r;
    }
}