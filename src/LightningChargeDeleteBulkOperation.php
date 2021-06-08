<?php

namespace Drupal\lightning_charge;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides the LightningChargeDeleteBulkOperation class.
 */
class LightningChargeDeleteBulkOperation extends LightningChargeBulkOperation {

  use StringTranslationTrait;

  /**
   * Provides the module service.
   *
   * @var \Drupal\lightning_charge\LightningChargeServiceInterface
   */
  protected $service;

  /**
   * {@inheritDoc}
   */
  public function getKey(): string {
    return 'delete';
  }

  /**
   * {@inheritDoc}
   */
  public function getLabel() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $items = $form_state->getValue('items');
    $items = array_filter($items);

    $objects = $form_state->getValue('objects');

    foreach ($items as $item) {
      /** @var \Drupal\lightning_charge\InvoiceInterface $object */
      $results = array_filter($objects, function($input) use ($item) {
        return $input->getId() == $item;
      });

      foreach ($results as $object) {
        $status = $object->getStatus();

        \Drupal::service('lightning_charge')->deleteInvoice($item, $status);
      }
    }
  }

}
