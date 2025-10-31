<?php

declare(strict_types=1);

namespace Drupal\public_info\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\public_info\Service\PublicInfoApiClient;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Pager\PagerManagerInterface;

/**
 * Returns responses for Public Info routes.
 */
final class PublicInfoController extends ControllerBase {

  /**
   * The API client service.
   *
   * @var \Drupal\public_info\Service\PublicInfoApiClient
   */
  protected PublicInfoApiClient $apiClient;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The pager manager.
   *
   * @var \Drupal\Core\Pager\PagerManagerInterface
   */
  protected PagerManagerInterface $pagerManager;

  /**
   * Constructs the controller.
   */
  public function __construct(
    PublicInfoApiClient $api_client,
    ConfigFactoryInterface $config_factory,
    PagerManagerInterface $pager_manager
  ) {
    $this->apiClient = $api_client;
    $this->configFactory = $config_factory;
    $this->pagerManager = $pager_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('public_info.api_client'),
      $container->get('config.factory'),
      $container->get('pager.manager')
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {
    // Check permission.
    if (!$this->currentUser()->hasPermission('access public info page')) {
      throw new AccessDeniedHttpException();
    }

    // Get config values.
    $config = $this->config('public_info.settings');
    $limit = (int) $config->get('results_limit') ?: 5;
    $ttl = (int) $config->get('cache_ttl') ?: 15;
    // Fetch SpaceX API data.
    $data = $this->apiClient->getLaunchData();

    if (empty($data)) {
      return [
        '#markup' => $this->t('No launch data found or API error.'),
      ];
    }
    // Apply pagination using PagerManager.
    $total = count($data);
    $pager = $this->pagerManager->createPager($total, $limit);
    $current_page = $pager->getCurrentPage();
    $offset = $current_page * $limit;
    $paged_data = array_slice($data, $offset, $limit);
    \Drupal::logger('public_info')->notice('Total launches: @total | Limit: @limit', [
      '@total' => $total,
      '@limit' => $limit,
      '@pager' =>$pager,
    ]);
    // Prepare launches for rendering.
    $launches = [];
    foreach ($paged_data as $launch) {
      $launches[] = [
        'name' => $this->t('@name', ['@name' => $launch['name'] ?? 'N/A']),
        'flight_number' => $launch['flight_number'] ?? 'N/A',
        'date' => date('Y-m-d H:i', strtotime($launch['date_utc'] ?? '')),
        'image' => $launch['links']['patch']['small'] ?? '',
        'youtube' => $launch['links']['webcast'] ?? '',
      ];
    }

    // Build render array.
    return [
      '#theme' => 'public_info_launches',
      '#launches' => $launches,
      '#last_fetched' => date('Y-m-d H:i'),
      '#pager' => ['#type' => 'pager'],
      '#attached' => [
        'library' => [
          'public_info/launches',
        ],
      ],
      '#cache' => [
        'tags' => ['public_info:spacex'],
        'contexts' => ['user.permissions', 'url.query_args:pager'],
        'max-age' => $ttl * 60,
      ],
    ];
  }
  /**
   * Forces a manual refresh of SpaceX data.
   */
  public function refresh() {
    // Ensure permission.
    if (!$this->currentUser()->hasPermission('access public info page')) {
      throw new AccessDeniedHttpException();
    }

    // Call your API client directly to bypass cache and refetch latest.
    $fresh_data = $this->apiClient->getLaunchData(TRUE); // TRUE means "force refresh"

    if (!empty($fresh_data)) {
      $this->messenger()->addStatus($this->t('SpaceX launch data refreshed successfully.'));
    }
    else {
      $this->messenger()->addError($this->t('Failed to refresh SpaceX data. Please try again.'));
    }

    // Redirect back to the main listing page.
    return $this->redirect('public_info.content');
  }

}

