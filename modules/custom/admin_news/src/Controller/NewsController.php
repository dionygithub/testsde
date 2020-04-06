<?php

namespace Drupal\admin_news\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class NewsController.
 */
class NewsController extends ControllerBase {

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




  /************************************* Privdas *******************************/

  public function getAllNews(){

      $connection = \Drupal::database();
      $sql = "SELECT * FROM news_entity WHERE status = 1";
      $result = $connection->query($sql);
      $objs = $result->fetchAll();

      return $objs;

  }

    public function getAllNewsRand(){

        $connection = \Drupal::database();
        $sql = "SELECT * FROM news_entity WHERE status = 1 ORDER BY RAND() LIMIT 8";
        $result = $connection->query($sql);
        $objs = $result->fetchAll();

        return $objs;

    }


  public function getNewbyId($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM news_entity WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    return $objs;
  }


  public function getNewsbyIdTax($tid){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM news_entity WHERE  status = 1 AND entity_reference_tax  = :tid ORDER BY RAND() LIMIT 1";
    $result = $connection->query($sql, [':tid' => $tid]);
    $objs = $result->fetchObject();

    return $objs;
  }


}
