<?php

/**
 * @file
 * Contains \Drupal\ajax_comments\Form\SettingsForm.
 */

namespace Drupal\ajax_comments\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure 'ajax comments' settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'ajax_comments_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ajax_comments.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ajax_comments.settings');

    $form['notify'] = array(
      '#title' => $this->t('Add notification message when comment posted'),
      '#type' => 'checkbox',
      '#default_value' => $config->get('notify'),
    );

    $form['enable_scroll'] = array(
      '#title' => $this->t('Enable scrolling events'),
      '#type' => 'checkbox',
      '#default_value' => $config->get('enable_scroll'),
    );

    $form['reply_autoclose'] = array(
      '#title' => t('Autoclose any opened reply forms'),
      '#type' => 'checkbox',
      '#default_value' => $config->get('reply_autoclose'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ajax_comments.settings')
      ->set('notify', $form_state->getValue('notify'))
      ->set('disable_scroll', $form_state->getValue('disable_scroll'))
      ->set('reply_autoclose', $form_state->getValue('reply_autoclose'))
      ->save();
    return parent::submitForm($form, $form_state);
  }

}
