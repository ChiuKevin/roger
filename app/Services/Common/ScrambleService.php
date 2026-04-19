<?php

namespace App\Services\Common;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\RouteInfo;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Parameter;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Extensions\OperationExtension;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class ScrambleService extends OperationExtension
{
    public static function configure(): void
    {
        self::configureRoutes();
        self::configureSecurity();
    }

    private static function configureRoutes(): void
    {
        $processedFunctions = collect();

        Scramble::routes(function (Route $route) use ($processedFunctions): bool {
            $uri = $route->uri();

            $clients = ['admin', 'consumer', 'web', 'pro'];
            $specials = ['constants', 'sms'];
            $prefixes = array_merge($clients, $specials);

            if (Str::startsWith($uri, $prefixes)) {
                $action = $route->getActionName();

                if (!$processedFunctions->contains($action)) {
                    $processedFunctions->push($action);
                    return true;
                }
            }

            return false;
        });
    }

    private static function configureSecurity(): void
    {
        Scramble::extendOpenApi(function (OpenApi $openApi): void {
            $openApi->secure(
                SecurityScheme::http('bearer', 'JWT')
            );
        });
    }

    public function handle(Operation $operation, RouteInfo $routeInfo): void
    {
        $uri = $routeInfo->route->uri;
        $client = ucfirst(explode('/', trim($uri, '/'))[0]);
        list($controller) = explode('@', $routeInfo->route->action['controller']);
        $class = str_replace('Controller', '', class_basename($controller));
        $operation->tags[0] = $client . ' - ' . $class;

        if (strpos($operation->path, 'admin') === 0) {
            $operation->addParameters([
                Parameter::make('region', 'header')
                    ->setSchema(
                        Schema::fromType(new StringType())
                    ),
                Parameter::make('locale', 'header')
                    ->setSchema(
                        Schema::fromType(new StringType())
                    )
            ]);
        }
    }
}
