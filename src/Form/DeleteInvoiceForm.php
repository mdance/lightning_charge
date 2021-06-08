<?php

namespace Drupal\lightning_charge\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\lightning_charge\Invoice;
use Drupal\lightning_charge\InvoiceInterface;
use Drupal\lightning_charge\LightningChargeConstants;
use Drupal\lightning_charge\LightningChargeServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the DeleteInvoiceForm class.
 */
class DeleteInvoiceForm extends ConfirmFormBase {

  /**
   * Provides the module service.
   *
   * @var \Drupal\lightning_charge\LightningChargeServiceInterface
   */
  protected $service;

  /**
   * Provides the item.
   *
   * @var InvoiceInterface
   */
  protected $item;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    LightningChargeServiceInterface $service
  ) {
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('lightning_charge')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lightning_charge_invoice_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, InvoiceInterface $invoice = NULL) {
    $this->item = $invoice;

    $form['item'] = [
      '#type' => 'value',
      '#value' => $invoice,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function getQuestion() {
    $args = [];

    $args['@id'] = $this->item->getId();

    return $this->t('Are you sure you want to delete the invoice @id?', $args);
  }

  /**
   * {@inheritDoc}
   */
  public function getCancelUrl() {
    $route_name = 'lightning_charge.invoice.view';

    $route_parameters = [];

    $route_parameters['invoice'] = $this->item->getId();

    return Url::fromRoute($route_name, $route_parameters);
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $status = $this->item->getStatus();

    if ($status == LightningChargeConstants::STATUS_PAID) {
      $message = $this->t('You cannot delete an invoice that has been paid.');

      $form_state->setError($form, $message);
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var InvoiceInterface $item */
    $item = $this->item;

    $id = $item->getId();
    $status = $item->getStatus();

    $route_parameters = [];

    if ($id && $status) {
      $result = $this->service->deleteInvoice($id, $status);

      $args = [];

      $args['@id'] = $id;

      if (!$result) {
        $message = $this->t('An error occurred attempting to delete invoice @id.', $args);
      } else {
        $message = $this->t('Invoice @id has been deleted', $args);
      }

      $route_name = 'lightning_charge.invoices';

      $this->messenger()->addMessage($message);
    }

    $form_state->setRedirect($route_name, $route_parameters);
  }

}
