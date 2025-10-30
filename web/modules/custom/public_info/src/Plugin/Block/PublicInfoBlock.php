<?php

namespace Drupal\public_info\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\public_info\Service\PublicInfoApiClient;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Public Info' block.
 *
 * @Block(
 *   id = "public_info_block",
 *   admin_label = @Translation("Public Info Block"),
 *   category = @Translation("Custom")
 * )
 */
class PublicInfoBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The API client service.
   *
   * @var \Drupal\public_info\Service\PublicInfoApiClient
   */
  protected $apiClient;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new PublicInfoBlock instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PublicInfoApiClient $api_client, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->apiClient = $api_client;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('public_info.api_client'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('public_info.settings');
    $limit = (int) $config->get('results_limit') ?: 5;
    $ttl = (int) $config->get('cache_ttl') ?: 15;

    $data = $this->apiClient->getLaunchData();

    if (empty($data)) {
      return [
        '#markup' => $this->t('No launch data available at the moment.'),
      ];
    }

    $launches = [];
    foreach (array_slice($data, 0, $limit) as $launch) {
      $launches[] = [
        'name' => $this->t('@name', ['@name' => $launch['name'] ?? 'N/A']),
      ];
    }

    return [
      '#theme' => 'public_info_launches',
      '#launches' => $launches,
      '#last_fetched' => date('Y-m-d H:i'),
      '#cache' => [
        'tags' => ['public_info:spacex'],
        'contexts' => ['user.permissions'],
        'max-age' => $ttl * 60,
      ],
    ];
  }

}
