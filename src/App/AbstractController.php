<?php
namespace App;

use Slim\Container;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class AbstractController
{
    /**
     * Dependency Injection Container
     */
    protected $container = null;

    /**
     * Constuctor
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
