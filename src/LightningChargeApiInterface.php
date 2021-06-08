<?php

namespace Drupal\lightning_charge;

use Drupal\lightning_charge\Exception\LightningChargeException;
use LightningChargeClient;

/**
 * Provides the LightningChargeApiInterface interface.
 */
interface LightningChargeApiInterface {

  /**
   * Gets the configuration.
   *
   * @return array
   *   An array containing the configuration.
   */
  public function getDefaultConfiguration();

  /**
   * Gets the configuration.
   *
   * @return array
   *   An array containing the configuration.
   */
  public function getConfiguration();

  /**
   * Sets the configuration.
   *
   * @param array $input
   *   An array containing the configuration.
   *
   * @return $this
   */
  public function setConfiguration($input = []);

  /**
   * Gets the URL.
   *
   * @return string
   *   A string containing the url.
   */
  public function getUrl();

  /**
   * Gets the token.
   *
   * @return string
   *   A string containing the token.
   */
  public function getToken();

  /**
   * Gets the client.
   *
   * @return mixed
   */
  public function getClient();

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
   * Gets the invoice mode.
   *
   * @return string
   *   A string containing the invoice mode.
   */
  public function getInvoiceMode();

  /**
   * Gets the invoice defaults.
   *
   * @return array
   *   An array of invoice defaults.
   */
  public function getInvoiceDefaults();

  /**
   * Creates an invoice.
   *
   * @param array $props
   *   An array of invoice properties.
   *
   * @return \Drupal\lightning_charge\InvoiceInterface
   *   The invoice response.
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
   * Gets an invoice.
   *
   * @param string $id
   *   A string containing the invoice id.
   *
   * @return \Drupal\lightning_charge\InvoiceInterface
   *   The invoices response.
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

}
