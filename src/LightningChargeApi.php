<?php

namespace Drupal\lightning_charge;

use Drupal\lightning_charge\Exception\LightningChargeException;
use Psr\Http\Message\ResponseInterface;

/**
 * Provides the LightningChargeApi class.
 */
class LightningChargeApi implements LightningChargeApiInterface {

  /**
   * Provides the configuration.
   *
   * @var array
   */
  protected $configuration;

  /**
   * Provides the client.
   *
   * @var
   */
  protected $client;

  /**
   * {@inheritDoc}
   */
  public function __construct($configuration = []) {
    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultConfiguration() {
    $defaults = [
      'schema' => LightningChargeConstants::DEFAULT_SCHEMA,
      'host' => LightningChargeConstants::DEFAULT_HOST,
      'port' => LightningChargeConstants::DEFAULT_PORT,
      'username' => LightningChargeConstants::DEFAULT_USERNAME,
      'token' => '',
    ];

    return [
      LightningChargeConstants::MODE_TEST => $defaults,
      LightningChargeConstants::MODE_LIVE => $defaults,
      'invoice_mode' => LightningChargeConstants::INVOICE_MODE_CURRENCY,
      'currency' => LightningChargeConstants::CURRENCY_USD,
      'client' => NULL,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritDoc}
   */
  public function setConfiguration($input = []) {
    $defaults = $this->getDefaultConfiguration();

    $input = array_merge($defaults, $input);

    $this->configuration = $input;

    return $this;
  }

  /**
   * Gets the mode configuration.
   *
   * @param null $mode
   *   A string containing the mode, or NULL for autodetect.
   *
   * @return array
   *   An array containing the configuration.
   */
  public function getModeConfiguration($mode = NULL) {
    $configuration = $this->getConfiguration();

    if (is_null($mode)) {
      $mode = $configuration['mode'] ?? LightningChargeConstants::MODE_TEST;
    }

    $output = $configuration;
    $output = array_merge($output, $configuration[$mode]);

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getUrl() {
    $configuration = $this->getModeConfiguration();

    $schema = $configuration['schema'];
    $host = $configuration['host'];
    $port = $configuration['port'];
    $username = $configuration['username'];
    $token = $configuration['token'];

    return "$schema://$username:$token@$host:$port";
  }

  /**
   * {@inheritDoc}
   */
  public function getToken() {
    $configuration = $this->getModeConfiguration();

    return $configuration['token'];
  }

  /**
   * {@inheritDoc}
   */
  public function getClient() {
    if (!$this->client) {
      $configuration = $this->getModeConfiguration();

      $this->client = $configuration['client'];
    }

    return $this->client;
  }

  /**
   * Gets the exception code.
   *
   * @param string $input
   *   A string containing the exception code.
   *
   * @return int
   *   An integer containing the exception code.
   */
  public function getExceptionCode($input) {
    return crc32($input);
  }

  /**
   * {@inheritDoc}
   */
  public function getUserAgent(array $context = []) {
    return 'LightningChargeApi client';
  }

  /**
   * {@inheritDoc}
   */
  public function getContentType(array $context = []) {
    return 'application/json';
  }

  /**
   * {@inheritDoc}
   */
  public function getAccept(array $context = []) {
    return 'application/json';
  }

  /**
   * {@inheritDoc}
   */
  public function getContentLength(array $context = []) {
    return 0;
  }

  /**
   * {@inheritDoc}
   */
  public function getAcceptEncoding(array $context = []) {
    return 'gzip,deflate';
  }

  /**
   * {@inheritDoc}
   */
  public function getHeaderMap(array $context = []) {
    $output = [
      'User-Agent' => [
        $this,
        'getUserAgent',
      ],
      'Content-Type' => [
        $this,
        'getContentType',
      ],
      'Accept' => [
        $this,
        'getAccept',
      ],
      'Content-Length' => [
        $this,
        'getContentLength',
      ],
      'Accept-Encoding' => [
        $this,
        'getAcceptEncoding',
      ],
    ];

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getHeaders(array $context = []) {
    $output = [];

    $map = $this->getHeaderMap($context);

    foreach ($map as $src => $callable) {
      $result = $callable($context);

      if (!empty($result)) {
        $output[$src] = $result;
      }
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getAuthenticatedHeaders(array $context = []) {
    $output = $this->getHeaders();

    return $output;
  }

  /**
   * Sends an HTTP request.
   *
   * @param array $options
   *   Provides an array of request options:
   *     url: A string containing the request url.
   *     method: A string containing the request method.
   *     headers: An array containing request headers.
   *     body: The POST body.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   Returns the response object.
   */
  public function sendRequest(array $options = []) {
    $defaults = [
      'url' => '',
      'method' => 'GET',
      'headers' => [],
      'body' => [],
    ];

    $options = array_merge($defaults, $options);

    $url = $options['url'];
    $headers = $options['headers'];
    $method = $options['method'];
    $body = $options['body'];

    return $this->sendHttpRequest($url, $headers, $method, $body);
  }

  /**
   * {@inheritDoc}
   */
  public function sendHttpRequest($url, array $headers, string $method = 'GET', array $body = []) {
    $response = NULL;

    try {
      $params['headers'] = $headers;

      if (!empty($body)) {
        $params['body'] = $this->processBody($body);
      }

      $client = $this->getClient();

      return $client->request($method, $url, $params);
    }
    catch (\Exception $exception) {
      $message = 'An error occurred sending an API request.';
      $code = $this->getExceptionCode(__METHOD__);

      throw New LightningChargeException($message, $code, $exception);
    }
  }

  /**
   * Processes the body.
   *
   * @param mixed $body
   *   Provides the body.
   *
   * @return mixed
   *   The processed body.
   */
  public function processBody($body) {
    return json_encode($body);
  }

  /**
   * Processes the url, and headers for a authenticated request.
   *
   * @param string $endpoint
   *   A string containing the endpoint.
   * @param string $url
   *   A string passed by reference that will contain the authenticated url.
   * @param mixed $headers
   *   An array passed by reference that will contain the request headers.
   * @param mixed $query
   *   An optiohnal string or array of query string parameters.
   *
   * @throws \Drupal\lightning_charge\Exception\LightningChargeException
   */
  public function getAuthenticatedRequest($endpoint, &$url, &$headers = [], $query = NULL) {
    try {
      $url = $this->getUrl();
      $url .= $endpoint;

      if (!is_null($query)) {
        if (is_array($query)) {
          $query = http_build_query($query);
        }

        if (substr($query, 0, 1) != '?') {
          $query = '?' . $query;
        }

        $url .= $query;
        $url = rtrim($url, '?');
      }

      if (!is_array($headers)) {
        $headers = [];
      }
    }
    catch (\Exception $e) {
      $code = $this->getExceptionCode(__METHOD__);

      throw new LightningChargeException('An error occurred attempting to build an authenticated request.', $code);
    }
  }

  /**
   * Sends an authenticated request.
   *
   * @param string $url
   *   A string containing the request URL.
   * @param array $headers
   *   An array of request headers.
   * @param string $method
   *   A string containing the HTTP request method.
   * @param array $body
   *   Optional POST parameters.
   * @param array $context
   *   An array of context variables for processing.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   Returns the response object.
   */
  public function sendAuthenticatedRequest($url, array $headers = [], $method = 'GET', array $body = [], array $context = []) {
    $global = $this->getAuthenticatedHeaders($context);

    $headers = array_merge($global, $headers);

    $options = [
      'url' => $url,
      'headers' => $headers,
      'method' => $method,
      'body' => $body,
      'context' => $context,
    ];

    return $this->sendRequest($options);
  }

  /**
   * Processes a response.
   *
   * @param mixed $response
   *   The response data.
   *
   * @return mixed
   *   Returns the processed response.
   */
  public function processResponse($response) {
    $output = NULL;

    if ($response instanceof ResponseInterface) {
      $output = $response->getBody();
      $output = json_decode($output);
    }

    return $output;
  }

  /**
   * Gets the server information.
   *
   * @return \Drupal\lightning_charge\ServerInformation
   *   The server information object.
   *
   * @throws \Drupal\lightning_charge\Exception\LightningChargeException
   */
  public function getServerInformation() {
    $endpoint = LightningChargeConstants::ENDPOINT_INFO;

    try {
      $this->getAuthenticatedRequest($endpoint, $url, $headers);

      $response = $this->sendAuthenticatedRequest($url, $headers);
      $result = $this->processResponse($response);

      if (is_object($result)) {
        $output = new ServerInformation($result);
      }
    } catch (\Exception $e) {
      $message = 'An error occurred retrieving the server information.';
      $code = $this->getExceptionCode(__METHOD__);

      throw new LightningChargeException($message, $code, $e);
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getInvoiceMode() {
    $configuration = $this->getModeConfiguration();

    return $configuration['invoice_mode'];
  }

  /**
   * {@inheritDoc}
   */
  public function getInvoiceDefaults() {
    $output = [
      'metadata' => [],
    ];

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function createInvoice($body = []) {
    $endpoint = LightningChargeConstants::ENDPOINT_INVOICE;

    try {
      $this->getAuthenticatedRequest($endpoint, $url, $headers);

      $defaults = $this->getInvoiceDefaults();

      $body = array_merge($defaults, $body);

      $response = $this->sendAuthenticatedRequest($url, $headers, 'POST', $body);
      $result = $this->processResponse($response);

      if (is_object($result)) {
        $output = new Invoice($result);
      }
    } catch (\Exception $e) {
      $message = 'An error occurred attempting to create an invoice.';
      $code = $this->getExceptionCode(__METHOD__);

      throw new LightningChargeException($message, $code, $e);
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function invoices() {
    $output = [];

    $endpoint = LightningChargeConstants::ENDPOINT_INVOICES;

    try {
      $this->getAuthenticatedRequest($endpoint, $url, $headers);

      $response = $this->sendAuthenticatedRequest($url, $headers);
      $results = $this->processResponse($response);

      if (is_array($results)) {
        foreach ($results as $result) {
          $output[] = new Invoice($result);
        }
      }
    } catch (\Exception $e) {
      $message = 'An error occurred retrieving the invoices.';
      $code = $this->getExceptionCode(__METHOD__);

      throw new LightningChargeException($message, $code, $e);
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function invoice($id) {
    $output = [];

    $endpoint = LightningChargeConstants::ENDPOINT_INVOICE;

    try {
      $this->getAuthenticatedRequest($endpoint, $url, $headers);

      $url .= "/$id";

      $response = $this->sendAuthenticatedRequest($url, $headers);
      $results = $this->processResponse($response);

      if ($results) {
        $output = new Invoice($results);
      }
    } catch (\Exception $e) {
      $message = 'An error occurred retrieving the invoice.';
      $code = $this->getExceptionCode(__METHOD__);

      throw new LightningChargeException($message, $code, $e);
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function deleteInvoice($id, $status) {
    $output = FALSE;

    $endpoint = LightningChargeConstants::ENDPOINT_INVOICE;

    try {
      $this->getAuthenticatedRequest($endpoint, $url, $headers);

      $url .= "/$id";

      $body = [];

      $body['status'] = $status;

      $this->sendAuthenticatedRequest($url, $headers, 'DELETE', $body);

      $output = TRUE;
    } catch (\Exception $e) {
      $message = 'An error occurred deleting the invoice.';
      $code = $this->getExceptionCode(__METHOD__);

      throw new LightningChargeException($message, $code, $e);
    }

    return $output;
  }

}
