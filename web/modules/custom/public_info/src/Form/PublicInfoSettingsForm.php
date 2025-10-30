<?php

namespace Drupal\public_info\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Public Info settings for this site.
 */
class PublicInfoSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['public_info.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'public_info_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('public_info.settings');

    $form['cache_ttl'] = [
      '#type' => 'number',
      '#title' => $this->t('Cache TTL (minutes)'),
      '#default_value' => $config->get('cache_ttl') ?? 15,
      '#min' => 1,
      '#description' => $this->t('How long API data should be cached.'),
    ];

    $form['results_limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of launches to display'),
      '#default_value' => $config->get('results_limit') ?? 5,
      '#min' => 1,
      '#max' => 50,
      '#description' => $this->t('How many latest SpaceX launches should be displayed.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);

    $this->configFactory()->getEditable('public_info.settings')
      ->set('cache_ttl', (int) $form_state->getValue('cache_ttl'))
      ->set('results_limit', (int) $form_state->getValue('results_limit'))
      ->save();

    $this->messenger()->addMessage($this->t('Public Info configuration saved.'));
  }

}
