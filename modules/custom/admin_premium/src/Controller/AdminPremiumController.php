<?php

namespace Drupal\admin_premium\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AdminPremiumController.
 */
class AdminPremiumController extends ControllerBase {

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


  public function getAllPremium(){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM premium_entity WHERE status = 1";
    $result = $connection->query($sql);
    $objs = $result->fetchAll();

    return $objs;

  }


  public function getPremiumbyId($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM premium_entity WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getAllPremiumByUser(){

    $data = array();

    $logged_in = \Drupal::currentUser()->isAuthenticated();

    if($logged_in){
      $uid = \Drupal::currentUser()->id();
      $user = \Drupal\user\Entity\User::load($uid);
      $connection = \Drupal::database();
      $sql = "SELECT * FROM premium_entity WHERE status = 1";
      $result = $connection->query($sql);
      $objs = $result->fetchAll();

      if(!empty($objs)){
         foreach($objs as $obj){
           $obj->estado = $this->isValidPremiumByidUseridPrem($user,$obj);
           $data[] = $obj;
         }
      }

    }
    return $data;

  }

  public function isValidPremiumByidUseridPrem($userObj,$premiumObj){

    $valid = false;
    $points = $userObj->get('field_points')->value;
    if($points >= $premiumObj->points){
      $valid = true;
    }
    return $valid;

  }


}
