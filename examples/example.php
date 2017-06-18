<?php

require __DIR__.'/../vendor/autoload.php';

use function Send\send;

echo(send(['url' => 'http://example.org']));
