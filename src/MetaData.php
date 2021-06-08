<?php

namespace Drupal\lightning_charge;

/**
 * Provides the MetaData class.
 */
class MetaData implements MetaDataInterface {

  /**
   * Provides the key.
   *
   * @var string
   */
  protected $key;

  /**
   * Provides the value.
   *
   * @var mixed
   */
  protected $value;

  /**
   * Provides the weight.
   *
   * @var int
   */
  protected $weight;

  /**
   * Provides the constructor.
   *
   * @param string $key
   *   A string containing the key.
   * @param mixed $value
   *   The value
   * @param int $weight
   *   An integer containing the weight.
   */
  public function __construct($key = '', $value = '', $weight = 0) {
    $this->key = $key;
    $this->value = $value;
    $this->weight = $weight;
  }

  /**
   * {@inheritDoc}
   */
  public function setKey(string $input) {
    $this->key = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getKey(): string {
    return $this->key;
  }

  /**
   * {@inheritDoc}
   */
  public function setValue($input) {
    $this->value = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * {@inheritDoc}
   */
  public function setWeight($input) {
    $this->weight = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getWeight() {
    return $this->weight;
  }

}
