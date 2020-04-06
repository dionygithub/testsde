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
 *   id = "admin_beneficios_usuarios_block",
 *   admin_label = @Translation("Beneficios Usuarios Block"),
 * )
 */
class BeneficiosUsuariosBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        $adminTestsController = \Drupal::service('service.admin_tests');

        $htmlCats = [
            '#theme' => 'block_beneficios_usuarios',
            '#data' => array('beneficios'=>"true"),
        ];
        $result = \Drupal::service('renderer')->render($htmlCats);

        return [
            '#markup' => $result,
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
        $this->configuration['tests_destacados_block_settings'] = $form_state->getValue('tests_destacados_block_settings');
    }
}

