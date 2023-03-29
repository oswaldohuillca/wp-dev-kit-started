<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

require(__DIR__ . "/core/index.php");

WpDevKit::load_env();

new WpDevKit();
