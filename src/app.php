<?php

require __DIR__.'/../vendor/autoload.php';

use Stef\Beata\Calculations\Commissions;


Commissions::showFromFile(__DIR__.'/../'.$argv[1]);
