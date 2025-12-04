<?php

use Elementor\Plugin;
use elementorWidget\ExampleWidget;

require_once 'class.php';
Plugin::instance()->widgets_manager->register_widget_type(new ExampleWidget());
