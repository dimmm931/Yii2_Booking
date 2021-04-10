<?php

namespace app\controllers;

use Yii;
use app\models\BookingCPH_2_Hotel\BookingCphV2Hotel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\filters\AccessControl;


/**
 * MyDatesGridController implements the CRUD actions for BookingCphV2Hotel model.
 */
class MyDatesGridController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                
                'actions' => [
                    'delete' => ['POST'],
                ],            
            ],
            
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BookingCphV2Hotel models. GridView. View all user's booked dates
     * @return mixed
     */
    public function actionIndex()
    {
        $myBookedDates = BookingCphV2Hotel::find() ->orderBy ('book_id DESC')  /*->limit('5')*/ 
		    ->where(['booked_by_user' => Yii::$app->user->identity->id,])   //if this line uncommented, each user has its own private booking(many users-> each user has own private booking appartment, other users cannot book it). Comment this if u want that booking is general, ie many users->one booking appartment(many users can book 1 general appartment) 
			->andWhere(['<=', 'book_to_unix',  new Expression('NOW()')])
			->all(); 
            
        $myBookedDatesProvider = new ActiveDataProvider([
            'query' => 
                BookingCphV2Hotel::find() ->orderBy ('book_id DESC')  /*->limit('5')*/ 
		            ->where(['booked_by_user' => Yii::$app->user->identity->id,])   //if this line uncommented, each user has its own private booking(many users-> each user has own private booking appartment, other users cannot book it). Comment this if u want that booking is general, ie many users->one booking appartment(many users can book 1 general appartment) 
			        ->andWhere(['<=', 'book_to_unix',  new Expression('NOW()')]),
            'pagination' => [
                'pageSize' => 4,],
                'sort'=> ['defaultOrder' => ['book_id'=>SORT_DESC]],
        ]); 
        
         return $this->render('index', [
            'myBookedDatesProvider' => $myBookedDatesProvider,
        ]);
    }

    /**
     * Displays a single BookingCphV2Hotel model.
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
     * Creates a new BookingCphV2Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BookingCphV2Hotel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->book_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BookingCphV2Hotel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->book_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BookingCphV2Hotel model.
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
     * Finds the BookingCphV2Hotel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookingCphV2Hotel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookingCphV2Hotel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
