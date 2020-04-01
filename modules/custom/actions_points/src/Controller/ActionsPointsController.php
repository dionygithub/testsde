<?php

namespace Drupal\actions_points\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ActionsPointsController.
 */
class ActionsPointsController extends ControllerBase {

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



  public function getAllActions(){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM actions_entity WHERE status = 1";
    $result = $connection->query($sql);
    $objs = $result->fetchAll();

    return $objs;

  }


  public function getActionbyId($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM actions_entity WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getAllActionsByUser(){

    $objs = array();

    $logged_in = \Drupal::currentUser()->isAuthenticated();

    if($logged_in){
      $connection = \Drupal::database();
      $sql = "SELECT * FROM actions_entity WHERE status = 1";
      $result = $connection->query($sql);
      $objs = $result->fetchAll();


    }
    return $objs;

  }
}
