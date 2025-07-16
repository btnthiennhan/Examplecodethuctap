<?php

namespace app\controllers;

use app\models\CategoryForm;
use app\models\dao\Category;
use app\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class CategoryController extends Controller
{
    public function behaviors()
    {
    return array_merge(
        parent::behaviors(),
        [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // @ nghĩa là đã đăng nhập
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]
    );
    }

    public function actionIndex()
    {
        // Hiển thị danh sách Category nếu cần
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        // Xem thông tin chi tiết một Category
        return $this->render('view', [
            'model' => CategoryForm::findOne($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new CategoryForm();

        if ($this->request->isPost && $form->load($this->request->post()) && $form->save()) {
            return $this->redirect(['view', 'id' => $form->getSavedModel()->id]);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $form = new CategoryForm();
        $form->loadFromCategory($id);

        if ($this->request->isPost && $form->load($this->request->post()) && $form->save()) {
            return $this->redirect(['view', 'id' => $form->getSavedModel()->id]);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id)
    {
        $form = new CategoryForm();
        $form->loadFromCategory($id);
        $form->delete();

        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
