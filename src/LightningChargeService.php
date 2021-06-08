<?php

namespace Drupal\lightning_charge;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\PrivateKey;
use Drupal\Core\Site\Settings;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\lightning_charge\Events\LightningChargeEvents;
use Drupal\lightning_charge\Events\LightningChargeMetadataSchemasEvent;
use GuzzleHttp\ClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides the LightningChargeService class.
 */
class LightningChargeService implements LightningChargeServiceInterface {

  use StringTranslationTrait;

  /**
   * Provides the configuration factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Provides the module configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Provides the state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Provides the client service.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Provides the private key service.
   *
   * @var \Drupal\Core\PrivateKey
   */
  protected $privateKey;

  /**
   * Provides a database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Provides the request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Provides the request.
   *
   * @var \Symfony\Component\HttpFoundation\Request|null
   */
  protected $request;

  /**
   * Provides the module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Provides the entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Provides the event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Provides the API service.
   *
   * @var \Drupal\lightning_charge\LightningChargeApiInterface
   */
  protected $api;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    StateInterface $state,
    ClientInterface $client,
    PrivateKey $private_key,
    Connection $connection,
    RequestStack $request_stack,
    ModuleHandlerInterface $module_handler,
    EntityTypeManagerInterface $entity_type_manager,
    EventDispatcherInterface $event_dispatcher,
    LightningChargeApiInterface $api
  ) {
    $this->configFactory = $config_factory;
    $this->config = $config_factory->getEditable(LightningChargeConstants::KEY_SETTINGS);

    $this->state = $state;
    $this->client = $client;
    $this->privateKey = $private_key;
    $this->connection = $connection;
    $this->requestStack = $request_stack;
    $this->request = $request_stack->getCurrentRequest();
    $this->moduleHandler = $module_handler;
    $this->entityTypeManager = $entity_type_manager;
    $this->eventDispatcher = $event_dispatcher;

    $configuration = $this->config->getRawData();

    $modes = [
      LightningChargeConstants::MODE_TEST,
      LightningChargeConstants::MODE_LIVE,
    ];

    foreach ($modes as $mode) {
      $key = LightningChargeConstants::KEY_TOKEN;

      if ($mode == LightningChargeConstants::MODE_TEST) {
        $key .= '_' . $mode;
      }

      $value = $state->get($key);

      $parents = [
        $mode,
        'token',
      ];

      NestedArray::setValue($configuration, $parents, $value);
    }

    $configuration['client'] = $client;

    $api->setConfiguration($configuration);

    $this->api = $api;
  }

  /**
   * {@inheritDoc}
   */
  public function getMode() {
    return $this->config->get('mode') ?? LightningChargeConstants::MODE_TEST;
  }

  /**
   * {@inheritDoc}
   */
  public function getModeOptions() {
    return [
      LightningChargeConstants::MODE_TEST => $this->t('Test'),
      LightningChargeConstants::MODE_LIVE => $this->t('Live'),
    ];
  }

  /**
   * Gets the mode config.
   *
   * @param mixed $parents
   *   The parents to traverse.
   * @param mixed $default
   *   The default value.
   * @param null $mode
   *   The mode.
   *
   * @return array|mixed|string|null
   *   The mode value.
   */
  private function getModeConfig($parents, $default = '', $mode = NULL) {
    if (is_null($mode)) {
      $mode = $this->getMode();
    }

    $subconfig = $this->config->get($mode);

    $key_exists = FALSE;

    if ($subconfig) {
      if (!is_array($parents)) {
        $parents = [
          $parents,
        ];
      }

      $output = NestedArray::getValue($subconfig, $parents, $key_exists);
    }

    if (!$key_exists) {
      $output = $default;
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getSchema($mode = NULL) {
    return $this->getModeConfig('schema', LightningChargeConstants::SCHEMA_HTTP, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getSchemaOptions() {
    return [
      LightningChargeConstants::SCHEMA_HTTP => LightningChargeConstants::SCHEMA_HTTP,
      LightningChargeConstants::SCHEMA_HTTPS => LightningChargeConstants::SCHEMA_HTTPS,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getHost($mode = NULL) {
    $default = 'payments.' . $this->request->getHost();

    return $this->getModeConfig('host', $default, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getPort($mode = NULL) {
    $default = LightningChargeConstants::DEFAULT_PORT;

    return $this->getModeConfig('port', $default, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getUsername($mode = NULL) {
    $default = LightningChargeConstants::DEFAULT_USERNAME;

    return $this->getModeConfig('username', $default, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getToken($mode = NULL) {
    if (is_null($mode)) {
      $mode = $this->getMode();
    }

    $key = LightningChargeConstants::KEY_TOKEN;

    if ($mode == LightningChargeConstants::MODE_TEST) {
      $key .= "_$mode";
    }

    return $this->state->get($key) ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function getCurrencies() {
    $output = [];

    $result = $this->moduleHandler->moduleExists('commerce_price');

    if ($result) {
      $storage = $this->entityTypeManager->getStorage('commerce_currency');

      $results = $storage->loadMultiple();

      foreach ($results as $result) {
        /** @var \Drupal\commerce_price\Entity\CurrencyInterface $result */
        $id = $result->getCurrencyCode();
        $name = $result->getName();

        $output[$id] = $name;
      }
    } else {
      $output[LightningChargeConstants::CURRENCY_USD] = 'USD';
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultCurrency() {
    $this->config->get('default_currency') ?? LightningChargeConstants::CURRENCY_USD;
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultLimit() {
    $output = $this->config->get('default_limit');

    if (!is_numeric($output)) {
      $output = LightningChargeConstants::DEFAULT_LIMIT;
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getLimitOptions() {
    $output = [
      25 => 25,
      50 => 50,
      100 => 100,
    ];

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultExpiry() {
    $output = $this->config->get('default_expiry');

    if (!is_numeric($output)) {
      $output = LightningChargeConstants::DEFAULT_EXPIRY;
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getWebHook($mode = NULL) {
    return $this->getModeConfig('webhook', TRUE, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getWebHookType($mode = NULL) {
    return $this->getModeConfig('webhook_type', LightningChargeConstants::WEBHOOK_DEFAULT, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getWebHookUrl($query = [], $mode = NULL) {
    $type = $this->getWebHookType($mode);

    $options = [
      'absolute' => TRUE,
      'query' => $query,
    ];

    if ($type == LightningChargeConstants::WEBHOOK_DEFAULT) {
      $url = Url::fromRoute('lightning_charge.webhook', [], $options);
      $output = $url->toString();
    } else {
      $url = $this->getModeConfig('webhook_url', '', $mode);
      $url = Url::fromUserInput($url, $options);

      $output = $url->toString();
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getWebSocket($mode = NULL) {
    return $this->getModeConfig('websocket', TRUE, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getWebSocketUrl($mode = NULL) {
    $default = 'ws://payments.' . $this->request->getHost() . '/ws';

    return $this->getModeConfig('websocket_url', $default, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getPaymentStream($mode = NULL) {
    return $this->getModeConfig('payment_stream', FALSE, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getPaymentStreamUrl($mode = NULL) {
    $default = '//payments.' . $this->request->getHost() . '/payment-stream';

    return $this->getModeConfig('payment_stream_url', $default, $mode);
  }

  /**
   * {@inheritDoc}
   */
  public function getServerInformation() {
    return $this->api->getServerInformation();
  }

  /**
   * {@inheritDoc}
   */
  public function getCsrfToken(&$id, &$token) {
    $table = LightningChargeConstants::TABLE_TOKENS;

    $insert = $this->connection->insert($table);

    $fields = [];

    $fields['id'] = NULL;

    $insert->fields($fields);

    $id = $insert->execute();

    $salt = Settings::getHashSalt();

    $key = $this->privateKey->get();
    $key .= $salt;

    $token = Crypt::hmacBase64($id, $key);

    $update = $this->connection->update(LightningChargeConstants::TABLE_TOKENS);

    $fields = [];

    $fields['id'] = $id;
    $fields['token'] = $token;

    $update->fields($fields);
    $update->condition('id', $id);
    $update->execute();

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getMetadataSchemaDefaults() {
    $output = [
      'title' => '',
      'schema' => [],
    ];

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getMetadataSchemas() {
    $event = new LightningChargeMetadataSchemasEvent();

    $this->eventDispatcher->dispatch($event, LightningChargeEvents::METADATA_SCHEMA);

    $output = $event->getSchemas();

    $defaults = $this->getMetadataSchemaDefaults();

    foreach ($output as $k => &$v) {
      $v = array_merge($defaults, $v);
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function createInvoice($props = []) {
    $result = $this->getWebHook();

    if ($result) {
      $this->getCsrfToken($id, $token);

      $query = [
        'id' => $id,
        'token' => $token,
        'XDEBUG_SESSION_START' => 'phpstorm',
      ];

      $url = $this->getWebHookUrl($query);

      $props['webhook'] = $url;
    }

    $output = $this->api->createInvoice($props);

    if ($output instanceof InvoiceInterface) {
      $update = $this->connection->update(LightningChargeConstants::TABLE_TOKENS);

      $fields = [];

      $fields['invoice_id'] = $output->getId();

      $update->fields($fields);
      $update->condition('id', $id);
      $update->execute();

      $update->fields($fields);
      $update->execute();
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function invoices() {
    return $this->api->invoices();
  }

  /**
   * {@inheritDoc}
   */
  public function invoice($id) {
    return $this->api->invoice($id);
  }

  /**
   * {@inheritDoc}
   */
  public function deleteInvoice($id, $status) {
    return $this->api->deleteInvoice($id, $status);
  }

  /**
   * {@inheritDoc}
   */
  public function validateToken($id, $token) {
    $select = $this->connection->select(LightningChargeConstants::TABLE_TOKENS);

    $select->condition('id', $id);
    $select->condition('token', $token);

    $result = $select->countQuery()->execute()->fetchField();

    if ($result) {
      $salt = Settings::getHashSalt();

      $key = $this->privateKey->get();
      $key .= $salt;

      $check = Crypt::hmacBase64($id, $key);

      if ($token == $check) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
