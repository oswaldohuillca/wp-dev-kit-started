<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

require(__DIR__ . "/utils/config.php");

WpDevKit::load_env();

new WpDevKit();
