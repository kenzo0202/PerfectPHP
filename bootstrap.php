<?php
require('core/ClassLoader.php');

$loader = new ClassLoader;
$loader->registerDir(dir(__FILE__).'/core');
$loader->registerDir(dir(__FILE__).'/models');
$loader->register();

?>