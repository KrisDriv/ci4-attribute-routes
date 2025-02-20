<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes\AttributeReader;

use Kenjis\CI4\AttributeRoutes\Route;
use ReflectionClass;

final class MethodReader
{
    /**
     * @param class-string $class
     *
     * @return Route[]
     */
    public function getRoutes(string $class): array
    {
        $reflection = new ReflectionClass($class);

        $routes = [];

        foreach ([...$reflection->getMethods()] as $method) {
            $attributes = $method->getAttributes(Route::class);

            if ($attributes === []) {
                continue;
            }

            foreach ($attributes as $attribute) {
                /** @var Route $route */
                $route = $attribute->newInstance();
                $route->setControllerMethod(
                    $reflection->getName() . '::' . $method->getName()
                );

                $routes[] = $route;
            }
        }

        return $routes;
    }
}