<?php namespace victorycto\ImageStore\Tests;

use \Orchestra\Testbench\TestCase as Base;
use victorycto\ImageStore\ImageStoreServiceProvider;

abstract class TestCase extends Base
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');
    }
    /**
     * @inheritdoc
     */
    protected function getPackageProviders($app)
    {
        return [
            ImageStoreServiceProvider::class,
        ];
    }
}
