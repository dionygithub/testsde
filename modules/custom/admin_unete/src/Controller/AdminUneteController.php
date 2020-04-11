<?php

namespace Drupal\admin_unete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AdminUneteController.
 */
class AdminUneteController extends ControllerBase {

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


  public function page_unete(){

    global $base_url;

    $logged_in = \Drupal::currentUser()->isAuthenticated();

    if($logged_in){
      return $this->redirect('user.page');
    }

    $build['page-unete'] = array(
        '#theme' => 'page_unete',
        '#data' => array('urlRegistroUser' => $base_url.'/user/unete/registro','urlLoginUser' => $base_url.'/user/unete/login','urlPassUser'=>$base_url.'/user/password'),
    );

    return $build;
  }


  public function registrarUser(){

    global $base_url;
    $datos = \Drupal::request()->request->all();


    $mail = $datos['email'];
    $fullname = $datos['fullname'];
    $pass = $datos['pass'];
    $grecaptcharesponse = $datos['g-recaptcha-response'];
    $mensaje = "";

    if(empty($mail)){
      $mensaje .= "<li>Debe especificar un correo electrónico</li>";
    }

    if(empty($fullname)){
      $mensaje .= "<li>Debe especificar su nombre completo</li>";
    }

    if(empty($pass)){
      $mensaje .= "<li>Debe especificar una contraseña</li>";
    }

    if(empty($grecaptcharesponse)){
      $mensaje .= "<li>Debe especificar la pregunta de verificación</li>";
    }

    if(!empty($grecaptcharesponse)){

        $secret_key = KEY_SECRET_RECAPTCHA;

        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);

        $response_data = json_decode($response);

        if($response_data == null && !$response_data->success) {
             $mensaje .= "<li>La pregunta de verificación no es correcta</li>";
        }
    }


    //Validacion del correo
    $isvalid = \Drupal::service('email.validator')->isValid($mail);
    if(!$isvalid){
      $mensaje .= "<li>El correo electrónico no es correcto</li>";
    }

    $existe_user = $this->_getuserbymail($mail);
    if($existe_user != null){
      $mensaje .= "<li>El correo electrónico coincide con un existente en nuestra plataforma</li>";
    }

    if(!empty($mensaje)) {

      $output['filtered_string'] = array(
          '#markup' => '<h2>Por favor, debe corregir las siguientes instruciones</h2><br><ul>' . $mensaje . '</ul>',
          '#allowed_tags' => ['ul', 'li'],
      );

      \Drupal::messenger()->addMessage(render($output), 'error', TRUE);
      $build['page-unete'] = array(
          '#theme' => 'page_unete',
          '#data' => array('urlRegistroUser' => $base_url . '/user/unete/registro', 'urlLoginUser' => $base_url . '/user/unete/login', 'urlPassUser' => $base_url . '/user/password'),
      );

      return $build;
    }

    $user = \Drupal\user\Entity\User::create();

    $user->setPassword($pass);
    $user->enforceIsNew();
    $user->setEmail($mail);
    $user->setUsername($mail);
    $user->set("status", 1);
    $user->set("field_nombre_completo", $fullname);

    $user->save();
    //_user_mail_notify('register_no_approval_required', $user);

    user_login_finalize($user);


    //Puntos por registrarse
    $adminPointsExController = \Drupal::service('service.admin_points_extras');
    $adminTestsController = \Drupal::service('service.admin_tests');
    $ActionsPointsController = \Drupal::service('service.actions_points');
    $action = $ActionsPointsController->getActionbyId(ACTION_REGISTRARSE_ID);

    $adminTestsController->addPointsUser($user,$action->points);
    $adminPointsExController->savePointsExtra($user->id(),$action->id,$action->points);
    \Drupal::messenger()->addMessage('Enhorabuena tus primeros '.$action->points,'info',TRUE);

    $homeResponse = new RedirectResponse(URL::fromUserInput('/user/desglose')->toString());
    $homeResponse->send();
    return;

  }


  public function loginUser(){

    $datos = \Drupal::request()->request->all();

    global $base_url;

    $servicepass = \Drupal::service('password');

    $mail = $datos['mail'];
    $pass = $datos['pass'];

    $users = \Drupal::entityTypeManager()->getStorage('user')
        ->loadByProperties(['mail' => $mail]);

      $user_get = reset($users);
      //echo'<pre>'; print_r($user_get); print_r($datos); die;

      if($user_get != null){

        if ($servicepass->check($pass, $user_get->pass->value)) {

          user_login_finalize($user_get);

          $homeResponse = new RedirectResponse(URL::fromUserInput('/')->toString());
          $homeResponse->send();

        }else{

          \Drupal::messenger()->addMessage('Ohh, Perdona: su contraseña es incorrecta','error',TRUE);

          $build['page-unete'] = array(
              '#theme' => 'page_unete',
              '#data' => array('urlRegistroUser' => $base_url.'/user/unete/registro','urlLoginUser' => $base_url.'/user/unete/login','urlPassUser'=>$base_url.'/user/password'),
          );

          return $build;

        }

      }else{
        \Drupal::messenger()->addMessage('Ohh, Perdona: este correo no existe en nuestra plataforma','error',TRUE);

        $build['page-unete'] = array(
            '#theme' => 'page_unete',
            '#data' => array('urlRegistroUser' => $base_url.'/user/unete/registro','urlLoginUser' => $base_url.'/user/unete/login'),
        );

        return $build;

      }
      return;
  }


  private function _getuserbymail($mail){

    $users = \Drupal::entityTypeManager()->getStorage('user')
        ->loadByProperties(['mail' => $mail]);
    $user_get = reset($users);

    return $user_get;
  }

}
