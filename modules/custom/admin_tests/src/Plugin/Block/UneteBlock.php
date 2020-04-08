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
 *   id = "admin_unete_block",
 *   admin_label = @Translation("Unete Block"),
 * )
 */
class UneteBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        global $base_url;

        $htmlunete = [
            '#theme' => 'block_unete',
            '#data' => array('url'=>$base_url.'/user/unete'),
        ];
        $htmluneteBlock = \Drupal::service('renderer')->render($htmlunete);

        return [
            '#markup' => $htmluneteBlock,
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
        $this->configuration['uenete_block_settings'] = $form_state->getValue('uenete_block_settings');
    }
}

