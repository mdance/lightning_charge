<?php

namespace Drupal\lightning_charge;

use Drupal\commerce_price\Price;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Provides the Invoice class.
 */
class Invoice implements InvoiceInterface {

  use StringTranslationTrait;

  /**
   * Provides the id.
   *
   * @var string
   */
  protected $id;

  /**
   * Provides the milisatoshis.
   *
   * @var string
   */
  protected $satoshis;

  /**
   * Provides the currency.
   *
   * @var string
   */
  protected $currency;

  /**
   * Provides the amount.
   *
   * @var string
   */
  protected $amount;

  /**
   * Provides the rhash.
   *
   * @var string
   */
  protected $rhash;

  /**
   * Provides the payment request.
   *
   * @var string
   */
  protected $paymentRequest;

  /**
   * Provides the payment index.
   *
   * @var mixed
   */
  protected $paymentIndex;

  /**
   * Provides the description.
   *
   * @var string
   */
  protected $description;

  /**
   * Provides the metadata.
   *
   * @var mixed
   */
  protected $metadata;

  /**
   * Provides the invoice creation date.
   *
   * @var int
   */
  protected $createDate;

  /**
   * Provides the invoice expiration date.
   *
   * @var int
   */
  protected $expirationDate;

  /**
   * Provides the invoice payment date.
   *
   * @var int
   */
  protected $paymentDate;

  /**
   * Provides the satoshis received.
   *
   * @var int
   */
  protected $satoshisReceived;

  /**
   * Provides the status.
   *
   * @var string
   */
  protected $status;

  /**
   * Provides the view mode.
   *
   * @var string
   */
  protected $viewMode = LightningChargeConstants::VIEW_MODE_DEFAULT;

  /**
   * Provides the module service.
   *
   * @var \Drupal\lightning_charge\LightningChargeServiceInterface
   */
  protected $service;

  /**
   * Invoice constructor.
   *
   * @param null $data
   *   An array of invoice data.
   */
  public function __construct($data = NULL) {
    if ($data) {
      $this->processData($data);
    }
  }

  /**
   * Processes the data.
   *
   * @param mixed $data
   *   The data object.
   *
   * @return $this
   */
  public function processData($data) {
    $mappings = [
      'id',
      'satoshis' => 'msatoshi',
      'currency' => 'quoted_currency',
      'amount' => 'quoted_amount',
      'rhash' => 'rhash',
      'paymentRequest' => 'payreq',
      'paymentIndex' => 'pay_index',
      'description' => 'description',
      'metadata' => 'metadata',
      'createDate' => 'created_at',
      'expirationDate' => 'expires_at',
      'paymentDate' => 'paid_at',
      'satoshisReceived' => 'msatoshi_received',
      'status' => 'status',
    ];

    $results = LightningChargeUtilities::processMappings($data, $mappings);

    foreach ($results as $k => $v) {
      $this->$k = $v;
    }

    $metadata = $this->metadata;

    if (is_object($metadata)) {
      $metadata = (array)$metadata;
    } else if (!is_array($metadata)) {
      $metadata = [];
    }

    $weight = 0;

    foreach ($metadata as $k => $v) {
      $metadata[$k] = new MetaData($k, $v, $weight);
      $weight++;
    }

    $this->metadata = $metadata;
  }

  /**
   * {@inheritDoc}
   */
  public function getId(): string {
    return $this->id ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function setId(string $id) {
    $this->id = $id ?? '';

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getSatoshis(): string {
    return $this->satoshis;
  }

  /**
   * {@inheritDoc}
   */
  public function setSatoshis(string $satoshis) {
    $this->satoshis = $satoshis;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getCurrency(): string {
    return $this->currency ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function setCurrency(string $currency) {
    $this->currency = $currency;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getAmount(): string {
    $output = $this->amount;
    $output = number_format($output, 2, '.', ' ');

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getFormattedAmount(): string {
    $amount = $this->getAmount();

    if ($amount == '0.00') {
      return $this->t('Donation');
    }

    $currency = $this->getCurrency();

    $result = \Drupal::moduleHandler()->moduleExists('commerce_price');

    if ($result) {
      $output = new Price($amount, $currency);
    } else {
      $output = number_format($amount, 2, '.', ' ') . ' ' . $currency;
    }

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function setAmount(string $amount) {
    $this->amount = $amount;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getRhash(): string {
    return $this->rhash ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function setRhash(string $rhash) {
    $this->rhash = $rhash;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getPaymentRequest(): string {
    return $this->paymentRequest ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function setPaymentRequest(string $payment_request) {
    $this->paymentRequest = $payment_request;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getPaymentIndex() {
    return $this->paymentIndex ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function setPaymentIndex($payment_index) {
    $this->paymentIndex = $payment_index;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getDescription(): string {
    return $this->description ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function setDescription(string $description) {
    $this->description = $description;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getMetadata() {
    return $this->metadata ?? [];
  }

  /**
   * {@inheritDoc}
   */
  public function setMetadata($metadata) {
    $this->metadata = $metadata;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getCreateDate(): int {
    return $this->createDate ?? 0;
  }

  /**
   * {@inheritDoc}
   */
  public function getFormattedCreateDate() {
    $output = $this->getCreateDate();

    return $this->formatEpoch($output);
  }

  /**
   * {@inheritDoc}
   */
  public function setCreateDate(int $create_date) {
    $this->createDate = $create_date;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getExpirationDate(): int {
    return $this->expirationDate ?? 3600;
  }

  /**
   * {@inheritDoc}
   */
  public function getFormattedExpirationDate() {
    $output = $this->getExpirationDate();

    return $this->formatEpoch($output);
  }

  /**
   * {@inheritDoc}
   */
  public function setExpirationDate(int $expiration_date) {
    $this->expirationDate = $expiration_date;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function isExpired(): bool {
    $now = time();
    $expiration = $this->getExpirationDate();

    if ($now > $expiration) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function getPaymentDate() {
    return $this->paymentDate ?? 0;
  }

  /**
   * {@inheritDoc}
   */
  public function getFormattedPaymentDate(): string {
    $output = $this->getPaymentDate();

    if ($output == 0) {
      return '';
    }

    return $this->formatEpoch($output);
  }

  /**
   * {@inheritDoc}
   */
  public function setPaymentDate($payment_date) {
    $this->paymentDate = $payment_date;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getSatoshisReceived() {
    return $this->satoshisReceived ?? 0;
  }

  /**
   * {@inheritDoc}
   */
  public function setSatoshisReceived($satoshis_received) {
    $this->satoshisReceived = $satoshis_received;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getStatus(): string {
    return $this->status;
  }

  /**
   * {@inheritDoc}
   */
  public function getFormattedStatus(): string {
    return ucwords($this->status);
  }

  /**
   * {@inheritDoc}
   */
  public function setStatus(string $status) {
    $this->status = $status;

    return $this;
  }

  /**
   * Formats an EPOCH date.
   *
   * @param mixed $input
   *   The EPOCH date.
   *
   * @return
   *   The formatted date.
   */
  private function formatEpoch($input, $format = 'M d, Y h:i A') {
    if (!is_numeric($input)) {
      return '';
    }

    /** @var \Drupal\Core\Datetime\DateFormatterInterface $service */
    $service = \Drupal::service('date.formatter');

    return $service->format($input, 'custom', $format);
  }

  /**
   * {@inheritDoc}
   */
  public function setViewMode($input) {
    $this->viewMode = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getViewMode() {
    return $this->viewMode;
  }

  /**
   * Gets the module service.
   *
   * @return \Drupal\lightning_charge\LightningChargeServiceInterface
   *   The module service
   */
  public function service() {
    if (is_null($this->service)) {
      $this->service = \Drupal::service('lightning_charge');
    }

    return $this->service;
  }

  /**
   * {@inheritDoc}
   */
  public function toRenderable() {
    $output = [];

    $view_mode = $this->getViewMode();

    $value = $this->getFormattedAmount();

    if ($value) {
      $key = 'amount';
      $label = $this->t('Amount: ');

      $output[$key] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => [
            'field',
            'field-' . $key,
          ],
        ],
        'label' => [
          '#markup' => $label,
        ],
        'value' => [
          '#markup' => $value,
        ],
      ];
    }

    $value = $this->getDescription();

    if ($value) {
      $key = 'description';
      $label = $this->t('Description: ');

      $output[$key] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => [
            'field',
            'field-' . $key,
          ],
        ],
        'label' => [
          '#markup' => $label,
        ],
        'value' => [
          '#markup' => $value,
        ],
      ];
    }

    $value = $this->getPaymentRequest();

    if ($value) {
      $key = 'payment-request';
      $label = $this->t('Payment Request: ');

      $route_parameters = [];

      $route_parameters['data'] = $value;

      $options = [];

      $options['absolute'] = TRUE;

      $src = Url::fromRoute('lightning_charge.qrcode', $route_parameters, $options);
      $src = $src->toString();

      $output[$key] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => [
            'field',
            'field-' . $key,
          ],
        ],
        //'label' => [
        //  '#markup' => $label,
        //],
        'value' => [
          '#type' => 'html_tag',
          '#tag' => 'img',
          '#attributes' => [
            'src' => $src,
            'alt' => $value,
          ],
        ],
      ];
    }

    if ($view_mode == LightningChargeConstants::VIEW_MODE_DETAILED) {
      $value = $this->getId();

      if ($value) {
        $key = 'id';
        $label = $this->t('ID: ');

        $output[$key] = [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'field',
              'field-' . $key,
            ],
          ],
          'label' => [
            '#markup' => $label,
          ],
          'value' => [
            '#markup' => $value,
          ],
        ];
      }

      $value = $this->getFormattedCreateDate();

      if ($value) {
        $key = 'created-date';
        $label = $this->t('Invoice Date: ');

        $output[$key] = [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'field',
              'field-' . $key,
            ],
          ],
          'label' => [
            '#markup' => $label,
          ],
          'value' => [
            '#markup' => $value,
          ],
        ];
      }


      $value = $this->getFormattedExpirationDate();

      if ($value) {
        $key = 'expiration-date';
        $label = $this->t('Expiration Date: ');

        $output[$key] = [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'field',
              'field-' . $key,
            ],
          ],
          'label' => [
            '#markup' => $label,
          ],
          'value' => [
            '#markup' => $value,
          ],
        ];
      }

      $value = $this->getFormattedStatus();

      if ($value) {
        $key = 'status';
        $label = $this->t('Status: ');

        $output[$key] = [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'field',
              'field-' . $key,
            ],
          ],
          'label' => [
            '#markup' => $label,
          ],
          'value' => [
            '#markup' => $value,
          ],
        ];
      }

      $value = $this->getFormattedPaymentDate();

      if ($value) {
        $key = 'payment-date';
        $label = $this->t('Payment Date: ');

        $output[$key] = [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'field',
              'field-' . $key,
            ],
          ],
          'label' => [
            '#markup' => $label,
          ],
          'value' => [
            '#markup' => $value,
          ],
        ];

        $value = $this->getSatoshisReceived();

        if ($value) {
          $key = 'satoshis-received';
          $label = $this->t('Payment Received: ');

          $output[$key] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => [
                'field',
                'field-' . $key,
              ],
            ],
            'label' => [
              '#markup' => $label,
            ],
            'value' => [
              '#markup' => $value,
            ],
          ];
        }
      }

      $value = $this->getRhash();

      if ($value) {
        $key = 'rhash';
        $label = $this->t('RHash: ');

        $output[$key] = [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'field',
              'field-' . $key,
            ],
          ],
          'label' => [
            '#markup' => $label,
          ],
          'value' => [
            '#markup' => $value,
          ],
        ];
      }

      $value = $this->getPaymentIndex();

      if ($value) {
        $key = 'payment-index';
        $label = $this->t('Payment Index: ');

        $output[$key] = [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'field',
              'field-' . $key,
            ],
          ],
          'label' => [
            '#markup' => $label,
          ],
          'value' => [
            '#markup' => $value,
          ],
        ];
      }

      $metadata = $this->getMetadata();

      if ($metadata) {
        $total = count($metadata);

        $schemas = $this->service()->getMetadataSchemas();

        $items = [];

        $schema = NULL;

        foreach ($metadata as $k => $v) {
          if ($v instanceof MetaData) {
            $v = $v->getValue();
          }

          if ($k == LightningChargeConstants::KEY_TYPE) {
            if (isset($schemas[$v])) {
              $schema = $schemas[$v];
            }
          }

          $item = [
            'key' => $k,
            'value' => $v,
          ];

          $items[$k] = $item;
        }

        $rows = [];

        if (!$schema) {
          $rows = $items;
        } else {
          $rows[LightningChargeConstants::KEY_TYPE] = [
            'key' => [
              'data' => $this->t('Type'),
            ],
            'value' => [
              'data' => $schema['title'],
            ],
          ];

          $mappings = $schema['schema'];

          foreach ($items as $k => $v) {
            if ($k == LightningChargeConstants::KEY_TYPE) {
              continue;
            }

            $key = $mappings[$k] ?? $k;

            $v['key'] = [
              'data' => $key,
            ];

            $rows[$k] = $v;
          }
        }

        $header = [];

        $header['key'] = $this->t('Key');
        $header['value'] = $this->t('Value');

        $output['metadata'] = [
          '#type' => 'table',
          '#caption' => $this->t('Metadata'),
          '#header' => $header,
          '#rows' => $rows,
        ];
      }
    }

    return $output;
  }

}
