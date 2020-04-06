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
use Drupal\help_page_test\Plugin\HelpSection\EmptyHelpSection;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "admin_curiosidades_block",
 *   admin_label = @Translation("Curiosidades Block"),
 * )
 */
class CuriosidadesBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        $adminController = \Drupal::service('service.admin_news');
        $resultCuriosidades = $adminController->getAllNewsRand();

        $curiosidadesArray = array();

        $arrayiconos = array("question", "cubes", "paper-plane", "pen-alt","rocket");

        if(!empty($resultCuriosidades)){
            foreach($resultCuriosidades as $curiosidade){
                $curio = new \stdClass();
                $curio->name = $curiosidade->name;
                $curio->icono = $arrayiconos[array_rand($arrayiconos)];
                $curio->decrip = $curiosidade->description;
                $curiosidadesArray[] = $curio;
            }
        }

        $htmlCats = [
            '#theme' => 'block_curiosidades',
            '#data' => array('curiosidades'=>$curiosidadesArray),
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

