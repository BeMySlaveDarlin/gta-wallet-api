<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Application;

use Laminas\Diactoros\ServerRequest;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\StrategyInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class Dispatcher
{
    public const TYPE_ROUTE = 'route';
    public const TYPE_ROUTE_GROUP = 'routeGroup';

    public function __construct(
        private readonly Router $router,
        private readonly StrategyInterface $strategy,
        private readonly ContainerInterface $container,
        private readonly Config $config
    ) {
        $this->router->setStrategy($this->strategy);

        $routes = $this->config->get(Config::ROUTES);
        foreach ($routes as $item) {
            if ($item['type'] === self::TYPE_ROUTE) {
                foreach ($item['methods'] as $method) {
                    $this->router->map(
                        method: $method,
                        path: $item['path'],
                        handler: $this->container->get($item['handler'])
                    );
                }
            }
            if ($item['type'] === self::TYPE_ROUTE_GROUP) {
                $this->router->group(
                    prefix: $item['path'],
                    group: function (RouteGroup $route) use ($item) {
                        foreach ($item['routes'] as $itemRoute) {
                            foreach ($itemRoute['methods'] as $method) {
                                $route->map(
                                    method: $method,
                                    path: $itemRoute['path'],
                                    handler: $this->container->get($itemRoute['handler'])
                                );
                            }
                        }
                    }
                );
            }
        }
    }

    public function dispatch(ServerRequest $request): ResponseInterface
    {
        return $this->router->dispatch($request);
    }
}
