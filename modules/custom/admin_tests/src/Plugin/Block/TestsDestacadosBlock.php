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
 *   id = "admin_tests_destacados_block",
 *   admin_label = @Translation("Tests Destacados Block"),
 * )
 */
class TestsDestacadosBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        $adminTestsController = \Drupal::service('service.admin_tests');

        $output = $adminTestsController->getHtmlTestsDestacados();
        $htmlTestRelac = [
            '#theme' => 'block_tests_destacados',
            '#data' => array('testsDestacados'=>$output),
        ];
        $htmlTestDestacados = \Drupal::service('renderer')->render($htmlTestRelac);

        return [
            '#markup' => $htmlTestDestacados,
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

