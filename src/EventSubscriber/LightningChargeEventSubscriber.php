<?php

namespace Drupal\lightning_charge\EventSubscriber;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\lightning_charge\Events\LightningChargeEvents;
use Drupal\lightning_charge\Events\LightningChargeInvoiceBulkOperationsEvent;
use Drupal\lightning_charge\LightningChargeConstants;
use Drupal\lightning_charge\LightningChargeDeleteBulkOperation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides the LightningChargeEventSubscriber class.
 */
class LightningChargeEventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * Provides the current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    AccountProxyInterface $current_user
  ) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $output = [];

    $output[LightningChargeEvents::BULK_OPERATIONS] = 'bulkOperations';

    return $output;
  }

  /**
   * Registers the modules bulk operations.
   *
   * @param LightningChargeInvoiceBulkOperationsEvent $event
   *   Provides the event object.
   */
  public function bulkOperations(LightningChargeInvoiceBulkOperationsEvent $event) {
    if ($this->currentUser->hasPermission(LightningChargeConstants::PERMISSION_DELETE)) {
      $operation = new LightningChargeDeleteBulkOperation();

      $event->addOperation($operation);
    }
  }

}
