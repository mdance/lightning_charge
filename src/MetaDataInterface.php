<?php

namespace Drupal\lightning_charge;

/**
 * Provides the MetaDataInterface interface.
 */
interface MetaDataInterface {

  /**
   * Sets the key.
   *
   * @param string $input
   *   A string containing the key.
   *
   * @return $this
   */
  public function setKey(string $input);

  /**
   * Gets the key.
   *
   * @return string
   *   A string containing the key.
   */
  public function getKey(): string;

  /**
   * Sets the value.
   *
   * @param mixed $input
   *   The value.
   *
   * @return $this
   */
  public function setValue($input);

  /**
   * Gets the value.
   *
   * @return mixed
   *   The value.
   */
  public function getValue();

  /**
   * Sets the weight.
   *
   * @param int $input
   *   An integer containing the weight.
   *
   * @return $this
   */
  public function setWeight($input);

  /**
   * Gets the weight.
   *
   * @return int
   *   An integer containing the weight.
   */
  public function getWeight();

}
