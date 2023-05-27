<?php

namespace Oswaldohuillca\WpDevKitStarted\core;

use Dotenv\Dotenv;
use Oswaldohuillca\WpDevKitStarted\core\BuildDir;
use Oswaldohuillca\WpDevKitStarted\core\Api;

class WpDevKit
{
  public static function init()
  {
    $dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
    $dotenv->load();

    $build_dir = new BuildDir();
    $build_dir->get();

    self::get_reusable_functions();

    $api = new Api;
    $api->create();
  }


  /**
   * get core reusable functions
   */

  private static function get_reusable_functions()
  {
    include_once dirname(__DIR__) . '/helpers/helpers.php';
  }
}
