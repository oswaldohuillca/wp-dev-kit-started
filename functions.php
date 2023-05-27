<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

require_once(__DIR__ . '/vendor/autoload.php');

use Timber\Timber;
use Oswaldohuillca\WpDevKitStarted\core\WpDevKit;

// Initialize Timber.
Timber::init();

// Initialize WpDevKit.
WpDevKit::init();
