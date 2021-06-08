<?php

namespace Drupal\lightning_charge;

/**
 * Provides the Address class.
 */
class Address implements AddressInterface {

  /**
   * Provides the type.
   *
   * @var string
   */
  protected $type;

  /**
   * Provides the address.
   *
   * @var string
   */
  protected $address;

  /**
   * Provides the port.
   *
   * @var int
   */
  protected $port;

  /**
   * Provides the constructor.
   *
   * @param mixed $data
   *   An array of data to process.
   */
  public function __construct($data = NULL) {
    if ($data) {
      $this->processData($data);
    }
  }

  /**
   * Processes the data.
   *
   * @param mixed $data
   *   The data object.
   *
   * @return $this
   */
  public function processData($data) {
    $mappings = [
      'type',
      'address',
      'port',
    ];

    $results = LightningChargeUtilities::processMappings($data, $mappings);

    foreach ($results as $k => $v) {
      $this->$k = $v;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function setType(string $input) {
    $this->type = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getType(): string {
    return $this->type;
  }

  /**
   * {@inheritDoc}
   */
  public function setAddress(string $input) {
    $this->address = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getAddress(): string {
    return $this->address;
  }

  /**
   * {@inheritDoc}
   */
  public function setPort(int $input) {
    $this->port = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getPort(): int {
    return $this->port;
  }

}
