<?php

namespace Drupal\lightning_charge;

/**
 * Provides the LightningChargeServiceInterface interface.
 */
interface LightningChargeServiceInterface {

  /**
   * Gets the schema.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the schema.
   */
  public function getSchema($mode = NULL);

  /**
   * Gets the schema options.
   *
   * @return array
   *   An array of schema options.
   */
  public function getSchemaOptions();

  /**
   * Gets the host.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the host.
   */
  public function getHost();

  /**
   * Gets the port.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the port.
   */
  public function getPort();

  /**
   * Gets the username.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the username.
   */
  public function getUsername();

  /**
   * Gets the token.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the token.
   */
  public function getToken();

  /**
   * Gets the webhook status.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return bool
   *   A boolean indicating if webhooks are enabled.
   */
  public function getWebHook();

  /**
   * Gets the webhook type.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the webhook type.
   */
  public function getWebHookType();

  /**
   * Gets the webhook url.
   *
   * @param array $query
   *   An array of query string parameters, should include an id and token to
   *   verify the webhook request.
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the webhook url.
   */
  public function getWebHookUrl($query = []);

  /**
   * Gets the websocket status.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return bool
   *   A boolean indicating if the websocket is enabled.
   */
  public function getWebSocket();

  /**
   * Gets the websocket url.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the websocket url.
   */
  public function getWebSocketUrl();

  /**
   * Gets the payment stream status.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return bool
   *   A boolean indicating if the payment stream is enabled.
   */
  public function getPaymentStream();

  /**
   * Gets the payment stream url.
   *
   * @param mixed $mode
   *   A string containing the mode, or NULL to autodetect.
   *
   * @return string
   *   A string containing the payment stream url.
   */
  public function getPaymentStreamUrl();

  /**
   * Gets the currencies.
   *
   * @return array
   *   An array of currencies.
   */
  public function getCurrencies();

  /**
   * Gets the default currency.
   *
   * @return string
   *   A string containing the default currency.
   */
  public function getDefaultCurrency();

  /**
   * Gets the default limit.
   *
   * @return int
   *   An integer containing the default limit.
   */
  public function getDefaultLimit();

  /**
   * Gets the limit options.
   *
   * @return array
   *   An array of limit options.
   */
  public function getLimitOptions();

  /**
   * Gets the default expiry.
   *
   * @return int
   *   An integer providing the default expiry in seconds.
   */
  public function getDefaultExpiry();

  /**
   * Gets the server information.
   *
   * @return \Drupal\lightning_charge\ServerInformation
   *   The server information object.
   *
   * @throws \Drupal\lightning_charge\Exception\LightningChargeException
   */
  public function getServerInformation();

  /**
   * Gets a CSRF token.
   *
   * @param mixed $id
   *   Will be assigned the token id.
   * @param mixed $token
   *   Will be assigned the token.
   *
   * @return $this
   */
  public function getCsrfToken(&$id, &$token);

  /**
   * Gets the metadata schemas.
   *
   * @return array
   *   An array of metadata schemas.
   */
  public function getMetadataSchemas();

  /**
   * Creates an invoice.
   *
   * @param array $props
   *   An array of invoice properties.
   *
   * @return \Drupal\lightning_charge\InvoiceInterface
   *   The invoice object.
   *
   * @throws \Drupal\lightning_charge\Exception\LightningChargeException
   */
  public function createInvoice($props = []);

  /**
   * Gets the invoices.
   *
   * @return \Drupal\lightning_charge\InvoiceInterface[]
   *   The invoices response.
   *
   * @throws \Drupal\lightning_charge\Exception\LightningChargeException
   */
  public function invoices();

  /**
   * Gets the invoice.
   *
   * @param string $id
   *   A string containing the invoice id.
   *
   * @return \Drupal\lightning_charge\InvoiceInterface
   *   The invoice object.
   *
   * @throws \Drupal\lightning_charge\Exception\LightningChargeException
   */
  public function invoice($id);

  /**
   * Deletes an invoice.
   *
   * @param string $id
   *   A string containing the id.
   * @param string $status
   *   A string containing the status.
   *
   * @return bool
   *   A boolean indicating if the invoice was deleted.
   */
  public function deleteInvoice($id, $status);

  /**
   * Validates a token.
   *
   * @param int $id
   *   An integer containing id.
   * @param string $token
   *   A string containing the token.
   *
   * @return bool
   *   A boolean indicating if the token is valid.
   */
  public function validateToken($id, $token);

}
