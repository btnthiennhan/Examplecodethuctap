<?php

namespace app\controllers;

use Yii;
use app\models\dao\Product;
use app\models\ProductForm;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class ProductController extends Controller
{
    public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'only' => ['', '', 'create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'], // @ nghĩa là chỉ người đã đăng nhập
                ],
            ],
        ],
    ];
}
    
public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new ProductForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $savedProduct = $model->save();
            if ($savedProduct) {
                return $this->redirect(['view', 'id' => $savedProduct->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $product = $this->findModel($id);
        $model = new ProductForm();
        $model->loadFromProduct($product);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->id = $id;
            $savedProduct = $model->save();
            if ($savedProduct) {
                return $this->redirect(['view', 'id' => $savedProduct->id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSuggest($term = '') {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    if (empty($term)) {
        return [];
    }

    $products = Product::find()
        ->select(['id', 'name'])
        ->where(['like', 'name', $term])
        ->limit(10)
        ->asArray()
        ->all();

    return array_map(function($p) {
        return ['id' => $p['id'], 'label' => $p['name']];
    }, $products);
}

}
