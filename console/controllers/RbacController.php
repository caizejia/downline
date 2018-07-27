<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

//自动把controller/action 生成rbac权限
class RbacController extends Controller
{

    // yii rbac/init
    // category/* category/add category/delete

    public function actionInit()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            
            $dir =  dirname(dirname(dirname(__FILE__))).'/api/controllers';  
            
            $controllers = glob($dir. '/*');
             
            $permissions = [];
            foreach ($controllers as $controller) {
                $content = file_get_contents($controller);
                preg_match('/class ([0-9a-zA-Z]+)Controller/', $content, $match);
                $cName = $match[1]; 
                $permissions[] = strtolower($cName. '/*');
                preg_match_all('/public function action([a-zA-Z_]+)/', $content, $matches);
                foreach ($matches[1] as $aName) {
                    $permissions[] = strtolower($cName. '/'. $aName);
                }
            }
            $auth = Yii::$app->authManager;
            foreach ($permissions as $permission) {
                if (!$auth->getPermission($permission)) { 
                    $obj = $auth->createPermission($permission);
                    $obj->description = $permission; 
                    $auth->add($obj);
                }
            }
            $trans->commit();
            echo "import success \n";
        } catch(\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(),'<br>';  
            $trans->rollback();
            echo "import failed \n";
        }
    }

}




