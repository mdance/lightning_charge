<?php

namespace Drupal\lightning_charge\Form;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Render\RenderableInterface;
use Drupal\Core\State\StateInterface;
use Drupal\lightning_charge\LightningChargeConstants;
use Drupal\lightning_charge\LightningChargeServiceInterface;
use Drupal\lightning_charge\LightningChargeUtilities;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the SettingsForm class.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Provides the state service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface|\Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Provides the module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

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
    ConfigFactoryInterface $config_factory,
    StateInterface $state,
    ModuleHandlerInterface $module_handler,
    LightningChargeServiceInterface $service
  ) {
    parent::__construct($config_factory);

    $this->state = $state;
    $this->moduleHandler = $module_handler;

    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('state'),
      $container->get('module_handler'),
      $container->get('lightning_charge')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lightning_charge_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      LightningChargeConstants::KEY_SETTINGS
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $form = parent::buildForm($form, $form_state);

    $form['#tree'] = TRUE;

    $key = 'settings';

    $form[$key] = [
      '#type' => 'details',
      '#title' => $this->t('Settings'),
      '#open' => TRUE,
    ];

    $container = &$form[$key];

    $key = 'mode';

    $default_value = $this->service->getMode();
    $options = $this->service->getModeOptions();

    $selector_mode = $key;

    $container[$key] = [
      '#type' => 'radios',
      '#title' => $this->t('Mode'),
      '#default_value' => $default_value,
      '#options' => $options,
      '#attributes' => [
        'class' => [
          $selector_mode,
        ],
      ],
    ];

    $modes = [
      LightningChargeConstants::MODE_TEST => $this->t('Test Settings'),
      LightningChargeConstants::MODE_LIVE => $this->t('Live Settings'),
    ];

    foreach ($modes as $mode => $title) {
      $container[$mode] = [
        '#type' => 'details',
        '#title' => $title,
        '#open' => TRUE,
        '#states' => [
          'visible' => [
            ".$selector_mode" => [
              'value' => $mode,
            ],
          ],
        ],
      ];

      $subcontainer = &$container[$mode];

      $key = 'schema';

      $default_value = $this->service->getSchema($mode);
      $options = $this->service->getSchemaOptions();

      $subcontainer[$key] = [
        '#type' => 'select',
        '#title' => $this->t('Schema'),
        '#default_value' => $default_value,
        '#options' => $options,
      ];

      $key = 'host';

      $default_value = $this->service->getHost($mode);

      $subcontainer[$key] = [
        '#type' => 'textfield',
        '#title' => $this->t('Host'),
        '#default_value' => $default_value,
      ];

      $key = 'port';

      $default_value = $this->service->getPort($mode);

      $subcontainer[$key] = [
        '#type' => 'number',
        '#title' => $this->t('Port'),
        '#min' => 0,
        '#default_value' => $default_value,
      ];

      $key = 'username';

      $default_value = $this->service->getUsername($mode);

      $subcontainer[$key] = [
        '#type' => 'textfield',
        '#title' => $this->t('Username'),
        '#default_value' => $default_value,
      ];

      $key = 'token';

      $default_value = $this->service->getToken($mode);

      $subcontainer[$key] = [
        '#type' => 'textfield',
        '#title' => $this->t('Token'),
        '#default_value' => $default_value,
      ];

      $key = 'webhook';

      $default_value = $this->service->getWebHook($mode);

      $subcontainer[$key] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable Webhooks'),
        '#default_value' => $default_value,
      ];

      $key = 'webhook_type';

      $default_value = $this->service->getWebHookType($mode);

      $options = [
        LightningChargeConstants::WEBHOOK_DEFAULT => $this->t('Default'),
        LightningChargeConstants::WEBHOOK_OTHER => $this->t('Other'),
      ];

      $selector = "field-webhook-url-type-$mode";

      $subcontainer[$key] = [
        '#type' => 'radios',
        '#title' => $this->t('Webhook URL'),
        '#default_value' => $default_value,
        '#options' => $options,
        '#attributes' => [
          'class' => [
            $selector,
          ],
        ],
      ];

      $key = 'webhook_url_custom';

      $default_value = $this->service->getWebSocketUrl($mode);

      $subcontainer[$key] = [
        '#type' => 'textfield',
        '#title' => $this->t('Custom Webhook URL'),
        '#default_value' => $default_value,
        '#states' => [
          'visible' => [
            ".$selector" => [
              'value' => LightningChargeConstants::WEBHOOK_OTHER,
            ],
          ],
        ],
      ];

      $key = 'websocket';

      $default_value = $this->service->getWebSocket($mode);
      $selector = "websocket-$mode";

      $subcontainer[$key] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable WebSockets'),
        '#default_value' => $default_value,
        '#attributes' => [
          'class' => [
            $selector,
          ],
        ],
      ];

      $key = 'websocket_url';

      $default_value = $this->service->getWebSocketUrl($mode);

      $subcontainer[$key] = [
        '#type' => 'textfield',
        '#title' => $this->t('WebSockets URL'),
        '#default_value' => $default_value,
        '#states' => [
          'visible' => [
            ".$selector" => [
              'checked' => TRUE,
            ],
          ],
        ],
      ];

      $key = 'payment_stream';

      $default_value = $this->service->getPaymentStream($mode);
      $selector = "payment-stream-$mode";

      $subcontainer[$key] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable Payment Stream'),
        '#default_value' => $default_value,
        '#attributes' => [
          'class' => [
            $selector,
          ],
        ],
      ];

      $key = 'payment_stream_url';

      $default_value = $this->service->getPaymentStreamUrl($mode);

      $subcontainer[$key] = [
        '#type' => 'textfield',
        '#title' => $this->t('Payment Stream URL'),
        '#default_value' => $default_value,
        '#states' => [
          'visible' => [
            ".$selector" => [
              'checked' => TRUE,
            ],
          ],
        ],
      ];

      unset($subcontainer);
    }

    $key = 'default_currency';

    $options = $this->service->getCurrencies();
    $default_value = $this->service->getDefaultCurrency();

    $container[$key] = [
      '#type' => 'select',
      '#title' => $this->t('Default Currency'),
      '#options' => $options,
      '#default_value' => $default_value,
    ];

    $key = 'default_expiry';

    $type = 'number';

    $default_value = $this->service->getDefaultExpiry();

    if ($this->moduleHandler->moduleExists('duration_field')) {
      $type = 'duration';

      $default_value = LightningChargeUtilities::secondsToInterval($default_value);
    }

    $container[$key] = [
      '#type' => $type,
      '#title' => $this->t('Default Expiration'),
      '#default_value' => $default_value,
      '#granularity' => 'd:h:i',
    ];

    try {
      // @todo Add modes output
      // @todo Conver to renderable
      $result = $this->service->getServerInformation();

      if ($result instanceof RenderableInterface) {
        $key = 'info';

        $form[$key] = [
          '#type' => 'details',
          '#title' => $this->t('Connection Details'),
          '#open' => TRUE,
        ];

        $container = &$form[$key];

        $render = $result->toRenderable();

        $container = array_merge($container, $render);
      }
    } catch (\Exception $e) {
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();

    unset($values['action']);

    $config = $this->config(LightningChargeConstants::KEY_SETTINGS);

    $base = [
      'settings',
    ];

    $modes = [
      LightningChargeConstants::MODE_TEST,
      LightningChargeConstants::MODE_LIVE,
    ];

    foreach ($modes as $mode) {
      $key = 'token';

      $parents = $base;
      $parents[] = $mode;
      $parents[] = $key;

      $result = NestedArray::getValue($values, $parents);

      if ($result) {
        $key = LightningChargeConstants::KEY_TOKEN;

        if ($mode == LightningChargeConstants::MODE_TEST) {
          $key .= '_' . $mode;
        }

        $this->state->set($key, $result);
      }

      NestedArray::unsetValue($values, $parents);
    }

    $parents = $base;
    $parents[] = 'default_expiry';

    $default_expiry = &NestedArray::getValue($values, $parents);

    if ($default_expiry instanceof \DateInterval) {
      $now = new \DateTimeImmutable();
      $after = $now->add($default_expiry);

      $default_expiry = $after->getTimestamp() - $now->getTimestamp();
    }

    $values = array_merge($values, $values['settings']);
    unset($values['settings']);

    foreach ($values as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }

}
