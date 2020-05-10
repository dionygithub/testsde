<?php

namespace Drupal\admin_tests\Controller;

use Drupal\admin_premium\Entity\RequestPremiumEntity;
use Drupal\admin_record\Entity\AdminRecordEntity;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\field\Entity\FieldConfig;
use Drupal\taxonomy\Plugin\views\argument\Taxonomy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TYPO3\PharStreamWrapper\Exception;

/**
 * Class AdminTestsController.
 */
class AdminTestsController extends ControllerBase {

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

  public function utilesajax(){

    global $base_url;

    if(!empty($_POST['op'])){
      $op = $_POST['op'];
    }else{
      $op = \Drupal::request()->request->get('op');
    }


    if($op == "saveTest"){

      $answers = \Drupal::request()->request->get('data');
      $testId = \Drupal::request()->request->get('testId');
      $objTest = $this->getTestbyId($testId);
      $logged_in = \Drupal::currentUser()->isAuthenticated();
      $uid = \Drupal::currentUser()->id();


      $success = false;

      if($logged_in == false){

        /*** Block Likes ***/
        $adminLikesController = \Drupal::service('service.admin_likes');
        $result = $adminLikesController->getCantLikesByTest($testId);
        //echo'<pre>'; print_r($result); die;
        $cantLike = ($result != null && isset($result['like']) && !empty($result['like'])) ? count($result['like']) : 0;
        $cantNoLike = ($result != null && isset($result['nolike']) && !empty($result['nolike'])) ? count($result['nolike']) : 0;
        $isvote = $adminLikesController->getVoteLike($testId,$uid);
        if($logged_in == false){
          $class = 'disabled';
        }else if($isvote){
          $class = "disabled";
        }else{
          $class = "";
        }
        $renderableLikes = [
            '#theme' => 'block_likes_test_user',
            '#data' => array('anonimo'=> true, 'base_url' => $base_url,'testid' => $testId,'class'=>$class,'cantLike'=>$cantLike + $objTest->likes,'cantNoLike'=>$cantNoLike + $objTest->nolikes,'name'=> null),
        ];
        $renderedLikes = \Drupal::service('renderer')->render($renderableLikes);
        $block_likes_test_user = $renderedLikes;
        /*** Fin del Block Likes ***/


        //Tests Relacionados
        $output = $this->getHtmlTestsRelacionados($testId);
        $htmlTestRelac = [
            '#theme' => 'block_tests_relacionados',
            '#data' => array('testsRelacionados'=>$output),
        ];
        $htmlTestRelacionados = \Drupal::service('renderer')->render($htmlTestRelac);



        //Info de preguntas
        $questionList = $this->getListQuestionsByIdTest($testId);
        $totalquestions = !empty($questionList) ? count($questionList) : 0;
        $totalcorrectquestions = count($this->getCorrectQuestions($testId,$answers));
        //**** Fin de Pregunta ****/


        $aliasManager = \Drupal::service('path.alias_manager');
        $alias = $base_url.$aliasManager->getAliasByPath('/test/'.$testId);
        $titleTest = $objTest->name;

        $renderable = [
            '#theme' => 'test_completado_anonimo',
            '#info' => array('block_likes_test_user'=>$block_likes_test_user,'urlTest'=>$alias,'titleTest'=>$titleTest,'tests_relacionados'=>$htmlTestRelacionados,'totalcorrectquestions' => $totalcorrectquestions, 'totalquestions'=>$totalquestions),
        ];
        $rendered = \Drupal::service('renderer')->render($renderable);

        $responseSaveTest = array(
            "success" => false,
            "uid" => 0,
            "htmlAnonimo" => $rendered
        );

      }else{

        $preConditionsSaveTest = $this->preConditionsSaveTest($testId);
        if($preConditionsSaveTest){

          $this->saveRecordTest($testId,$answers);
          $cantTest = count($this->getListTestsRealizadosByUser($uid));
          $this->_updateCantTestUser($uid,$cantTest);
          $success = true;
        }

        $responseSaveTest = array(
            "success" => $success,
            "uid" => $uid,
        );
      }

      $response = new Response(
          json_encode($responseSaveTest),
          Response::HTTP_OK,
          array('content-type' => 'text/x-json')
      );
      return $response;
    }

    if($op == "getNews"){

      $testId = \Drupal::request()->request->get('testId');

      $idTax = $this->getCategoriaByIdTest($testId);
      $newsController = \Drupal::service('service.admin_news');
      $objs = $newsController->getNewsbyIdTax($idTax);

      $htmlNews = [
          '#theme' => 'list_news',
          '#info' => array('news'=>$objs,'formatSimple' => true),
      ];
      $newsRender = \Drupal::service('renderer')->render($htmlNews);

      $response = new Response(
          json_encode(array("success" => true,"html" => $newsRender)),
          Response::HTTP_OK,
          array('content-type' => 'text/x-json')
      );
      return $response;
    }


    if($op == "buyPremium"){

        $premiumId = \Drupal::request()->request->get('premiumId');

        $logged_in = \Drupal::currentUser()->isAuthenticated();
        $uid = \Drupal::currentUser()->id();

        $success = false;
        $text = "";

        if($logged_in == false) {

          \Drupal::messenger()->addMessage(t('No estas logueado'), 'error');

          return new Response(
              json_encode(array("success" => $success,"text"=>"No has iniciado sesión")),
              Response::HTTP_OK,
              array('content-type' => 'text/x-json')
          );
        }

        $adminPremiumController = \Drupal::service('service.admin_premium');
        $premium = $adminPremiumController->getPremiumbyId($premiumId);
        $user = \Drupal\user\Entity\User::load($uid);
        $isValid = $adminPremiumController->isValidPremiumByidUseridPrem($user,$premium);

        //echo '<pre>'; print_r($isValid); die('aaa');

        if($isValid == false) {

          \Drupal::messenger()->addMessage(t('No es válida su solicitud de PREMIO'), 'error');

          return new Response(
              json_encode(array("success" => false,'text'=>t('No es válida su solicitud de PREMIO'))),
              Response::HTTP_OK,
              array('content-type' => 'text/x-json')
          );

        }


        $isSave = $this->saveRequestPremium($premiumId, $uid,$premium->points);

        if ($isSave != false) {

           if($this->subtractPointsUser($user,$premium->points) != false){

             $adminPointsExController = \Drupal::service('service.admin_points_extras');
             $descuento = "-".$premium->points;
             $adminPointsExController->savePointsExtra($uid,0,$descuento);

             //sendMail();

             \Drupal::messenger()->addMessage(t('En Hora Buena, ya tienes tu premio!! Porfavor rebice su correo, Difrutalo'), 'status');
             $success = true;
             $text = "En Hora Buena, ya tienes tu premio!! Porfavor rebice su correo, Difrutalo";

             $cantrequest = count($this->getListSolicitudesByUser($uid));
             $this->_updateCantSolicitudesUser($user,$cantrequest);

           }else{
             \Drupal::messenger()->addMessage(t('Error, al descontar los points'), 'error');
             return new Response(
                 json_encode(array("success" => false,'text'=>"Error, al descontar los points")),
                 Response::HTTP_OK,
                 array('content-type' => 'text/x-json')
             );
           }
        }else{
          $text = "Perdona pero tiene una solicitud en PROCESO";
        }

        $response = new Response(
            json_encode(array("success" => $success,'text'=>$text)),
            Response::HTTP_OK,
            array('content-type' => 'text/x-json')
        );
        return $response;
    }

    if($op == 'generateToken'){

        $uid = \Drupal::request()->request->get('uiduser');
        $uuid = $this->addTokenReferred($uid);

        $response = new Response(
            json_encode(array("success" => true,'token' => $uuid)),
            Response::HTTP_OK,
            array('content-type' => 'text/x-json')
        );
        return $response;
    }



    if($op == 'validaterobot'){

      $success = false;
      $infoMenssage = "";
      $infoName = "";
      $infoMail = "";
      $info = "";

      if(empty($_POST['menssage'])){
        $infoMenssage = "Mensaje obligatorio";
      }

      if(empty($_POST['name'])){
        $infoName = "Nombre obligatorio";
      }

      if(!empty($_POST['mail'])){

        $isvalid = \Drupal::service('email.validator')->isValid($_POST['mail']);
        if(!$isvalid){
          $infoMail = "Correo electrónico invalido";
        }

      }else{
        $infoMail = "Correo electrónico obligatorio";
      }

      if(empty($_POST['g-recaptcha-response'])){
        $info = 'Captcha verification is empty';
      }

      //echo'<pre>'; var_dump($info); var_dump($infoMail); var_dump($infoName); var_dump($infoMenssage); die;
      if($info == "" && $infoMail == "" && $infoName == "" && $infoMenssage == ""){

        $secret_key = KEY_SECRET_RECAPTCHA;

        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);

        $response_data = json_decode($response);
        //echo'<pre>'; print_r($response_data); die;

        if($response_data == null && !$response_data->success){
          $info = 'Captcha verification failed';
          $success = false;
        }else{
          $success = true;
        }


      }else{
        $success = false;
      }

      if($success == true){
        sendMailSugerencias($_POST['name'],$_POST['mail'],$_POST['menssage']);
      }

      $response = new Response(
          json_encode(array("success" => $success,"infocaptcha" => $info,'infoMail'=>$infoMail,'infoNombre'=>$infoName,'infoMenssage'=>$infoMenssage)),
          Response::HTTP_OK,
          array('content-type' => 'text/x-json')
      );
      return $response;

    }

    if($op == 'saveLike'){

          $logged_in = \Drupal::currentUser()->isAuthenticated();
          $uid = \Drupal::currentUser()->id();
          $testId = \Drupal::request()->request->get('testId');
          $like = \Drupal::request()->request->get('like');

          $adminLikesController = \Drupal::service('service.admin_likes');
          $success = false;

          if($logged_in != false){

            $user = \Drupal::entityTypeManager()->getStorage('user')->load($uid);
            $adminPointsExController = \Drupal::service('service.admin_points_extras');
            $ActionsPointsController = \Drupal::service('service.actions_points');

            $action = $ActionsPointsController->getActionbyId(ACTION_MEGUSTA_ID);

            if(!empty($action)){
              $adminLikesController->saveLike($uid,$testId,$like);
              $adminPointsExController->savePointsExtra($uid,$action->id,$action->points);
              $this->addPointsUser($user,$action->points);
              $success = true;
            }else{
              $success = false;
            }


          }else{
            \Drupal::messenger()->addMessage(t('No estas logueado'), 'error');
          }


          $result = $adminLikesController->getCantLikesByTest($testId);
          $cantLike = (!empty($result['like'])) ? count($result['like']) : 0;
          $cantNoLike = (!empty($result['nolike'])) ? count($result['nolike']) : 0;

          $response = new Response(
              json_encode(array("success" => $success,'cantLike' => $cantLike, 'cantNolike' => $cantNoLike)),
              Response::HTTP_OK,
              array('content-type' => 'text/x-json')
          );
          return $response;
        }


    if($op == 'saveTestImagenes'){

        $answers = \Drupal::request()->request->get('data');
        $testId = \Drupal::request()->request->get('testId');



        $correctQuestions = [];
        $answersInput = [];
        if(!empty($answers)){

          foreach($answers as $answer){
            $answersInput[$answer['question']][] = $answer['answer'];
          }

          foreach($answers as $answer){
            $answerObj = $this->getAnswerbyId($answer['answer']);
            if($answerObj->respuesta_valida){
              $correctQuestions[$answer['question']][] = $answer['answer'];
            }

          }
//          echo'<pre>'; print_r($answersInput);
//          echo'<pre>'; print_r($correctQuestions);

          foreach($correctQuestions as $key => $correctQuestion){

            $countAnswareCorrectInput = count($correctQuestion);
            $countAnswareInput = count($answersInput[$key]);
            $countBaseData = count($this->getListAnswerCorrectByIdQuestion($key));

//            echo'<pre>'; print_r('todas '.$countAnswareInput."\n");
//
//            echo'<pre>'; print_r($countAnswareCorrectInput."\n");
//
//            echo'<pre>'; print_r($countBaseData."\n");

            if($countBaseData != $countAnswareInput || $countBaseData != $countAnswareCorrectInput){
              unset($correctQuestions[$key]);
            }
          }
        }

        //echo'<pre>'; print_r($correctQuestions); die;

        $countAnswareCorrect = count($correctQuestions);

          $responseSaveTestImagenes = array(
              "success" => true,
             "countAnswareCorrect" => $countAnswareCorrect
          );

        $response = new Response(
            json_encode($responseSaveTestImagenes),
            Response::HTTP_OK,
            array('content-type' => 'text/x-json')
        );
        return $response;

    }

  }

  public function getAcciones(){


    $uid = \Drupal::currentUser()->id();
    $logged_in = \Drupal::currentUser()->isAuthenticated();

    $output_action = array();
    $outputUser = "";

    if($logged_in == true){

      $ActionsPointsController = \Drupal::service('service.actions_points');
      $acciones = $ActionsPointsController->getAllActionsByUser();

      if(!empty($acciones)){
        foreach($acciones as $accion){

          $accionObj = \Drupal::entityTypeManager()->getStorage('actions_entity')->load($accion->id);
          //echo '<pre>'; print_r($accionObj); die;
          $datos['name'] = $accionObj->get('name')->value;
          $datos['points'] = $accionObj->get('points')->value;
          $datos['description'] = $accionObj->get('description')->value;
          $datos['imagen'] = getUrlImagen($accionObj->get('imagen')->target_id);
          $renderableA = [
              '#theme' => 'actions_entity',
              '#data' => $datos,
          ];
          $output_action[] = \Drupal::service('renderer')->render($renderableA);
        }
      }

      $outputUser = $this->getUserTira($uid,true);

    }

    $build['page-actions'] = array(
        '#theme' => 'page_actions',
        '#data' => array('viewuser' => $outputUser,'viewaction' => $output_action,'logged' => $logged_in),
    );

    return $build;

  }

  public function getInfoCatgoria($idCat){

    $termObj = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($idCat);

    return !empty($termObj) ? $termObj : "";
  }

  public function getUserTira($uid,$show_perfil){

    global $base_url;

    \Drupal::messenger()->addMessage("id usuario " . $uid, 'info');


    $user = \Drupal::entityTypeManager()->getStorage('user')->load($uid);

    \Drupal::messenger()->addMessage("id usuario despues de cargar " . $user->id(), 'info');


    $current_path = \Drupal::service('path.current')->getPath();
    $routing = Url::fromUserInput($current_path)->getRouteName();

    $datos['points'] = array(

        'name' => 'Puntos',
        'data' => $user->get('field_points')->value,
        'url' => Url::fromRoute('admin_tests.pagedesglose',[], ['absolute' => TRUE])->toString(),
        'color' => 'blue',
        'icono' => 'fa-money-bill-alt',
        'active' => ($routing == 'admin_tests.pagedesglose') ? 'tira-activa' : "",
    );

    $datos['cant_tests'] = array(
        'name' => 'Tests',
        'data' => $user->get('field_cant_tests')->value,
        'url' => Url::fromRoute('admin_tests.pageTestRealizado',[], ['absolute' => TRUE])->toString(),
        'color' => 'deep-purple',
        'icono' => 'fa-chart-bar',
        'active' => ($routing == 'admin_tests.pageTestRealizado') ? 'tira-activa' : "",
    );

    $datos['referred'] = array(

        'name' => 'Referidos',
        'data' => $user->get('field_referred')->value,
        'url' => Url::fromRoute('admin_tests.pageReferidos',[], ['absolute' => TRUE])->toString(),
        'color' => 'teal',
        'icono' => 'fa-money-bill-alt',
        'active' => ($routing == 'admin_tests.pageReferidos') ? 'tira-activa' : "",
    );

    $datos['requests_premiums'] = array(
        'name' => 'Solicitudes',
        'data' => $user->get('field_requests_premiums')->value,
        'url' => Url::fromRoute('admin_tests.pageSolicitudes',[], ['absolute' => TRUE])->toString(),
        'color' => 'pink',
        'icono' => 'fa-money-bill-alt',
        'active' => ($routing == 'admin_tests.pageSolicitudes') ? 'tira-activa' : "",
    );

    $userObj = new \stdClass();
    $userObj->name = $user->label();
    $userObj->url = $base_url.'/user/'.$uid;

    if (!$user->user_picture->isEmpty()) {
      $displayImg = file_create_url($user->user_picture->entity->getFileUri());
    }else{
      $displayImg = $base_url.'/'.drupal_get_path('module', 'admin_tests').'/img/default-user.png';
    }
    $userObj->imagen = $displayImg;
    $renderableUser = [
        '#theme' => 'user_tira',
        '#data' => array('data' =>$datos,'user' => $userObj,'show_perfil' => $show_perfil ),
    ];
    return \Drupal::service('renderer')->render($renderableUser);

  }

  public function getPremios(){


    $uid = \Drupal::currentUser()->id();
    $logged_in = \Drupal::currentUser()->isAuthenticated();

    $output_premium = array();
    $outputUser = "";
    if($logged_in == true){

      $adminPremiumController = \Drupal::service('service.admin_premium');
      $premios = $adminPremiumController->getAllPremiumByUser();

      if(!empty($premios)){
         foreach($premios as $premio){
           $premium = \Drupal::entityTypeManager()->getStorage('premium_entity')->load($premio->id);
           \Drupal::entityTypeManager()->getViewBuilder('premium_entity')->resetCache();
           $output_premium[] = render(\Drupal::entityTypeManager()->getViewBuilder('premium_entity')->view($premium, 'teaser'));

         }
      }


      $outputUser = $this->getUserTira($uid,true);

    }

    $build['page-premiums'] = array(
        '#theme' => 'page_premiums',
        '#data' => array('viewuser' => $outputUser,'viewpremium' => $output_premium,'logged' => $logged_in),
    );

    return $build;
  }


  public function page_test_completado($test){

    $info = array();
    global $base_url;
    $uid = \Drupal::currentUser()->id();
    $logged_in = \Drupal::currentUser()->isAuthenticated();
    $info['anonimo'] = $logged_in;
    $objTest = $this->getTestbyId($test);

    $record = $this->getRecordByIdUserANDIdTest($uid,$test);

    if(!empty($record)){
      $info['test_valido'] = 1;
      $info['totalquestions'] = $record->totalquestions;
      $info['totalcorrectquestions'] = $record->totalcorrectquestions;
      $info['points'] = $record->points;
      $info['pointsDesponibles'] = $this->getCantPointsDisponibleByTest($test);

    }else{
      $info['test_valido'] = 0;

    }

    //Tests Relacionados
    $output = $this->getHtmlTestsRelacionados($test);
    $renderable = [
        '#theme' => 'block_tests_relacionados',
        '#data' => array('testsRelacionados'=>$output),
    ];
    $rendered = \Drupal::service('renderer')->render($renderable);

    $info['tests_relacionados'] = $rendered;


    /*** Block Likes ***/
    $user = \Drupal::entityTypeManager()->getStorage('user')->load($uid);
    $adminLikesController = \Drupal::service('service.admin_likes');
    $result = $adminLikesController->getCantLikesByTest($test);
    $cantLike = ($result != null && isset($result['like']) && !empty($result['like'])) ? count($result['like']) : 0;
    $cantNoLike = ($result != null && isset($result['nolike']) && !empty($result['nolike'])) ? count($result['nolike']) : 0;
    $isvote = $adminLikesController->getVoteLike($test,$uid);
    if($logged_in == false){
      $class = 'disabled';
    }else if($isvote){
      $class = "disabled";
    }else{
      $class = "";
    }
    $renderableLikes = [
        '#theme' => 'block_likes_test_user',
        '#data' => array('anonimo' => $logged_in,'base_url'=>$base_url,'testid' => $test,'class'=>$class,'cantLike'=>$cantLike + $objTest->likes ,'cantNoLike'=>$cantNoLike + $objTest->nolikes,'name'=> $this->getNombreUsuario($user)),
    ];
    $renderedLikes = \Drupal::service('renderer')->render($renderableLikes);
    $info['block_likes_test_user'] = $renderedLikes;
    /*** Fin del Block Likes ***/


    $aliasManager = \Drupal::service('path.alias_manager');
    $alias = $base_url.$aliasManager->getAliasByPath('/test/'.$test);
    $info['urlTest'] = $alias;
    $objTest = $this->getTestbyId($test);
    $info['titleTest'] = $objTest->name;
    $info['base_url'] = $base_url;


    $build['test-completado'] = array(
        '#theme' => 'page_test_completado',
        '#idTest' => $test,
        '#info' => $info,
    );

    return $build;

  }

  public function getHtmlTestsRelacionados($idTest){

    $arrayTestsRelacionados = $this->getTestsRelacionados($idTest);

    $output = array();
    if(!empty($arrayTestsRelacionados)) {

      foreach ($arrayTestsRelacionados as $arrayTestsRelacionado) {

        $nid = $arrayTestsRelacionado->id;
        $entity_type = 'admin_test_entity';
        $view_mode = 'teaser';

        $node = \Drupal::entityTypeManager()->getStorage($entity_type)->load($nid);
        $output[] = render(\Drupal::entityTypeManager()->getViewBuilder($entity_type)->view($node, $view_mode));
      }


    }
    return $output;
  }

  public function getHtmlTestsDestacados(){

    $arrayTests = $this->getTestsDestacados();

    $output = array();
    if(!empty($arrayTests)) {

      foreach ($arrayTests as $arrayTest) {

        $nid = $arrayTest->id;
        $entity_type = 'admin_test_entity';
        $view_mode = 'teaser';

        $node = \Drupal::entityTypeManager()->getStorage($entity_type)->load($nid);
        $output[] = render(\Drupal::entityTypeManager()->getViewBuilder($entity_type)->view($node, $view_mode));
      }


    }
    return $output;
  }

  public function getHtmlTestsValorados(){

    $arrayTests = $this->getTestsDestacados();

    $output = array();
    if(!empty($arrayTests)) {

      foreach ($arrayTests as $arrayTest) {

        $nid = $arrayTest->id;
        $entity_type = 'admin_test_entity';
        $view_mode = 'teaser';

        $node = \Drupal::entityTypeManager()->getStorage($entity_type)->load($nid);
        $output[] = render(\Drupal::entityTypeManager()->getViewBuilder($entity_type)->view($node, $view_mode));
      }


    }
    return $output;
  }

  public function getHtmlCuriosidades(){

    $arrayTests = $this->getCuriosidades();

    $output = array("aaa");
//    if(!empty($arrayTests)) {
//
//      foreach ($arrayTests as $arrayTest) {
//
//        $nid = $arrayTest->id;
//        $entity_type = 'admin_test_entity';
//        $view_mode = 'teaser';
//
//        $node = \Drupal::entityTypeManager()->getStorage($entity_type)->load($nid);
//        $output[] = render(\Drupal::entityTypeManager()->getViewBuilder($entity_type)->view($node, $view_mode));
//      }
//
//
//    }
    return $output;
  }

  public function page_test_fallado($test){

    $uid = \Drupal::currentUser()->id();

    $build['test-fallado'] = array(
        '#theme' => 'page_test_fallado',
        '#idTest' => $test,
        '#info' => $uid,
    );

    return $build;

  }


  public function list_categorias(){

    global $base_url;

    $treeNames = array();

      $vid = 'categorias';
      $parent_tid = 0;
      $depth = 2; //depth upto which level you want
      $load_entities = FALSE;
      $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent_tid, $depth, $load_entities);

      foreach ($tree as $term) {

        $termObj = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid);

        $aliasManager = \Drupal::service('path.alias_manager');
        $alias = $aliasManager->getAliasByPath('/taxonomy/term/' . $termObj->get('tid')->value);

        $treeNames[] = array(
            'name' => $termObj->get('name')->value,
            'id' => $termObj->get('tid')->value,
            'description' => $termObj->get('description')->value,
            'path' => $base_url . $alias,
            'imagen' => getUrlImagen($termObj->field_imagen->target_id),
        );
      }

    return $treeNames;
  }

  public function list_categorias_generales(){

    global $base_url;

    $treeNames = array();

    $vid = 'categorias';
    $parent_tid = 0;
    $depth = 2; //depth upto which level you want
    $load_entities = FALSE;
    $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent_tid, $depth, $load_entities);

      foreach ($tree as $term) {

        $termObj = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid);

        $aliasManager = \Drupal::service('path.alias_manager');
        $alias = $aliasManager->getAliasByPath('/taxonomy/term/' . $termObj->get('tid')->value);

        $treeNames[] = array(
            'name' => $termObj->get('name')->value,
            'id' => $termObj->get('tid')->value,
            'description' => $termObj->get('description')->value,
            'path' => $base_url.$alias,
            'imagen' => getUrlImagen($termObj->field_imagen->target_id),
        );
      }

      //echo'<pre>'; print_r($base_url); die;

      $build['list-categorias'] = array(
          '#theme' => 'list_categoria_generales',
          '#list' => $treeNames,
       );

      return $build;


  }

  public function get_page_contacto(){


    global $base_url;

    $urlPageSugerencias = $base_url."/gracias/sugerencias";


    $build['contacto'] = array(
        '#theme' => 'block_sugerencias_contacto',
        '#data' => array('urlPageSugerencias'=>$urlPageSugerencias),
    );

    return $build;

  }

  public function pageReferidos($token){

    global $base_url;
    $logged_in = \Drupal::currentUser()->isAuthenticated();


    $user = $this->getUserByTokenReferidos($token);

    if($user == null ){
      throw new NotFoundHttpException();
    }

    $userObj = new \stdClass();
    $userObj->name = $this->getNombreUsuario($user);
    $userObj->uid = $user->id();
    $userObj->correo = $user->get('mail')->value;

    if (!$user->user_picture->isEmpty()) {
      $displayImg = file_create_url($user->user_picture->entity->getFileUri());
    }else{
      $displayImg = $base_url.'/'.drupal_get_path('module', 'admin_tests').'/img/default-user.png';
    }
    $userObj->imagen = $displayImg;

    //Tests Destacados
    $output = $this->getHtmlTestsDestacados();
    $htmlTestRelac = [
        '#theme' => 'block_tests_destacados',
        '#data' => array('testsDestacados'=>$output),
    ];
    $htmlTestDestacados = \Drupal::service('renderer')->render($htmlTestRelac);

    $urlRegistroReferido = $base_url.'/registrar/referido';

    $ActionsPointsController = \Drupal::service('service.actions_points');
    $action = $ActionsPointsController->getActionbyId(ACTION_REGISTRARSE_ID);
    $puntos_registrarse = (!empty($action)) ? $action->points : 0;


    $build['page-referidos'] = array(
        '#theme' => 'page_referidos',
        '#data' => array('logged_in'=>$logged_in,'points' => $puntos_registrarse ,'user'=>$userObj,'tests_destacados'=>$htmlTestDestacados,'urlRegistroReferido'=>$urlRegistroReferido),
    );

    return $build;

  }

  public function saveTestRango(){

    global $base_url;
    $datos = \Drupal::request()->request->all();

    $idTest = $datos['idTest'];
    $objTest = $this->getTestbyId($idTest);
    //echo'<pre>'; print_r($objTest); die;
    $puntuacionUser = 0;

    $info['resultado'] = "";

    $info = array();
    $uid = \Drupal::currentUser()->id();
    $user = \Drupal::entityTypeManager()->getStorage('user')->load($uid);
    $logged_in = \Drupal::currentUser()->isAuthenticated();
    $info['anonimo'] = $logged_in;

    if(!empty($datos)){

      //suma todos los puntos de las respuestas
      foreach($datos as $key => $answer){
        $ObjKey = explode('-',$key);
        if($ObjKey[0] == "question"){
          $objAnswer = $this->getAnswerbyId($answer);
          $puntuacionUser += !empty($objAnswer->puntuacion) ? $objAnswer->puntuacion : 0;
        }
      }

      //Obtengo todos los resultados del TEST
      $resultados = $this->getResultadosByIdTest($idTest);
      $textResultado = $this->getTextResultadoByPuntuacion($resultados,$puntuacionUser);
      $info['resultado'] =  $textResultado;

    }

    /*** Block Likes ***/
    $adminLikesController = \Drupal::service('service.admin_likes');
    $result = $adminLikesController->getCantLikesByTest($idTest);

    //echo'<pre>'; print_r($result); die;

    $cantLike = ($result != null && isset($result['like']) && !empty($result['like'])) ? count($result['like']) : 0;
    $cantNoLike = ($result != null && isset($result['nolike']) && !empty($result['nolike'])) ? count($result['nolike']) : 0;
    $isvote = ($logged_in == true) ?  $adminLikesController->getVoteLike($idTest,$uid) : true;
    if($logged_in == false){
      $class = 'disabled';
    }else if($isvote){
      $class = "disabled";
    }else{
      $class = "";
    }
    $renderableLikes = [
        '#theme' => 'block_likes_test_user',
        '#data' => array('anonimo' =>$logged_in,'base_url'=>$base_url,'testid' => $idTest,'class'=>$class,'cantLike'=>$cantLike +  $objTest->likes ,'cantNoLike'=>$cantNoLike +  $objTest->nolikes,'name'=> $this->getNombreUsuario($user)),
    ];
    $renderedLikes = \Drupal::service('renderer')->render($renderableLikes);
    $info['block_likes_test_user'] = $renderedLikes;
    /*** Fin del Block Likes ***/

    $aliasManager = \Drupal::service('path.alias_manager');
    $alias = $base_url.$aliasManager->getAliasByPath('/test/'.$idTest);
    $info['urlTest'] = $alias;

    $info['titleTest'] = $objTest->name;


    //Tests Relacionados
    $output = $this->getHtmlTestsRelacionados($idTest);
    $renderable = [
        '#theme' => 'block_tests_relacionados',
        '#data' => array('testsRelacionados'=>$output),
    ];
    $rendered = \Drupal::service('renderer')->render($renderable);

    $info['tests_relacionados'] = $rendered;


    $build['test-completado-resultados'] = array(
        '#theme' => 'page_test_completado_resultados',
        '#idTest' => $idTest,
        '#info' => $info,
    );

    return $build;


  }


  public function registrarReferido(){

    $datos = \Drupal::request()->request->all();
    global $base_url;

    //Tests Relacionados
    $output = $this->getHtmlTestsDestacados();
    $htmlTestRelac = [
        '#theme' => 'block_tests_destacados',
        '#data' => array('testsDestacados'=>$output),
    ];
    $htmlTestDestacados = \Drupal::service('renderer')->render($htmlTestRelac);

    //Validacion de campos vacios
    if(empty($datos['mail']) && empty($datos['iduserreferente']) && empty($datos['pass'])){

      \Drupal::messenger()->addMessage(t('Ohh, nos falta información requerida!!!'), 'error');

      return  $build['page-gracias-referido'] = array(
          '#theme' => 'page_gracia_referido',
          '#data' => array('base_url'=>$base_url,'tests_destacados'=>$htmlTestDestacados,'error'=>true),
      );
    }

    //Validacion del correo
    $isvalid = \Drupal::service('email.validator')->isValid($datos['mail']);
    if(!$isvalid){
      \Drupal::messenger()->addMessage(t('El correo electronico no es correcto'), 'error');

      return  $build['page-gracias-referido'] = array(
          '#theme' => 'page_gracia_referido',
          '#data' => array('base_url'=>$base_url,'tests_destacados'=>$htmlTestDestacados,'error'=>true),
      );
    }

    //Validar que existe el correo
    $existe_user = $this->_getuserbymail($datos['mail']);

    if($existe_user != null){

      \Drupal::messenger()->addMessage(t('Perdone, pero ya se registraron con este correo'), 'error');

      return  $build['page-gracias-referido'] = array(
          '#theme' => 'page_gracia_referido',
          '#data' => array('base_url'=>$base_url,'tests_destacados'=>$htmlTestDestacados,'error'=>true),
      );
    }

    //Validar que el usuario que remite sea verdadero
    $userReferente = \Drupal\user\Entity\User::load($datos['iduserreferente']);

    if($userReferente == null ){

      \Drupal::messenger()->addMessage(t('Perdone, pero su amigo no existe con ese identificador en nuestra plataforma'), 'error');

      return  $build['page-gracias-referido'] = array(
          '#theme' => 'page_gracia_referido',
          '#data' => array('base_url'=>$base_url,'tests_destacados'=>$htmlTestDestacados,'error'=>true),
      );
    }


      $userObj = \Drupal\user\Entity\User::create();

      $userObj->setPassword($datos['pass']);
      $userObj->enforceIsNew();
      $userObj->setEmail($datos['mail']);
      $userObj->setUsername($datos['mail']);
      $userObj->set("status", 1);

      $userObj->save();

      $referidoId = $userObj->uid->value;
      $arrayreferidoId = array('target_id' => $referidoId);
      $userReferente->field_referidos[] = $arrayreferidoId;
      $isUpdate = $userReferente->save();

      if($isUpdate){
        $cant = $this->getCantReferredByUser($userReferente->id())[0];
        $this->_updateReferredUser($userReferente,$cant);
      }

    $adminPointsExController = \Drupal::service('service.admin_points_extras');
    $ActionsPointsController = \Drupal::service('service.actions_points');


    //Puntos por referidos
    $actionReferido = $ActionsPointsController->getActionbyId(ACTION_REFERIDO_ID);
    $this->addPointsUser($userReferente,$actionReferido->points);
    $adminPointsExController->savePointsExtra($userReferente->id(),$actionReferido->id,$actionReferido->points);

    //Puntos por Registrarse
    $actionRegistrarse = $ActionsPointsController->getActionbyId(ACTION_REGISTRARSE_ID);
    $user = \Drupal::entityTypeManager()->getStorage('user')->load($userObj->uid->value);
    $this->addPointsUser($user,$actionRegistrarse->points);
    $adminPointsExController->savePointsExtra($user->id(),$actionRegistrarse->id,$actionRegistrarse->points);
    \Drupal::messenger()->addMessage('Enhorabuena tus primeros '.$actionRegistrarse->points.' Pts','info',TRUE);

    user_login_finalize($user);

    $texto_success = "<h1>Gracias por unirte a nuestra plataforma puedes revisar tu desglose <span class='badge badge-primary'><a href='".$base_url."/user/desglose'>AQUI</a></span></h1>";

    $build['page-gracias-referido'] = array(
        '#theme' => 'page_gracia_referido',
        '#data' => array( 'texto_success' =>$texto_success,'base_url'=>$base_url,'tests_destacados'=>$htmlTestDestacados),
    );

    return $build;

  }

  public function getPageTestRealizado(){

    $uid = \Drupal::currentUser()->id();

    $output_data = array();

    $outputUser = $this->getUserTira($uid,true);

    $tests = $this->getListTestsRealizadosByUser($uid);

    if(!empty($tests)) {
      foreach ($tests as $index => $test) {

        $testRealizadoObj = new \stdClass();

        $testObj = $this->getTestbyId($test->test_reference_entity);

        $testRealizadoObj->name = $testObj->name;
        $testRealizadoObj->preguntas = $test->totalquestions;
        $testRealizadoObj->pregcorrectas = $test->totalcorrectquestions;
        $attempts = \Drupal::config('admin_tests.testsConfig')->get('attempts_test');
        $testRealizadoObj->numintento =  $test->totalattempts.' / '.$attempts;
        $testRealizadoObj->puntosganados = $test->points;
        $testRealizadoObj->fecha = date('d-m-Y h:i A',$test->changed);


        $output_data[] = $testRealizadoObj;
      }
    }

    $build['page-testsrealizado'] = array(
        '#theme' => 'page_testsrealizado',
        '#data' => array('viewuser' => $outputUser,'viewdata' => $output_data),
    );

    return $build;
  }

  public function getPageDesglose(){

    $uid = \Drupal::currentUser()->id();

    $output_data = array();

    $desgloses = $this->getListDesgloseByUser($uid);

    if(!empty($desgloses)){
       foreach($desgloses as $index => $desglose){

         $desgloseObj = new \stdClass();


         if(empty($desglose->action_reference_entity) && $desglose->points < 0){
           $name = "Descuento por compra";
           $desgloseObj->clase = "badge-danger";
         }else{
           $ActionsPointsController = \Drupal::service('service.actions_points');
           $action = $ActionsPointsController->getActionbyId($desglose->action_reference_entity);
           $name = $action->name;
           $desgloseObj->clase = "badge-default";
         }

         $desgloseObj->name = $name;
         $desgloseObj->fecha = date('d-m-Y h:i A',$desglose->changed);

         $desgloseObj->puntos = $desglose->points;

         $output_data[] = $desgloseObj;
       }
    }

    $outputUser = $this->getUserTira($uid,true);

    $build['page-desglose'] = array(
        '#theme' => 'page_desglose',
        '#data' => array('viewuser' => $outputUser,'viewdata' => $output_data),
    );

    return $build;
  }


  public function getPageMisReferidos(){

    $uid = \Drupal::currentUser()->id();
    global $base_url;

    $output_data = array();
    $referidos = $this->getListReferidosByUser($uid);

    if(!empty($referidos)){

      foreach($referidos as $referido){
        $referido = \Drupal::entityTypeManager()->getStorage('user')->load($referido->field_referidos_target_id);

        $referidoObj = new \stdClass();

        if (!$referido->user_picture->isEmpty()) {
          $displayImg = file_create_url($referido->user_picture->entity->getFileUri());
        }else{
          /*$field_info = FieldConfig::loadByName('user', 'user', 'user_picture');
          $image_uuid = $field_info->getSetting('default_image')['uuid'];
          $image = \Drupal::service('entity.repository')->loadEntityByUuid('file', $image_uuid);*/
          $displayImg =  $base_url.'/'.drupal_get_path('module', 'admin_tests').'/img/default-user.png';

        }
        $referidoObj->img = $displayImg;
        $referidoObj->name =  $this->getNombreUsuario($referido);
        $referidoObj->estado = ($referido->get('status')->value) ? "Activo" : "No activo";

        $output_data[] = $referidoObj;

      }
    }


    $outputUser = $this->getUserTira($uid,true);

    $build['page-misreferidos'] = array(
        '#theme' => 'page_misreferidos',
        '#data' => array('viewuser' => $outputUser,'viewdata' => $output_data),
    );

    return $build;
  }


  public function getPageSolicitudes(){

    $uid = \Drupal::currentUser()->id();

    $output_data = array();

    $outputUser = $this->getUserTira($uid,true);

    $solicitudes = $this->getListSolicitudesByUser($uid);

    if(!empty($solicitudes)) {
      foreach ($solicitudes as $index => $solicitude) {

        $solicitudObj = new \stdClass();

        $adminPremiumController = \Drupal::service('service.admin_premium');
        $premium = $adminPremiumController->getPremiumbyId($solicitude->premium_reference_entity);

        $solicitudObj->premio = $premium->name;
        $solicitudObj->puntos = $solicitude->premium_price;

        $termObj = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($solicitude->status_tax);

        $solicitudObj->fecha = date('d-m-Y',$solicitude->changed);
        $solicitudObj->estado = $termObj->get('name')->value;

        $output_data[] = $solicitudObj;
      }
    }

    $build['page-solicitudes'] = array(
        '#theme' => 'page_solicitudes',
        '#data' => array('viewuser' => $outputUser,'viewdata' => $output_data),
    );

    return $build;
  }


//  public function getPageGraciasSugerencias(){
//
//    $uid = \Drupal::currentUser()->id();
//    $logged_in = \Drupal::currentUser()->isAuthenticated();
//
//    $name = "";
//    if($logged_in == true){
//      $user = \Drupal::entityTypeManager()->getStorage('user')->load($uid);
//      $name = $this->getNombreUsuario($user);
//    }
//
//    $output = $this->getHtmlTestsDestacados();
//    $htmlTestRelac = [
//        '#theme' => 'block_tests_destacados',
//        '#data' => array('testsDestacados'=>$output),
//    ];
//    $htmlTestDestacados = \Drupal::service('renderer')->render($htmlTestRelac);
//
//
//    $datos = \Drupal::request()->request->all();
//
//    //Validacion del correo
//    $isvalid = \Drupal::service('email.validator')->isValid($datos['mail']);
//    if(!$isvalid){
//      \Drupal::messenger()->addMessage(t('El correo electronico no es correcto'), 'error');
//    }else{
//
//      sendMailSugerencias($datos['name'],$datos['mail'],$datos['menssage']);
//
//      \Drupal::messenger()->addMessage(t('Sugerencia enviada'), 'info');
//    }
//
//    $build['page-gracias-sugerencias'] = array(
//        '#theme' => 'page_gracias_sugerencias',
//        '#data' => array('name' => $name,'logged' => $logged_in,'tests_destacados'=>$htmlTestDestacados),
//    );
//
//    return $build;
//  }


  public function getNombreUsuario($user){
    return ($user->id() != 0 && !empty($user->get('field_nombre_completo')->value)) ? $user->get('field_nombre_completo')->value : $user->get('name')->value;
  }


 /*****************************************************************************************************************************
 ******************************************************************************************************************************
 ******************************************** Funciones Privadas Solo devuelven codigo ****************************************
 ******************************************************************************************************************************
 *****************************************************************************************************************************/


  public function getCuriosidades(){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_test_entity_field_data WHERE status = :id LIMIT 4";
    $result = $connection->query($sql, [':id' => 1]);
    $objs = $result->fetchAll();

    return true;
  }


  private function getCantReferredByUser($uid){

    $connection = \Drupal::database();
    $sql = "SELECT COUNT(field_referidos_target_id) FROM user__field_referidos WHERE entity_id = :uid";
    $result = $connection->query($sql, [':uid' => $uid]);
    $objs = $result->fetchCol();

     return $objs;

  }

  private function getListReferidosByUser($uid){

    $connection = \Drupal::database();
    $sql = "SELECT field_referidos_target_id FROM user__field_referidos WHERE entity_id = :uid";
    $result = $connection->query($sql, [':uid' => $uid]);
    $objs = $result->fetchAll();

    return $objs;

  }

  private function getListDesgloseByUser($uid){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM points_extras_entity WHERE user_id = :uid ORDER BY changed DESC";
    $result = $connection->query($sql, [':uid' => $uid]);
    $objs = $result->fetchAll();

    return $objs;

  }

  private function getListSolicitudesByUser($uid){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM request_premium_entity WHERE user_id = :uid";
    $result = $connection->query($sql, [':uid' => $uid]);
    $objs = $result->fetchAll();

    return $objs;

  }

  private function getListTestsRealizadosByUser($uid){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_record_entity WHERE user_id = :uid";
    $result = $connection->query($sql, [':uid' => $uid]);
    $objs = $result->fetchAll();

    return $objs;

  }

  private function _updateReferredUser($user,$cant){

    $user->field_referred = $cant;
    $user->save();

    return true;
  }

  private function _updateCantTestUser($uid,$cant){

    $user = \Drupal\user\Entity\User::load($uid);
    $user->set("field_cant_tests",$cant);
    $user->save();

    return true;
  }

  private function _updateCantSolicitudesUser($user,$cant){

    $user->set("field_requests_premiums",$cant);
    $user->save();

    return true;
  }

  private function addTokenReferred($uid){

    $user = \Drupal\user\Entity\User::load($uid);

    $uuid_service = \Drupal::service('uuid');
    $uuid = $uuid_service->generate();

    $user->field_token_referidos = $uuid;
    $user->save();

    return $uuid;
  }

  private function _getuserbymail($mail){



    $users = \Drupal::entityTypeManager()->getStorage('user')
        ->loadByProperties(['mail' => $mail]);

    $user_get = reset($users);

    if($user_get == null){

      \Drupal::logger("admin_tests")->error("No se encuentra el mail @mail",['@mail'=>$mail]);

    }

    return $user_get;
  }


  public function getUserByTokenReferidos($token){

    $users = \Drupal::entityTypeManager()->getStorage('user')
        ->loadByProperties(['field_token_referidos' => $token]);

    $user = reset($users);

    return $user;
  }

  public function subtractPointsUser($user,$points){

    $pointsActual = ($user->field_points->value == null ? 0 : $user->field_points->value );
    $pointsResult = (FLOAT) $pointsActual - (FLOAT)$points;

    //echo'<pre>'; print_r($pointsResult); echo'<>'; print_r($pointsActual); echo'<>'; print_r($points); die;
    $result = false;
    if($pointsResult >= 0){
      $user->set("field_points",$pointsResult);
      $result = $user->save();
    }

    return $result;

  }

  public function addPointsUser($user,$points){

    $pointsActual = ($user->field_points != null && !empty($user->field_points->value)) ? $user->field_points->value : 0;
    $pointsResult = (FLOAT) $pointsActual + (FLOAT)$points;
    //echo'<pre>'; print_r($pointsResult); print_r($user->id()); die;
    $result = false;
    if($pointsResult >= 0){
      $user->set("field_points",$pointsResult);
      $result = $user->save();
    }

    return $result;

  }

  public function getTestsRelacionados($idTest){

    $tests = array();
    $idCategoria = $this->getCategoriaByIdTest($idTest);
    if($idCategoria != null ){
      $tests = $this->getTestsbyIdTaxonomy($idCategoria,$idTest);
    }

    return $tests;
  }

  public function getCategoriaByIdTest($idTest){

    $test = $this->getTestbyId($idTest);
    return ($test->entity_reference_tax != null && !empty($test->entity_reference_tax)) ? $test->entity_reference_tax : null;

  }

  public function getTipoByIdTest($idTest){

    $test = $this->getTestbyId($idTest);
    return ($test->tipo != null && !empty($test->tipo)) ? $test->tipo : null;
  }

  public function preConditionsSaveTest($testId){

    $logged_in = \Drupal::currentUser()->isAuthenticated();
    $success = false;

    if($logged_in){

      $uid = \Drupal::currentUser()->id();
      $record = $this->getCantAttemptsByIdUserANDIdTest($uid,$testId);
      $attempts = \Drupal::config('admin_tests.testsConfig')->get('attempts_test');
      if(empty($record) || $record->totalattempts < $attempts){
        $success = true;
      }

    }

    return $success;
  }


  public function saveRequestPremium($premiumId,$uid,$points){


    $recordEntitys = \Drupal::entityTypeManager()->getStorage('request_premium_entity')
        ->loadByProperties(['premium_reference_entity'=> $premiumId, 'user_id' => $uid,'status_tax' => SOLICITUD_SOLICITADA]);

    $record = reset($recordEntitys);
    $result = false;

    if($record == null){

      $record = RequestPremiumEntity::create();

      $record->set("name","Solicitud de ".$uid." para el premio ".$premiumId);
      $record->set('user_id',$uid);
      $record->set("premium_reference_entity", $premiumId);
      $record->set("premium_price", $points);
      $record->set("status_tax", SOLICITUD_SOLICITADA);

      $result = $record->save();

    }else{
      \Drupal::messenger()->addMessage(t('Disculpe, pero ya tiene una solicitud en proceso para este premio'), 'info');
    }


    return $result;


  }

  public function saveRecordTest($testId,$answers){

    $uid = \Drupal::currentUser()->id();
    $user = \Drupal\user\Entity\User::load($uid);

    $points = 0;

    $recordEntitys = \Drupal::entityTypeManager()->getStorage('admin_record_entity')
        ->loadByProperties(['test_reference_entity'=> $testId, 'user_id' => $uid]);

    $record = reset($recordEntitys);

    $questionList = $this->getListQuestionsByIdTest($testId);
    $totalquestions = !empty($questionList) ? count($questionList) : 0;

    $listQuestionCorrects = $this->getCorrectQuestions($testId,$answers);

    if(!empty($listQuestionCorrects)){
       foreach($listQuestionCorrects as $correct){
         $question = $this->getQuestionbyId($correct);
         $points += (FLOAT) $question->points;
       }
    }

    $totalcorrectquestions = count($listQuestionCorrects);

    //$pointByQuestion = \Drupal::config('admin_tests.testsConfig')->get('point_question');
    $points = ($totalcorrectquestions > 0) ? $points : 0;

    if($record == null){

      $record = AdminRecordEntity::create();
      $totalattempts = 1;

      $this->addPointsUser($user,$points);

    }else{
      $totalattemptsPrevio = $this->getCantAttemptsByIdUserANDIdTest($uid,$testId);

      if(!empty($totalattemptsPrevio->totalattempts)){
        $totalattempts = $totalattemptsPrevio->totalattempts + 1;
      }

      $this->subtractPointsUser($user,$record->get('points')->value);
      $this->addPointsUser($user,$points);
    }

    $record->set("name","Historial ".$uid);
    $record->set('user_id',$uid);
    $record->set("test_reference_entity", $testId);
    $record->set("totalattempts", $totalattempts);
    $record->set("totalquestions", $totalquestions);
    $record->set("totalcorrectquestions", $totalcorrectquestions);
    $record->set("points", $points);

    //echo'<pre>'; print_r($record->get('points')->getValue()); die;
    $record->save();


  }

  public function getCorrectQuestions($idTest,$answers){
    $correctQuestions = [];

    foreach($answers as $answer){
      $answerObj = $this->getAnswerbyId($answer['answer']);
      $isValidQuestion = $this->findQuestionByIdTestAndIdQuestion($idTest,$answer['question']);
      if($isValidQuestion && $answerObj->respuesta_valida){
        $correctQuestions[] = $answer['question'];
      }

    }
    return $correctQuestions;
  }

  public function getCantAttemptsByIdUserANDIdTest($uid,$testId){

    $connection = \Drupal::database();
    $sql = "SELECT totalattempts FROM admin_record_entity WHERE test_reference_entity = :testId AND user_id = :uid";
    $result = $connection->query($sql, [':uid' => $uid,':testId' => $testId]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getRecordByIdUserANDIdTest($uid,$testId){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_record_entity WHERE test_reference_entity = :testId AND user_id = :uid";
    $result = $connection->query($sql, [':uid' => $uid,':testId' => $testId]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getTestbyId($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_test_entity_field_data WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getTestsbyIdTaxonomy($idTax,$idTest){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_test_entity_field_data WHERE id <> :idtest AND entity_reference_tax = :id LIMIT 4";
    $result = $connection->query($sql, [':id' => $idTax, ':idtest' => $idTest]);
    $objs = $result->fetchAll();

    return $objs;
  }

  public function getTestsDestacados(){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_test_entity_field_data WHERE status = :id AND destacado = :destacado LIMIT 4";
    $result = $connection->query($sql, [':id' => 1,':destacado' => 1]);
    $objs = $result->fetchAll();

    return $objs;
  }

  public function getQuestionbyId($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_question_entity_field_data WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    return $objs;
  }

  public function getAnswerbyId($id){

    $connection = \Drupal::database();
    $sql = "SELECT * FROM admin_answer_entity_field_data WHERE id = :id";
    $result = $connection->query($sql, [':id' => $id]);
    $objs = $result->fetchObject();

    if(isset($objs->imagen__target_id) && !empty($objs->imagen__target_id)){
      $objs->imagen = getUrlImagen($objs->imagen__target_id);
      $objs->imagenLarge = getUrlLargeImagen($objs->imagen__target_id);
    }
    //echo'<pre>'; print_r($objs); die;
    return $objs;
  }

  public function getResultadosByIdTest($idTest){

    $list = array();
    $connection = \Drupal::database();
    $sql = "SELECT resultados_target_id FROM admin_test_entity__resultados WHERE entity_id = :id";
    $result = $connection->query($sql, [':id' => $idTest]);
    $objs = $result->fetchAll();

    foreach ($objs as $obj) {
      $list[] = $obj->resultados_target_id;
    }

    return $list;
  }


  public function getTextResultadoByPuntuacion($resultados,$puntuacion){

    $textResult = "";

    if(!empty($resultados)){
      foreach($resultados as $resultado){

        $objTaxResult = $this->getInfoCatgoria($resultado);
        $desde = $objTaxResult->get('field_rango')->from;
        $hasta = $objTaxResult->get('field_rango')->to;
        $description = $objTaxResult->get('description')->value;

        if($puntuacion >= $desde && $puntuacion <= $hasta){
          $textResult = $description;
          return $textResult;
        }
      }
    }

    return $textResult;
  }


  public function findQuestionByIdTestAndIdQuestion($idTest,$idQuestion){

    $connection = \Drupal::database();
    $sql = "SELECT question_reference_entity_target_id FROM admin_test_entity__question_reference_entity WHERE entity_id = :idtest AND question_reference_entity_target_id = :idquestion";
    $result = $connection->query($sql, [':idtest' => $idTest, ':idquestion' => $idQuestion]);
    $obj = $result->fetchObject();

    //echo'<pre>'; print_r($obj); die;

    return !empty($obj) ? true : false;
  }
  public function getListQuestionsByIdTest($idTest){

    $list = array();
    $connection = \Drupal::database();
    $sql = "SELECT question_reference_entity_target_id FROM admin_test_entity__question_reference_entity WHERE entity_id = :id";
    $result = $connection->query($sql, [':id' => $idTest]);
    $objs = $result->fetchAll();

    foreach ($objs as $obj) {
      $list[] = $this->getQuestionbyId($obj->question_reference_entity_target_id);
    }

    return $list;
  }

  public function getFirstQuestionByIdTest($idTest){

    $list = array();
    $connection = \Drupal::database();
    $sql = "SELECT question_reference_entity_target_id FROM admin_test_entity__question_reference_entity WHERE entity_id = :id ORDER BY delta ASC LIMIT 1";
    $result = $connection->query($sql, [':id' => $idTest]);
    $obj = $result->fetchObject();

    if(!empty($obj->question_reference_entity_target_id)){
      $list['titulo'] = $this->getQuestionbyId($obj->question_reference_entity_target_id);
      $list['respuestas'] = $this->getListAnswerByIdQuestion($obj->question_reference_entity_target_id);
    }

    //echo'<pre>'; print_r($list); die;

    return $list;
  }


  public function getListAnswerByIdQuestion($idQuestion){

    $list = array();
    $connection = \Drupal::database();
    $sql = "SELECT answer_reference_entity_target_id FROM admin_question_entity__answer_reference_entity WHERE entity_id = :id";
    $result = $connection->query($sql, [':id' => $idQuestion]);
    $objs = $result->fetchAll();

    foreach ($objs as $obj) {
      $list[] = $this->getAnswerbyId($obj->answer_reference_entity_target_id);
    }

    return $list;

  }

  public function getListAnswerCorrectByIdQuestion($idQuestion){

    $list = array();
    $connection = \Drupal::database();
    $sql = "SELECT answer_reference_entity_target_id FROM admin_question_entity__answer_reference_entity WHERE entity_id = :id";
    $result = $connection->query($sql, [':id' => $idQuestion]);
    $objs = $result->fetchAll();

    foreach ($objs as $obj) {
      $objAnswer = $this->getAnswerbyId($obj->answer_reference_entity_target_id);
      if($objAnswer->respuesta_valida){
        $list[] = $objAnswer;
      }

    }

    return $list;

  }

  public function getTreeTest($id){

    $test = $this->getTestbyId($id);
    $questions = $this->getListQuestionsByIdTest($id);

    if(!empty($questions)){
      foreach($questions as $question){

        if(!empty($question) && isset($question->id)){
          $answer = $this->getListAnswerByIdQuestion($question->id);
          $question->answers = $answer;
          $test->questions[] = $question;
        }
      }
    }
    return $test;
  }

  public function getCantPointsDisponibleByTest($id){

    $points = 0;
    $questions = $this->getListQuestionsByIdTest($id);
    //echo'<pre>'; print_r($questions); die;
    if(!empty($questions)){
       foreach($questions as $question){
         $puntos = ($question != null && !empty($question->points)) ? $question->points :0;
         $points += (FLOAT) $puntos;
       }
    }
    return $points;
  }

}
