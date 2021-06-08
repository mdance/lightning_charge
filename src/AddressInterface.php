<?php

namespace Drupal\lightning_charge;

/**
 * Provides the AddressInterface interface.
 */
interface AddressInterface {

  /**
   * Sets the type.
   *
   * @param string $input
   *   A string containing the type.
   *
   * @return $this
   */
  public function setType(string $input);

  /**
   * Gets the type.
   *
   * @return string
   *   A string containing the type.
   */
  public function getType(): string;

  /**
   * Sets the address.
   *
   * @param string $input
   *   A string containing the address.
   *
   * @return $this
   */
  public function setAddress(string $input);

  /**
   * Gets the address.
   *
   * @return string
   *   A string containing the address.
   */
  public function getAddress(): string;

  /**
   * Sets the port.
   *
   * @param int $input
   *   An integer containing the port.
   */
  public function setPort(int $input);

  /**
   * Gets the port.
   *
   * @return int
   *   An integer containing the port.
   */
  public function getPort(): int;

}
