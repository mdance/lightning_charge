<?php

namespace Drupal\lightning_charge;

use Drupal\Core\Render\RenderableInterface;
use Drupal\lightning_charge\Exception\LightningChargeException;
use LightningChargeClient;

/**
 * Provides the InvoiceInterface interface.
 */
interface InvoiceInterface extends RenderableInterface {

  /**
   * Gets the ID.
   *
   * @return string
   *   A string containing the ID.
   */
  public function getId(): string;

  /**
   * Sets the ID.
   *
   * @param string $id
   *   A string containing the ID.
   *
   * @return $this
   */
  public function setId(string $id);

  /**
   * Gets the satoshis.
   *
   * @return string
   *   A string containing the satoshis.
   */
  public function getSatoshis(): string;

  /**
   * Sets the satoshis.
   *
   * @param string $satoshis
   *   A string containing the satoshis.
   *
   * @return $this
   */
  public function setSatoshis(string $satoshis);

  /**
   * Gets the currency.
   *
   * @return string
   *   A string containing the currency.
   */
  public function getCurrency(): string;

  /**
   * Sets the currency.
   *
   * @param string $currency
   *   A string containing the currency.
   *
   * @return $this
   */
  public function setCurrency(string $currency);

  /**
   * Gets the amount.
   *
   * @return string
   *   A string containing the amount.
   */
  public function getAmount(): string;

  /**
   * Gets the formatted amount.
   *
   * @return mixed
   *   The formatted amount.
   */
  public function getFormattedAmount(): string;

  /**
   * Sets the amount.
   *
   * @param string $amount
   *   A string containing the amount.
   *
   * @return $this
   */
  public function setAmount(string $amount);

  /**
   * Gets the hash.
   *
   * @return string
   *   A string containing the hash.
   */
  public function getRhash(): string;

  /**
   * Sets the hash.
   *
   * @param string $rhash
   *   A string containing the hash.
   *
   * @return $this
   */
  public function setRhash(string $rhash);

  /**
   * Gets the payment request.
   *
   * @return string
   */
  public function getPaymentRequest(): string;

  /**
   * Sets the payment request.
   *
   * @param string $payment_request
   *   A string containing the payment request.
   *
   * @return $this
   */
  public function setPaymentRequest(string $payment_request);

  /**
   * Gets the pay index.
   *
   * @return int
   *   An integer containing the payment index.
   */
  public function getPaymentIndex();

  /**
   * Sets the payment index.
   *
   * @param mixed $payment_index
   *   The payment index.
   *
   * @return $this
   */
  public function setPaymentIndex($payment_index);

  /**
   * Gets the description.
   *
   * @return string
   *   A string containing the description.
   */
  public function getDescription(): string;

  /**
   * Sets the description.
   *
   * @param string $description
   *   A string containing the description.
   *
   * @return $this
   */
  public function setDescription(string $description);

  /**
   * Gets the metadata.
   *
   * @return mixed
   *   The invoice metadata.
   */
  public function getMetadata();

  /**
   * Sets the metadata.
   *
   * @param mixed $metadata
   *   The invoice metadata.
   *
   * @return $this
   */
  public function setMetadata($metadata);

  /**
   * Gets the invoice creation date.
   *
   * @return int
   *   The invoice creation date.
   */
  public function getCreateDate(): int;

  /**
   * Gets the formatted invoice creation date.
   *
   * @return string
   *   The formatted date.
   */
  public function getFormattedCreateDate();

  /**
   * Sets the invoice creation date.
   *
   * @param int $create_date
   *   An integer containing the invoice creation date.
   *
   * @return $this
   */
  public function setCreateDate(int $create_date);

  /**
   * Gets the invoice expiration date.
   *
   * @return int
   *   The invoice expiration date.
   */
  public function getExpirationDate(): int;

  /**
   * Gets the formatted expiration date.
   *
   * @return string
   *   The formatted date.
   */
  public function getFormattedExpirationDate();

  /**
   * Sets the expiration date.
   *
   * @param int $expiresAt
   *   The invoice expiration date.
   *
   * @return $this
   */
  public function setExpirationDate(int $expiration_date);

  /**
   * Checks if the invoice has expired.
   *
   * @return bool
   *   A boolean indicating if the invoice has expired.
   */
  public function isExpired(): bool;

  /**
   * Gets the payment date.
   *
   * @return mixed
   *   The payment date.
   */
  public function getPaymentDate();

  /**
   * Gets the formatted payment date.
   *
   * @return string
   *   The formatted date.
   */
  public function getFormattedPaymentDate(): string;

  /**
   * Sets the payment date.
   *
   * @param mixed $payment_date
   *   The payment date.
   *
   * @return $this
   */
  public function setPaymentDate($payment_date);

  /**
   * Gets the satoshis received.
   *
   * @return mixed
   *   The satoshis received.
   */
  public function getSatoshisReceived();

  /**
   * Sets the satoshis received.
   *
   * @param mixed $satoshis_received
   *   The satoshis received.
   *
   * @return $this
   */
  public function setSatoshisReceived($satoshis_received);

  /**
   * Gets the status.
   *
   * @return string
   *   The status.
   */
  public function getStatus(): string;

  /**
   * Gets the formatted status.
   *
   * @return string
   *   The status.
   */
  public function getFormattedStatus(): string;

  /**
   * Sets the status.
   *
   * @param string $status
   *   A string containing the status.
   *
   * @return $this
   */
  public function setStatus(string $status);

  /**
   * Sets the view mode.
   *
   * @param string $input
   *   A string containing the view mode.
   *
   * @return $this
   */
  public function setViewMode($input);

  /**
   * Gets the view mode.
   *
   * @return string
   *   A string containing the view mode.
   */
  public function getViewMode();

}
