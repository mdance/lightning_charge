<?php

namespace Drupal\lightning_charge;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the LightningChargeBulkOperation class.
 */
abstract class LightningChargeBulkOperation implements LightningChargeBulkOperationInterface {

  /**
   * {@inheritDoc}
   */
  abstract public function getKey(): string;

  /**
   * {@inheritDoc}
   */
  abstract public function getLabel();

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
