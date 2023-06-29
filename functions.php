<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

require(__DIR__ . "/core/core.php");

WpDevKit::load_env();

new WpDevKit();
