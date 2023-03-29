<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

require(__DIR__ . "/utils/index.php");

WpDevKit::load_env();

new WpDevKit();
