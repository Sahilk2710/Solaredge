<?php

namespace Drupal\public_info\Service;

use Drupal\Core\Cache\CacheBackendInterface;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Service to fetch public data from SpaceX API.
 */
class PublicInfoApiClient {

  /**
   * The Guzzle HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The cache backend.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * Cache ID for SpaceX data.
   */
  protected const CACHE_ID = 'public_info.spacex_data';

  /**
   * TTL in seconds (15 minutes default).
   */
  protected const TTL = 900;

  /**
   * Constructs a new PublicInfoApiClient object.
   */
  public function __construct(ClientInterface $http_client, LoggerInterface $logger, CacheBackendInterface $cache) {
    $this->httpClient = $http_client;
    $this->logger = $logger;
    $this->cache = $cache;
  }

  /**
   * Fetch SpaceX launch data from API or cache.
   */
  public function getLaunchData(bool $force_refresh = FALSE): ?array {
    // Step 1: Return cached data if available and not forced to refresh.
    if (!$force_refresh && ($cache = $this->cache->get(self::CACHE_ID))) {
      return $cache->data;
    }
    $url = 'https://api.spacexdata.com/v4/launches';
    try {
      $response = $this->httpClient->request('GET', $url, ['timeout' => 5]);

      if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), TRUE);

        // Step 2: Cache the API response with TTL and cache tags.
        $this->cache->set(
          self::CACHE_ID,
          $data,
          time() + self::TTL,
          ['public_info:spacex']
        );

        $this->logger->info('SpaceX API data fetched successfully (force refresh: @force)', [
          '@force' => $force_refresh ? 'Yes' : 'No',
        ]);

        return $data;
      }
      else {
        $this->logger->warning('Unexpected status code from SpaceX API: @code', [
          '@code' => $response->getStatusCode(),
        ]);
      }
    }
    catch (ConnectException $e) {
      $this->logger->error('Connection timeout when fetching SpaceX data: @msg', ['@msg' => $e->getMessage()]);
    }
    catch (RequestException $e) {
      $this->logger->error('Request failed when fetching SpaceX data: @msg', ['@msg' => $e->getMessage()]);
    }
    catch (\Exception $e) {
      $this->logger->error('Unexpected error while fetching SpaceX data: @msg', ['@msg' => $e->getMessage()]);
    }

    return NULL;
  }


}
