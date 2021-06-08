<?php

namespace Drupal\lightning_charge;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the LightningChargeBulkOperationInterface interface.
 */
interface LightningChargeBulkOperationInterface {

  /**
   * Gets the key.
   *
   * @return string
   *   A string containing the key.
   */
  public function getKey(): string;

  /**
   * Gets the label.
   *
   * @return string
   *   A string containing the label.
   */
  public function getLabel();

  /**
   * Provides the validate handler.
   */
  public function validateForm(array &$form, FormStateInterface $form_state);

  /**
   * Provides the submit handler.
   */
  public function submitForm(array &$form, FormStateInterface $form_state);

}
