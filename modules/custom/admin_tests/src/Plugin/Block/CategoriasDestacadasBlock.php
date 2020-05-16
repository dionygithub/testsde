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
 *   id = "admin_cats_destacadas_block",
 *   admin_label = @Translation("Cats Destacadas Block"),
 * )
 */
class CategoriasDestacadasBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        global $base_url;
        $output = array();
        $tree = array(1,4,5,6,3);
        foreach ($tree as $term) {

            $termObj = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term);

            $aliasManager = \Drupal::service('path.alias_manager');
            $alias = $aliasManager->getAliasByPath('/taxonomy/term/' . $termObj->get('tid')->value);

            $output[] = array(
                'name' => $termObj->get('name')->value,
                'id' => $termObj->get('tid')->value,
                'description' => $termObj->get('description')->value,
                'path' => $base_url . $alias,
                'imagen' => getUrlImagen($termObj->field_imagen->target_id),
            );
        }

        $htmlCats = [
            '#theme' => 'block_cats_destacadas',
            '#data' => array('catsDestacadas'=>$output),
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

