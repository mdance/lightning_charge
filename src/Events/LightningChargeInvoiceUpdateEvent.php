<?php

namespace Drupal\lightning_charge\Events;

use Drupal\Component\EventDispatcher\Event;
use Drupal\lightning_charge\InvoiceInterface;

/**
 * Provides the LightningChargeInvoiceUpdateEvent class.
 */
class LightningChargeInvoiceUpdateEvent extends Event {

  /**
   * Provides the invoice.
   *
   * @var \Drupal\lightning_charge\InvoiceInterface
   */
  protected $invoice;

  /**
   * Provides the constructor method.
   *
   * @param InvoiceInterface $invoice
   *   Provides the invoice.
   */
  public function __construct(InvoiceInterface $invoice) {
    $this->invoice = $invoice;
  }

  /**
   * Gets the invoice
   *
   * @return InvoiceInterface
   *   The invoice object.
   */
  public function getInvoice() {
    return $this->invoice;
  }

}
