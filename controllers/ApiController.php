<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Request;




class ApiController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation=false;
        return parent::beforeAction($action);
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
    //method for reading a module items
    public function actionRates()
    {
        $file_name='CURRFX-USDTZS.csv';
        foreach (file($file_name) as $row) {
            $rower=  explode(',', $row);
            $y=  date('Y',  strtotime($rower[0]));
            $m=  date('m',  strtotime($rower[0]));
            $d=  date('d',  strtotime($rower[0]));
            $date="new Date(".$y.",".$m.",".$d.")";
            $date= trim($date);
            $data[]=array(
                $y,$m,$d,intval($rower[1]),  intval($rower[2]),  intval(str_replace("\n",'',$rower[3]))
            );
            
        }
        array_shift($data);
        print_r(json_encode($data));
    }



    //start of utility functions
    public function renderJson($data)
    {
        print  Json::encode($data);
    }
    public function runQuery($sql)
    {
        $connection=Yii::$app->db; 
        return $connection->createCommand($sql)->queryAll(\PDO::FETCH_OBJ);
    }    
    public function returnObject($sql)
    {
        $connection=Yii::$app->db; 
        return $connection->createCommand($sql)->queryOne(\PDO::FETCH_OBJ);
    }    
    public function deleteObject($table,$column_name,$object_id)
    {
        $sql="DELETE FROM $table WHERE $column_name= $object_id";
        $connection=Yii::$app->db; 
        return $connection->createCommand($sql)->execute();
    }
}
