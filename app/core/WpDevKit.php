<?php

namespace Oswaldo\WpDevKit\core;

use Dotenv\Dotenv;
use Oswaldo\WpDevKit\core\BuildDir;
use Oswaldo\WpDevKit\core\Api;

class WpDevKit
{
  public static function init()
  {
    WpDevKit::load_env();

    $build_dir = new BuildDir();
    $build_dir->get();

    self::get_reusable_functions();

    $api = new Api;
    $api->create();
  }

  /**
   * read and load .env eviroments
   */
  private static function load_env()
  {
    if (!WpDevKit::find_env_file(".env") && !WpDevKit::find_env_file(".env.production")) return;

    $dotenv = Dotenv::createUnsafeMutable(dirname(dirname(__DIR__)));
    $dotenv->load();
  }

  private static function find_env_file(string $filename): bool
  {
    return file_exists(dirname(dirname(__DIR__)) . "/$filename");
  }

  /**
   * get core reusable functions
   */

  private static function get_reusable_functions()
  {
    include_once dirname(__DIR__) . '/helpers/helpers.php';
  }
}
