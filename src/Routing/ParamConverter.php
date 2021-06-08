<?php

namespace Drupal\lightning_charge\Routing;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Drupal\lightning_charge\LightningChargeServiceInterface;
use Symfony\Component\Routing\Route;

/**
 * Provides the ParamConverter class.
 */
class ParamConverter implements ParamConverterInterface {

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
    LightningChargeServiceInterface $service
  ) {
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    $output = NULL;

    if (!empty($value)) {
      try {
        $output = $this->service->invoice($value);
      } catch (\Exception $e) {}
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'lightning_charge_invoice');
  }

}
