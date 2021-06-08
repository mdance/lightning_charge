<?php

namespace Drupal\lightning_charge;

/**
 * Provides the LightningChargeConstants class.
 */
class LightningChargeConstants {

  /**
   * Provide the test mode.
   *
   * @var string
   */
  const MODE_TEST = 'test';

  /**
   * Provides the live mode.
   *
   * @var string
   */
  const MODE_LIVE = 'live';

  /**
   * Provides the tokens table.
   *
   * @var string
   */
  const TABLE_TOKENS = 'lightning_charge_tokens';

  /**
   * Provides the configuration key.
   *
   * @var string
   */
  const KEY_SETTINGS = 'lightning_charge.settings';

  /**
   * Provides the token key.
   *
   * @var string
   */
  const KEY_TOKEN = 'lightning_charge_token';

  /**
   * Provides the lightning charge type.
   */
  const KEY_TYPE = 'lightning_charge_type';

  /**
   * Provides the http schema.
   *
   * @var string
   */
  const SCHEMA_HTTP = 'http';

  /**
   * Provides the https schema.
   *
   * @var string
   */
  const SCHEMA_HTTPS = 'https';

  /**
   * Provides the default schema.
   *
   * @var string
   */
  const DEFAULT_SCHEMA = 'http';

  /**
   * Provides the default host.
   *
   * @var string
   */
  const DEFAULT_HOST = 'localhost';

  /**
   * Provides the default port.
   *
   * @var string
   */
  const DEFAULT_PORT = 9112;

  /**
   * Provides the default username.
   *
   * @var string
   */
  const DEFAULT_USERNAME = 'api-token';

  /**
   * Provides the satoshis invoice mode.
   *
   * @var string
   */
  const INVOICE_MODE_SATOSHIS = 'satoshis';

  /**
   * Provides the currency invoice mode.
   *
   * @var string
   */
  const INVOICE_MODE_CURRENCY = 'currency';

  /**
   * Provides the USD currency.
   *
   * @var string
   */
  const CURRENCY_USD = 'USD';

  /**
   * Provides the info endpoint.
   *
   * @var string
   */
  const ENDPOINT_INFO = '/info';

  /**
   * Provides the invoice endpoint.
   *
   * @var string
   */
  const ENDPOINT_INVOICE = '/invoice';

  /**
   * Provides the invoices endpoint.
   *
   * @var string
   */
  const ENDPOINT_INVOICES = '/invoices';

  /**
   * Provides the payment stream endpoint.
   *
   * @var string
   */
  const ENDPOINT_PAYMENT_STREAM = '/payment-stream';

  /**
   * Provides the websocket endpoint.
   *
   * @var string
   */
  const ENDPOINT_WEBSOCKET = '/ws';

  /**
   * Provides the view permission.
   *
   * @var string
   */
  const PERMISSION_VIEW = 'view lightning_charge invoices';

  /**
   * Provides the edit permission.
   *
   * @var string
   */
  const PERMISSION_EDIT = 'edit lightning_charge invoices';

  /**
   * Provides the delete permission.
   *
   * @var string
   */
  const PERMISSION_DELETE = 'delete lightning_charge invoices';

  /**
   * Provides the default webhook.
   *
   * @var string
   */
  const WEBHOOK_DEFAULT = 'default';

  /**
   * Provides the other webhook.
   *
   * @var string
   */
  const WEBHOOK_OTHER = 'other';

  /**
   * Provides the unpaid status.
   *
   * @var string
   */
  const STATUS_UNPAID = 'unpaid';

  /**
   * Provides the paid status.
   *
   * @var string
   */
  const STATUS_PAID = 'paid';

  /**
   * Provides the expired status.
   *
   * @var string
   */
  const STATUS_EXPIRED = 'expired';

  /**
   * Provides the javascript selector.
   *
   * @var string
   */
  const SELECTOR = 'lightning-charge-container';

  /**
   * Provides the default limit.
   *
   * @var int
   */
  const DEFAULT_LIMIT = 25;

  /**
   * Provides the default expiry.
   *
   * @var int
   */
  const DEFAULT_EXPIRY = 3600;

  /**
   * Provides the default view mode.
   */
  const VIEW_MODE_DEFAULT = 'default';

  /**
   * Provides the detailed view mode.
   */
  const VIEW_MODE_DETAILED = 'detailed';

}
