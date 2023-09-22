<?php declare(strict_types=1);

use Shopware\Core\TestBootstrapper;

$loader = (new TestBootstrapper())
    ->addCallingPlugin()
    ->addActivePlugins('CustomListing')
    ->bootstrap()
    ->getClassLoader();

$loader->addPsr4('CustomListing\\Tests\\', __DIR__);
