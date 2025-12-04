<?php

foreach (glob(__DIR__ . '/posttypes/*.php') as $filename) {
    require_once $filename;
}
