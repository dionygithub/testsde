<?php

/**
 * Created by PhpStorm.
 * User: diony
 * Date: 08/03/2020
 * Time: 11:42
 */

namespace Drupal\admin_tests\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "admin_tests_block_sugerencias",
 *   admin_label = @Translation("Sugerencias Block"),
 * )
 */
class SugerenciasBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        global $base_url;

        $urlPageSugerencias = $base_url."/gracias/sugerencias";

        $htmlNews = [
            '#theme' => 'block_sugerencias_contacto',
            '#data' => array('urlPageSugerencias'=>$urlPageSugerencias),
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
    public function blockSubmit($form, FormStateInterface $form_state) {
        $this->configuration['sugerencias_block_settings'] = $form_state->getValue('sugerencias_block_settings');
    }
}

