<?php

namespace Drupal\lightning_charge\Events;

use Drupal\Component\EventDispatcher\Event;

/**
 * Provides the LightningChargeMetadataSchemasEvent class.
 */
class LightningChargeMetadataSchemasEvent extends Event {

  /**
   * Provides the metadata schemas.
   *
   * @var array
   */
  protected $schemas = [];

  /**
   * Adds a schema.
   *
   * @param string $key
   *   A string containing the schema key.
   * @param string $title
   *   A string containing the schema title.
   * @param array $schema
   *   An array containing the schema.
   *
   * @return $this
   */
  public function addSchema($key, $title, $schema) {
    $this->schemas[$key] = [
      'title' => $title,
      'schema' => $schema,
    ];

    return $this;
  }

  /**
   * Gets the schemas.
   *
   * @return array
   */
  public function getSchemas() {
    return $this->schemas;
  }

}
