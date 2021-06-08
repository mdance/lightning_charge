<?php

namespace Drupal\lightning_charge;

use Drupal\Core\Render\RenderableInterface;

/**
 * Provides the ServerInformationInterface interface.
 */
interface ServerInformationInterface extends RenderableInterface {

  /**
   * Gets the ID.
   *
   * @return string
   *   A string containing the id.
   */
  public function getId(): string;

  /**
   * Sets the ID.
   *
   * @param string $input
   *   A string containing the Id.
   *
   * @return $this
   */
  public function setId(string $input);

  /**
   * Gets the alias.
   *
   * @return string
   *   A string containing the alias.
   */
  public function getAlias(): string;

  /**
   * Sets the alias.
   *
   * @param string $input
   *   A string containing the alias.
   *
   * @return $this
   */
  public function setAlias(string $input);

  /**
   * Gets the color.
   *
   * @return string
   *   A string containing the color.
   */
  public function getColor(): string;

  /**
   * Sets the color.
   *
   * @param string $input
   *   A string containing the color.
   *
   * @return $this
   */
  public function setColor(string $input);

  /**
   * Gets the number peers.
   *
   * @return int
   *   An integer containing the number of peers.
   */
  public function getPeers();

  /**
   * Sets the peers.
   *
   * @param mixed $input
   *   An integer containing the number of peers.
   *
   * @return $this
   */
  public function setPeers($input);

  /**
   * Gets the pending channels.
   *
   * @return int
   *   An integer containing the number of pending channels.
   */
  public function getPendingChannels(): int;

  /**
   * Sets the pending channels.
   *
   * @param mixed $input
   *   An integer containing the number of pending channels.
   *
   * @return $this
   */
  public function setPendingChannels($input);

  /**
   * Gets the active channels.
   *
   * @return int
   *   An integer containing the number of active channels.
   */
  public function getActiveChannels();

  /**
   * Sets the active channels.
   *
   * @param mixed $input
   *   An integer containing the number of active channels.
   */
  public function setActiveChannels($input);

  /**
   * Gets the inactive channels.
   *
   * @return int
   *   An integer containing the number of inactive channels.
   */
  public function getInactiveChannels();

  /**
   * Sets the inactive channels.
   *
   * @param mixed $input
   *   An integer containing the number of inactive channels.
   *
   * @return $this
   */
  public function setInactiveChannels($input);

  /**
   * Gets the addresses.
   *
   * @return \Drupal\lightning_charge\AddressInterface[]
   *   An array of addresses.
   */
  public function getAddresses(): array;

  /**
   * Sets the addresses.
   *
   * @param \Drupal\lightning_charge\AddressInterface[] $input
   *   An array of addresses.
   *
   * @return $this
   */
  public function setAddresses($input);

  /**
   * Gets the bindings.
   *
   * @return \Drupal\lightning_charge\BindingInterface[]
   *   An array of bindings.
   */
  public function getBindings();

  /**
   * Sets the bindings.
   *
   * @param \Drupal\lightning_charge\BindingInterface[] $input
   *   An array of bindings.
   *
   * @return $this
   */
  public function setBindings($input);

  /**
   * Gets the version.
   *
   * @return string
   *   A string containing the version.
   */
  public function getVersion(): string;

  /**
   * Sets the version
   *
   * @param string $input
   *   A string containing the version.
   */
  public function setVersion($input);

  /**
   * Gets the block height.
   *
   * @return int
   *   An integer containing the block height.
   */
  public function getBlockHeight(): int;

  /**
   * Sets the block height.
   *
   * @param int $input
   *   An integer containing the block height.
   */
  public function setBlockHeight(int $input);

  /**
   * Gets the network.
   *
   * @return string
   *   A string containing the network.
   */
  public function getNetwork();

  /**
   * Sets the network.
   *
   * @param string $input
   *   A string containing the network.
   *
   * @return $this
   */
  public function setNetwork($input);

  /**
   * Gets the fees collected.
   *
   * @return string
   *   A string containing the fees collected.
   */
  public function getFeesCollected();

  /**
   * Sets the fees collected.
   *
   * @param string $input
   *   A string containing the fees collected.
   *
   * @return $this
   */
  public function setFeesCollected($input);

  /**
   * Gets the directory.
   *
   * @return string
   *   A string containing the directory.
   */
  public function getDirectory(): string;

  /**
   * Sets the directory.
   *
   * @param string $input
   *   A string containing the directory.
   *
   * @return $this
   */
  public function setDirectory(string $input);

}
