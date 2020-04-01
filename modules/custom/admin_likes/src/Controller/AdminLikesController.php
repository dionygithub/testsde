<?php

namespace Drupal\admin_likes\Controller;

use Drupal\admin_likes\Entity\LikesEntity;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class AdminLikesController.
 */
class AdminLikesController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }


  public function getAllLikes(){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM likes_entity WHERE status = 1";
    $result = $connection->query($sql);
    $objs = $result->fetchAll();

    return $objs;

  }


  public function getLikeById($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM likes_entity WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getAllLikesByUser($uid){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM likes_entity WHERE status = 1 AND user_id = :user_id";
    $result = $connection->query($sql,[':user_id' => $uid]);
    $objs = $result->fetchAll();

    return $objs;

  }

  public function getAllLikesByTest($testid){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM likes_entity WHERE status = 1 AND test_reference_entity = :idtest";
    $result = $connection->query($sql,[':idtest' => $testid]);
    $objs = $result->fetchAll();

    return $objs;

  }

  public function getCantLikesByTest($testid){

    $connection = \Drupal::database();
    $resultFinal = array();
    $sql = "SELECT * FROM likes_entity WHERE status = 1 AND test_reference_entity = :idtest";
    $result = $connection->query($sql,[':idtest' => $testid]);
    $objs = $result->fetchAll();
    //echo'<pre>'; print_r($objs); die;
    if(!empty($objs)){
      foreach($objs as $obj){
        if($obj->like){
          $resultFinal['like'] = $obj;
        }else{
          $resultFinal['nolike'] = $obj;
        }
      }
    }

    return $resultFinal;

  }

  public function getVoteLike($testid,$uid){

    $connection = \Drupal::database();
    $resultFinal = false;
    $sql = "SELECT * FROM likes_entity WHERE status = 1 AND test_reference_entity = :idtest AND user_id = :uid";
    $result = $connection->query($sql,[':idtest' => $testid, ':uid'=>$uid]);
    $objs = $result->fetchObject();

    if($objs->like != null || $objs->like != ""){
      $resultFinal = true;
    }
    //echo'<pre>'; var_dump($resultFinal); die;
    return $resultFinal;

  }

  public function saveLike($uid,$testId,$like){

    $exist = $this->getVoteLike($testId,$uid);
    $likes = ($like == "true") ? 1 :0;
    if(!$exist){
      $record = LikesEntity::create();
      $record->set("name","Like ".$testId.'_'.$uid);
      $record->set('user_id',$uid);
      $record->set("test_reference_entity", $testId);
      $record->set("like", $likes);
      $record->save();
    }



  }

}
