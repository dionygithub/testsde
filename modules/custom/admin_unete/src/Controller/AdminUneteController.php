<?php

namespace Drupal\admin_unete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        '#data' => array('urlRegistroUser' => $base_url.'/user/unete/registro'),
    );

    return $build;
  }


  public function registrarUser(){

    $datos = \Drupal::request()->request->all();

    $user = $user = \Drupal\user\Entity\User::load(1);
    _user_mail_notify('register_no_approval_required', $user);
    echo'<pre>'; print_r($datos); die;

  }


  public function loginUser(){

    $datos = \Drupal::request()->request->all();

    $servicepass = \Drupal::service('password');

    $mail = $datos['mail'];
    $pass = $datos['pass'];

    $users = \Drupal::entityTypeManager()->getStorage('user')
        ->loadByProperties(['mail' => $mail]);

      $user_get = reset($users);

      if($user_get != null){

        if ($servicepass->check($pass, $user_get->pass->value)) {

            user_login_finalize($user_get);

          $user_destination = \Drupal::destination()->get();
          $response = new RedirectResponse($user_destination);
          $response->send();


        }else{

          \Drupal::messenger()->addMessage('Ohh, Perdona: su contraseña es incorrecta', 'error');

          $this->redirect('admin_unete.unete');

        }

      }else{

        \Drupal::messenger()->addMessage('Ohh, Perdona: Pero este correo  @mai no se encuentra en nuestra plataforma',['@mail'=>$mail], 'error');

        $this->redirect('admin_unete.unete');

      }

  }

}
