<?php

use Timber\Timber;

$context = Timber::context();
// echo "<pre>";
// print_r($context);
// echo "</pre>";

Timber::render('base.twig', $context);
