<?php

namespace Drupal\lightning_charge\Events;

/**
 * Provides the LightningChargeEvents class.
 */
final class LightningChargeEvents {

  /**
   * Register bulk operations.
   *
   * @Event
   */
  const BULK_OPERATIONS = 'lightning_charge_bulk_operations';

  /**
   * An invoice has been updated.
   *
   * @Event
   */
  const INVOICE_UPDATE = 'lightning_charge_invoice_update';

  /**
   * A javascript event.
   *
   * @Event
   */
  const JS = 'lightning_charge_js';

  /**
   * A metadata schema event.
   *
   * @Event
   */
  const METADATA_SCHEMA = 'lightning_charge_metadata_schema';

}
