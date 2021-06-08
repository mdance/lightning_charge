<?php

namespace Drupal\lightning_charge\Events;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\lightning_charge\InvoiceInterface;

/**
 * Provides the LightningChargeAjaxResponseEvent class.
 */
class LightningChargeAjaxResponseEvent extends Event {

  /**
   * Provides the invoice.
   *
   * @var \Drupal\lightning_charge\InvoiceInterface
   */
  protected $invoice;

  /**
   * Provides the response.
   *
   * @var \Drupal\Core\Ajax\AjaxResponse
   */
  protected $response;

  /**
   * Provides the constructor method.
   *
   * @param InvoiceInterface $invoice
   *   Provides the invoice.
   * @param AjaxResponse $response
   *   Provides the response object.
   */
  public function __construct(
    InvoiceInterface $invoice,
    AjaxResponse $response
  ) {
    $this->invoice = $invoice;
    $this->response = $response;
  }

  /**
   * Gets the invoice.
   *
   * @return InvoiceInterface
   *   The invoice object.
   */
  public function getInvoice() {
    return $this->invoice;
  }

  /**
   * Gets the response.
   *
   * @return AjaxResponse
   *   The response object.
   */
  public function getResponse() {
    return $this->response;
  }

}
