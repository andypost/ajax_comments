<?php

/**
 * @file
 * Contains \Drupal\ajax_comments\Form\AjaxCommentsSettingsForm.
 */

namespace Drupal\ajax_comments\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure 'ajax comments' settings for this site.
 */
class AjaxCommentsSettingsForm extends ConfigFormBase {

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

    $form['ajax_comments_node_types'] = array(
      '#title' => t('Content types'),
      '#type' => 'checkboxes',
      '#description' => t('Select node types you want to activate ajax comments on. If you select nothing, AJAX Comments will be enabled everywhere.'),
      '#default_value' => $config->get('ajax_comments_node_types'),
      '#options' => node_type_get_names(),
    );
    $form['ajax_comments_notify'] = array(
      '#title' => t('Notification Message'),
      '#type' => 'checkbox',
      '#description' => t('Add notification message to comment when posted.'),
      '#default_value' => $config->get('ajax_comments_notify'),
    );

    $form['ajax_comments_disable_scroll'] = array(
      '#title' => t('Disable scrolling'),
      '#type' => 'checkbox',
      '#description' => t('Disable the scroll events'),
      '#default_value' => $config->get('ajax_comments_disable_scroll'),
    );

    $form['ajax_comments_reply_autoclose'] = array(
      '#title' => t('Autoclose reply'),
      '#type' => 'checkbox',
      '#description' => t('Autoclose any opened reply forms'),
      '#default_value' => $config->get('ajax_comments_reply_autoclose', ''),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('ajax_comments.settings');
    //$config->set('comments_form_type', $form_state->getValue('ajax_comments']);
    $config->save();
    return parent::submitForm($form, $form_state);

    // @todo Decouple from form: http://drupal.org/node/2040135.
    // Cache::invalidateTags(array('config' => 'ajax_comments.settings'));
  }

}
