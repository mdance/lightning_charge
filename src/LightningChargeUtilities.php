<?php

namespace Drupal\lightning_charge;

/**
 * Provides the LightningChargeUtilities class.
 */
class LightningChargeUtilities {

  /**
   * Returns an array of mapping defaults.
   *
   * @return array
   *   An array of mapping defaults.
   */
  public static function getMappingDefaults() {
    $output = [
      'key' => NULL,
      'check_defaults' => FALSE,
      'defaults_key' => NULL,
      'default' => NULL,
      'postprocess' => NULL,
      'filter_null' => TRUE,
    ];

    return $output;
  }

  /**
   * Processes data mappings.
   *
   * @param mixed $input
   *   The input to be processed.
   * @param array $mappings
   *   The mappings array.
   * @param array $defaults
   *   An array of default values.
   * @param array $mapping_defaults
   *   The mapping defaults.
   *
   * @return array
   *   The processed data.
   */
  public static function processMappings($input, array $mappings, array $defaults = [], array $mapping_defaults = NULL) {
    $output = [];

    foreach ($mappings as $k => $v) {
      if (is_numeric($k)) {
        $k = $v;
      }

      if (!is_array($v)) {
        $v = [
          'key' => $v,
        ];
      }

      if (!isset($v['key'])) {
        $v['key'] = $k;
      }

      if (is_null($mapping_defaults)) {
        $mapping_defaults = static::getMappingDefaults();
      }

      $v = array_merge($mapping_defaults, $v);

      $key = $v['key'];
      $check_defaults = $v['check_defaults'];
      $defaults_key = $v['defaults_key'];
      $default = $v['default'];
      $postprocess = $v['postprocess'];
      $filter_null = $v['filter_null'];

      if (!is_array($key)) {
        $key = [
          $key,
        ];
      }

      if (is_object($input)) {
        $array = clone $input;
        $array = (array)$array;

        $result = NestedData::getValue($array, $key, $key_exists);
      } else {
        $result = NestedData::getValue($input, $key, $key_exists);
      }

      if (!$key_exists) {
        if ($check_defaults) {
          if (!$defaults_key) {
            $defaults_key = $key;
          }

          if (!is_array($defaults_key)) {
            $defaults_key = [
              $defaults_key,
            ];
          }

          $result = NestedData::getValue($defaults, $defaults_key, $key_exists);
        }
      }

      if (is_null($result)) {
        $result = $default;
      }

      if (is_callable($postprocess)) {
        $result = $postprocess($result, $output);
      }

      if (is_null($result) && $filter_null) {
        continue;
      }

      $output[$k] = $result;
    }

    return $output;
  }

  /**
   * Converts seconds to a interval.
   *
   * @param int $input
   *
   * @return \DateInterval
   */
  public static function secondsToInterval(int $input) {
    // https://stackoverflow.com/questions/8273804/convert-seconds-into-days-hours-minutes-and-seconds/12118442
    $a = new \DateTime('@0');
    $b = new \DateTime("@$input");

    $diff = $a->diff($b);

    $days = $diff->format('%a');
    $hours = $diff->format('%h');
    $minutes = $diff->format('%i');
    $seconds = $diff->format('%s');

    $output = new \DateInterval("P${days}DT${hours}H${minutes}M${seconds}S");

    return $output;
  }

}
