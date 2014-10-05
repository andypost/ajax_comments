<?php

/**
 * @file
 * Contains \Drupal\ajax_comments\Form\AjaxCommentsSettingsForm.
 */

namespace Drupal\AjaxComments\Form;

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

    $em = \Drupal::entityManager();
    // Only use node types the user has access to.
    $view_modes = array();
    foreach ($em->getStorage('node_type')->loadMultiple() as $type) {
      $view_modes[] = $view_modes;
    }

    $form['ajax_comments_view_modes'] = array(
      '#title' => t('View modes'),
      '#type' => 'checkboxes',
      '#description' => t('Select which view modes you want to activate ajax comments on. If you select nothing, AJAX Comments will be enabled everywhere.'),
      '#default_value' => variable_get('ajax_comments_view_modes', array()),
      '#options' => $view_modes,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $config = $this->config('ajax_comments.settings');
    //$config->set('comments_form_type', $form_state->getValue('ajax_comments']);
    $config->save();
    parent::submitForm($form, $form_state);

    // @todo Decouple from form: http://drupal.org/node/2040135.
    // Cache::invalidateTags(array('config' => 'ajax_comments.settings'));
  }

}
