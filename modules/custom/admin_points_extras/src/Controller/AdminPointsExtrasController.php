<?php

namespace Drupal\admin_points_extras\Controller;

use Drupal\admin_points_extras\Entity\PointsExtrasEntity;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class AdminPointsExtrasController.
 */
class AdminPointsExtrasController extends ControllerBase {

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


  public function getAllPointsExtras(){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM points_extras_entity WHERE status = 1";
    $result = $connection->query($sql);
    $objs = $result->fetchAll();

    return $objs;

  }


  public function getPointExtrabyId($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM points_extras_entity WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getAllPointsExtrasByUser($uid){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM points_extras_entity WHERE status = 1 AND user_id = :user_id";
    $result = $connection->query($sql,[':user_id' => $uid]);
    $objs = $result->fetchAll();

    return $objs;

  }

  public function savePointsExtra($uid,$acctionid,$points){


//    $pointsExtraEntitys = \Drupal::entityTypeManager()->getStorage('points_extras_entity')
//        ->loadByProperties(['action_reference_entity '=> $acctionid, 'user_id' => $uid]);
//
//    $record = reset($pointsExtraEntitys);

      $record = PointsExtrasEntity::create();

      $record->set("name","Point Extra ".$acctionid.'_'.$uid);
      $record->set('user_id',$uid);
      $record->set("action_reference_entity", $acctionid);
      $record->set("points", $points);
      $record->save();

  }

}
