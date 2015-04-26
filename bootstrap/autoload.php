<?php
use Symfony\Component\ClassLoader\Psr4ClassLoader;

if(file_exists(__DIR__.'/../vendor/autoload.php')){
    require(__DIR__.'/../vendor/autoload.php');
}
else{
    require __DIR__.'/../lib/ClassLoader/Psr4ClassLoader.php';

    $loader = new Psr4ClassLoader();
    $loader->addPrefix('Maiorano\\Shortcodes\\', __DIR__.'/../src');
    $loader->register();
}
