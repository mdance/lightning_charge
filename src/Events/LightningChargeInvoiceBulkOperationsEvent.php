<?php

namespace Drupal\lightning_charge\Events;

use Drupal\Component\EventDispatcher\Event;
use Drupal\lightning_charge\LightningChargeBulkOperationInterface;

/**
 * Provides the LightningChargeInvoiceBulkOperationsEvent class.
 */
class LightningChargeInvoiceBulkOperationsEvent extends Event {

  /**
   * Provides the operations.
   *
   * @var LightningChargeBulkOperationInterface[]
   */
  protected $operations = [];

  /**
   * Adds an operation.
   *
   * @param \Drupal\lightning_charge\LightningChargeBulkOperationInterface $input
   *   The operation object.
   *
   * @return $this
   */
  public function addOperation(LightningChargeBulkOperationInterface $input) {
    $this->operations[] = $input;

    return $this;
  }

  /**
   * Gets the operations.
   *
   * @return LightningChargeBulkOperationInterface[]
   *   An array of bulk operations.
   */
  public function getOperations() {
    return $this->operations;
  }

}
