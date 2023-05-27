<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('views/single.twig', $context);
