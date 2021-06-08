<?php

namespace Drupal\lightning_charge\Controller;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\lightning_charge\Events\LightningChargeAjaxResponseEvent;
use Drupal\lightning_charge\Events\LightningChargeEvents;
use Drupal\lightning_charge\Events\LightningChargeInvoiceUpdateEvent;
use Drupal\lightning_charge\Exception\LightningChargeException;
use Drupal\lightning_charge\Invoice;
use Drupal\lightning_charge\InvoiceInterface;
use Drupal\lightning_charge\LightningChargeConstants;
use Drupal\lightning_charge\LightningChargeServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides the DefaultController class.
 */
class DefaultController extends ControllerBase {

  /**
   * Provides the event dispatcher.
   *
   * @var EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Provides the module service.
   *
   * @var \Drupal\lightning_charge\LightningChargeServiceInterface
   */
  protected $service;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    EventDispatcherInterface $event_dispatcher,
    LightningChargeServiceInterface $service
  ) {
    $this->eventDispatcher = $event_dispatcher;
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dispatcher'),
      $container->get('lightning_charge')
    );
  }

  /**
   * Gets the invoice title.
   *
   * @param string $id
   *   Provides the id.
   *
   * @return string
   *   The invoice title.
   */
  public function invoiceTitle($invoice) {
    if (is_string($invoice)) {
      $invoice = $this->service->invoice($invoice);
    }

    $string = 'Invoice';

    $args = [];

    if ($invoice instanceof InvoiceInterface) {
      $id = $invoice->getId();

      if ($id) {
        $args['@id'] = $id;

        $string .= ' @id';
      }
    }

    $string .= ' Details';

    return $this->t($string, $args);
  }

  /**
   * View an invoice.
   *
   * @param InvoiceInterface $invoice
   *   Provides the invoice.
   *
   * @return mixed
   *   The invoice details.
   */
  public function view($invoice) {
    $invoice->setViewMode(LightningChargeConstants::VIEW_MODE_DETAILED);

    return $invoice->toRenderable();
  }

  /**
   * Displays a QR Code.
   *
   * @param string $data
   *   Provides the data.
   *
   * @return mixed
   *   The QR code image.
   */
  public function qrCode($data) {
    $style = new RendererStyle(300);
    $backend = new ImagickImageBackEnd();

    $renderer = new ImageRenderer($style, $backend);

    $writer = new Writer($renderer);

    $content = $writer->writeString($data);

    if ($content) {
      $headers = [];

      $headers['Content-type'] = 'image/png';

      return new Response($content, 200, $headers);
    }

    $message = 'An error occurred generating the QR code';

    throw new NotFoundHttpException($message);
  }

  /**
   * Provides the webhook callback.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Provides the request object.
   */
  public function webhook(Request $request) {
    $id = $request->get('id');
    $token = $request->get('token');

    $result = $this->service->validateToken($id, $token);

    if (!$result) {
      $logger = $this->getLogger('lightning_charge');

      $context = [];

      $context['@token'] = $token;

      $logger->critical('The lightning charge token is invalid. (Token: @token)', $context);

      $message = 'The CSRF token is invalid.';

      throw new LightningChargeException($message);
    }

    $data = $request->getContent();
    $data = json_decode($data);

    $invoice = new Invoice($data);

    $event = new LightningChargeInvoiceUpdateEvent($invoice);

    $this->eventDispatcher->dispatch($event, LightningChargeEvents::INVOICE_UPDATE);

    $args = [];

    $args['@id'] = $id;
    $args['@token'] = $token;

    $content = $this->t('@id @token webhook processed', $args);

    $output = new Response($content);

    return $output;
  }

  /**
   * Provides the javascript callback.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Provides the request object.
   */
  public function js(Request $request) {
    $id = $request->get('id');
    $token = $request->get('token');

    $result = $this->service->validateToken($id, $token);

    if (!$result) {
      $logger = $this->getLogger('lightning_charge');

      $context = [];

      $context['@token'] = $token;

      $logger->critical('The lightning charge token is invalid. (Token: @token)', $context);

      $message = 'The CSRF token is invalid.';

      throw new LightningChargeException($message);
    }

    $data = $request->get('invoice');

    $invoice = new Invoice($data);

    $id = $invoice->getId();

    $invoice = $this->service->invoice($id);

    $event = new LightningChargeInvoiceUpdateEvent($invoice);

    $this->eventDispatcher->dispatch($event, LightningChargeEvents::INVOICE_UPDATE);

    $output = new AjaxResponse();

    $args = [];

    $args['@id'] = $id;

    $message = $this->t('Invoice @id has been paid!', $args);

    $command = new MessageCommand($message);

    $output->addCommand($command);

    $event = new LightningChargeAjaxResponseEvent($invoice, $output);

    $this->eventDispatcher->dispatch($event, LightningChargeEvents::JS);

    return $output;
  }

}
