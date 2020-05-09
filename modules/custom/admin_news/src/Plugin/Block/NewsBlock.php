<?php

/**
 * Created by PhpStorm.
 * User: diony
 * Date: 08/03/2020
 * Time: 11:42
 */

namespace Drupal\admin_news\Plugin\Block;

use Drupal\admin_news\Controller\NewsController;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "admin_news_block_news_taxonomy",
 *   admin_label = @Translation("News Block"),
 * )
 */
class NewsBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {


        $newsController = \Drupal::service('service.admin_news');

        $simple = true;

        $current_path = \Drupal::service('path.current')->getPath();

        $path_args = explode('/', $current_path);
        //echo '<pre>'; print_r($path_args); die;
        if($path_args[1] != null && $path_args[2] != null ){

            $adminTestsController = \Drupal::service('service.admin_tests');
            $idTax = $adminTestsController->getCategoriaByIdTest($path_args[2]);

            $objs = $newsController->getNewsbyIdTax($idTax);

        }else{
            $objs = $newsController->getAllNewsRand();
            $simple = false;
        }

        $htmlNews = [
            '#theme' => 'list_news',
            '#info' => array('news'=>$objs,'formatSimple' => $simple),
        ];
        $newsRender = \Drupal::service('renderer')->render($htmlNews);

        return [
            '#markup' => $newsRender,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function blockAccess(AccountInterface $account) {
        return AccessResult::allowedIfHasPermission($account, 'access content');
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state) {
        $config = $this->getConfiguration();

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheMaxAge() {
        return 0;
    }
    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state) {
        $this->configuration['news_block_settings'] = $form_state->getValue('news_block_settings');
    }
}

