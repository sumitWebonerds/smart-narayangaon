<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\rest\ActiveController;
use yii\web\Response;
use common\models\CategoriesSearch; 
use common\models\Categories; 
use common\models\Sliders;
use common\models\Vendor;
use yii\db\Expression;
use common\models\VendorCategories;
use common\models\Advertisements;
use common\models\Ratings;

/**
 * Site controller
 */
class VendorsapiController extends ActiveController
{
    public $modelClass = "common\models\Vendor";


public function behaviors()
{
    $behaviors = parent::behaviors();
    $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
    

     // remove authentication filter
    $auth = $behaviors['authenticator'];
    unset($behaviors['authenticator']);
    // add CORS filter
    $behaviors['corsFilter'] = [
        'class' => \yii\filters\Cors::className(),
    ];
    // re-add authentication filter
    $behaviors['authenticator'] = $auth;
    // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
    $behaviors['authenticator']['except'] = ['options'];
    return $behaviors;
}


   public function actionList()
    {

        $subcategory_id =Yii::$app->request->get('subcategory_id'); 
        $data =  array();
        $model = new Vendor();
        $vendorCategory=new VendorCategories();
        if(!empty($subcategory_id))
        { 
            $data = Vendor::find()->joinWith('user')->where(['vendor_categories.category_id'=>$subcategory_id,'vendor.status'=>1])->all();
                return $data;

        }else{
            
            $subcategory_id=16;
            
            $data = Vendor::find()->joinWith('user')->where(['vendor_categories.category_id'=>$subcategory_id,'vendor.status'=>1])->all();
    
            return $data;

        }

     }
    public function actionShopDetails()
    {

        $id =Yii::$app->request->get('id'); 
        $model = new Vendor();
        $new = new Ratings();
        $shop = '';
        if(!empty($id))
        { 
            $shop = $model->find()->where(['id'=>$id,'app_id'=>1])->asArray()->one();
            $shop['ratings'] = $model->ratingsAvg($id);    
           // $shop['category'] = $model->categoryDetails($id); 
            return $shop; 
        }
        else{


        }       
     }
     public function actionShopImage(){
     
        $id =Yii::$app->request->get('sub_category_id');
     
        $model = new Categories();
        
        if(!empty($id)){

            $image = $model->find()->where(['id'=>$id,'app_id'=>1])->asArray()->one();
        
            return $image;
        
        }
     
     }

    public function actionSlider()
    {
        $parent_id =Yii::$app->request->get('parent_id'); 
    
        $model = new Sliders();
    
        if(!empty($parent_id))
        { 
            return $model->find()->where(['app_id'=>1])->asArray()->all();  
        }
        else{
            return $model->find()->where(['app_id'=>1])->asArray()->all();   
        }       
     }
    
    public function actionCreateNew()
    {
      
            $model = new Vendor();
            //$data = Yii::$app->request->post();
            
            $data =  json_decode(utf8_encode(file_get_contents("php://input")), false);
               
            if(empty($data)){
                


            }else{
                $model->date=date('Y-m-d');
                $model->shop_name=$data->shopname;
                $model->shop_address=$data->shopaddress;
                

                $model->time_from=(isset($data->from))?date("g:i a", strtotime($data->from)):"";
                $model->time_to=(isset($data->to))?date("g:i a", strtotime($data->to)):"";
                $model->weekly_off= (isset($data->weeklyoff))?$data->weeklyoff:"";
                $model->shop_owner=$data->owner;
                $model->description=$data->description;
                $model->mobile=$data->contactno;
                $model->opt_mobileno=(isset($data->optcontact))?$data->optcontact:"";
                $model->opt_email= (isset($data->email))?$data->email:"";
                $model->collected_by="Self Registered";
                $model->webingeer_coupon = 'No';
            
                if($model->save()){
            
                    $response["message"]="Thank You for Register We will Contact you very soon";
                    $response["data"] = $model;          
                    $response["status"] = "success";          
                    
                    return $response;
            
                }else{
            
                    $response['message']="Error";
                    $response["status"] = "error";
                    $response["data"] =  $model->errors;  
                    return $response;
            
                }
            }
     }

     public function actionOffersDetails()
     {
            $model = new Advertisements();
            $data = array(); 
            if(!empty($parent_id))
            { 
                return $model->find()->asArray()->all();  
            }
            else{
              $data = $model->find()->where(['status'=>1,'app_id'=>1])->asArray()->all();
                
                return $data;
            }       

     }


}


