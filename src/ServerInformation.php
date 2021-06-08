<?php

namespace Drupal\lightning_charge;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides the ServerInformation class.
 */
class ServerInformation implements ServerInformationInterface {

  use StringTranslationTrait;

  /**
   * Provides the id.
   *
   * @var string
   */
  protected $id;

  /**
   * Provides the alias.
   *
   * @var string
   */
  protected $alias;

  /**
   * Provides the color.
   *
   * @var string
   */
  protected $color;

  /**
   * Provides the peers.
   *
   * @var string
   */
  protected $peers;

  /**
   * Provides the pending channels.
   *
   * @var string
   */
  protected $pendingChannels;

  /**
   * Provides the active channels.
   *
   * @var string
   */
  protected $activeChannels;

  /**
   * Provides the inactive chanels.
   *
   * @var mixed
   */
  protected $inactiveChannels;

  /**
   * Provides the addresses.
   *
   * @var string
   */
  protected $addresses;

  /**
   * Provides the bindings.
   *
   * @var mixed
   */
  protected $bindings;

  /**
   * Provides the version.
   *
   * @var int
   */
  protected $version;

  /**
   * Provides the block height.
   *
   * @var int
   */
  protected $blockHeight;

  /**
   * Provides the network.
   *
   * @var int
   */
  protected $network;

  /**
   * Provides the fees collected.
   *
   * @var int
   */
  protected $feesCollected;

  /**
   * Provides the directory.
   *
   * @var string
   */
  protected $directory;

  /**
   * Provides the constructor.
   *
   * @param mixed $data
   *   An array of data to process.
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
      'alias',
      'color',
      'peers' => 'num_peers',
      'pendingChannels' => 'num_pending_channels',
      'activeChannels' => 'num_active_chanels',
      'inactiveChannels' => 'num_inactive_chanels',
      'addresses' => 'address',
      'bindings' => 'binding',
      'version',
      'blockHeight' => 'blockheight',
      'network',
      'feesCollected' => 'msatoshi_fees_collected',
      'directory' => 'lightning-dir',
    ];

    $results = LightningChargeUtilities::processMappings($data, $mappings);

    foreach ($results as $k => $v) {
      $this->$k = $v;
    }

    $map = [
      'addresses' => Address::class,
      'bindings' => Binding::class,
    ];

    foreach ($map as $key => $class) {
      if (is_array($this->$key)) {
        foreach ($this->$key as $k => $v) {
          $this->$key[$k] = new $class($v);
        }
      }
    }
  }

  /**
   * {@inheritDoc}
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * {@inheritDoc}
   */
  public function setId(string $input) {
    $this->id = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getAlias(): string {
    return $this->alias;
  }

  /**
   * {@inheritDoc}
   */
  public function setAlias(string $input) {
    $this->alias = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getColor(): string {
    return $this->color;
  }

  /**
   * {@inheritDoc}
   */
  public function setColor(string $input) {
    $this->color = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getPeers(): int {
    return $this->peers ?? 0;
  }

  /**
   * {@inheritDoc}
   */
  public function setPeers($input) {
    $this->peers = $input ?? 0;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getPendingChannels(): int {
    return $this->pendingChannels ?? 0;
  }

  /**
   * {@inheritDoc}
   */
  public function setPendingChannels($input) {
    $this->pendingChannels = $input ?? 0;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getActiveChannels() {
    return $this->activeChannels ?? 0;
  }

  /**
   * {@inheritDoc}
   */
  public function setActiveChannels($input) {
    $this->activeChannels = $input ?? 0;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getInactiveChannels(): int {
    return $this->inactiveChannels ?? 0;
  }

  /**
   * {@inheritDoc}
   */
  public function setInactiveChannels($input) {
    $this->inactiveChannels = $input ?? 0;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getAddresses(): array {
    return $this->addresses;
  }

  /**
   * {@inheritDoc}
   */
  public function setAddresses($input) {
    $this->addresses = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getBindings() {
    return $this->bindings;
  }

  /**
   * {@inheritDoc}
   */
  public function setBindings($input) {
    $this->bindings = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getVersion(): string {
    return $this->version;
  }

  /**
   * {@inheritDoc}
   */
  public function setVersion($input) {
    $this->version = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getBlockHeight(): int {
    return $this->blockHeight;
  }

  /**
   * {@inheritDoc}
   */
  public function setBlockHeight(int $input) {
    $this->blockHeight = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getNetwork() {
    return $this->network;
  }

  /**
   * {@inheritDoc}
   */
  public function setNetwork($input) {
    $this->network = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getFeesCollected() {
    return $this->feesCollected;
  }

  /**
   * {@inheritDoc}
   */
  public function setFeesCollected($input) {
    $this->feesCollected = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getDirectory(): string {
    return $this->directory;
  }

  /**
   * {@inheritDoc}
   */
  public function setDirectory(string $input) {
    $this->directory = $input;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function toRenderable() {
    $output = [];

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

    $value = $this->getAlias();

    if ($value) {
      $key = 'alias';
      $label = $this->t('Alias: ');

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

    $value = $this->getColor();

    if ($value) {
      $key = 'color';
      $label = $this->t('Color: ');

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

    $value = $this->getPeers();

    if ($value) {
      $key = 'peers';
      $label = $this->t('Peers: ');

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

    $value = $this->getPendingChannels();

    if ($value) {
      $key = 'pending-channels';
      $label = $this->t('Pending Channels: ');

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

    $value = $this->getActiveChannels();

    if ($value) {
      $key = 'active-channels';
      $label = $this->t('Active Channels: ');

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

    $value = $this->getInactiveChannels();

    if ($value) {
      $key = 'inactive-channels';
      $label = $this->t('Inactive Channels: ');

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

    $values = $this->getAddresses();

    if ($values) {
      $key = 'addresses';
      $label = $this->t('Addresses: ');

      $header = [];

      $header['type'] = $this->t('Type');
      $header['address'] = $this->t('Address');
      $header['port'] = $this->t('Port');

      $rows = [];

      foreach ($values as $value) {
        $row = [];

        $row['type'] = $value->getType();
        $row['address'] = $value->getAddress();
        $row['port'] = $value->getPort();

        $rows[] = $row;
      }

      $output[$key] = [
        '#type' => 'table',
        '#caption' => $label,
        '#header' => $header,
        '#rows' => $rows,
        '#attributes' => [
          'class' => [
            'field',
            'field-' . $key,
          ],
        ],
      ];
    }

    $values = $this->getBindings();

    if ($values) {
      $key = 'bindings';
      $label = $this->t('Bindings: ');

      $header = [];

      $header['type'] = $this->t('Type');
      $header['address'] = $this->t('Address');
      $header['port'] = $this->t('Port');

      $rows = [];

      foreach ($values as $value) {
        $row = [];

        $row['type'] = $value->getType();
        $row['address'] = $value->getAddress();
        $row['port'] = $value->getPort();

        $rows[] = $row;
      }

      $output[$key] = [
        '#type' => 'table',
        '#caption' => $label,
        '#header' => $header,
        '#rows' => $rows,
        '#attributes' => [
          'class' => [
            'field',
            'field-' . $key,
          ],
        ],
      ];
    }

    $value = $this->getVersion();

    if ($value) {
      $key = 'version';
      $label = $this->t('Version: ');

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

    $value = $this->getBlockHeight();

    if ($value) {
      $key = 'block-height';
      $label = $this->t('Block Height: ');

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

    $value = $this->getNetwork();

    if ($value) {
      $key = 'network';
      $label = $this->t('Network: ');

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

    $value = $this->getFeesCollected();

    if ($value) {
      $key = 'fees-collected';
      $label = $this->t('Fees Collected: ');

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

    $value = $this->getDirectory();

    if ($value) {
      $key = 'directory';
      $label = $this->t('Directory: ');

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

    return $output;
  }

}
