<?php

/**
 * Created by PhpStorm.
 * User: diony
 * Date: 05/04/2020
 * Time: 23:56
 */
namespace Drupal\admin_unete\EventSubscriber;

use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\views\Views;
use Drupal\views\ViewExecutable;
use Drupal\user\Entity\User;
use Drupal\Core\Url;

class MyModuleSubscriber implements EventSubscriberInterface {

    /**
     * {@inheritdoc}
     */
    public function checkForRedirection(GetResponseEvent $event) {

        $route_name = RouteMatch::createFromRequest($event->getRequest())->getRouteName();
        $logged_in = \Drupal::currentUser()->isAuthenticated();
        if (!$logged_in) {
            $redirect_url = NULL;
            //echo'<pre>'; print_r($route_name); die;
            switch ($route_name) {
                case 'user.login';
                    // Redirect an authenticated user to the profile page.
                    $redirect_url = Url::fromRoute('admin_unete.unete', [], ['absolute' => TRUE]);
                    break;

                case 'user.register';
                    // Redirect an authenticated user to the profile form.
                    $redirect_url = Url::fromRoute('admin_unete.unete', [], ['absolute' => TRUE]);
                    break;
            }

            if ($redirect_url) {
                $event->setResponse(new RedirectResponse($redirect_url->toString(),301));
            }

        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        $events[KernelEvents::REQUEST][] = array('checkForRedirection');
        return $events;
    }

}