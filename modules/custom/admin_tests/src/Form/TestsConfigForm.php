<?php

namespace Drupal\admin_tests\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TestsConfigForm.
 */
class TestsConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'admin_test.testsConfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tests_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['attempts_test'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Intentos por test'),
        '#maxlength' => 64,
        '#size' => 64,
        '#default_value' => \Drupal::config('admin_tests.testsConfig')->get('attempts_test'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    \Drupal::configFactory()->getEditable('admin_tests.testsConfig')
      ->set('attempts_test', $form_state->getValue('attempts_test'))
      ->save();
  }

}
