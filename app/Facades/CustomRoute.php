<?php

namespace App\Facades;

use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Route as BaseRoute;

class CustomRoute extends BaseRoute
{
    public static function customApiResource(string $name, string $controller)
    {
        static::get("$name/search", [$controller, 'search']);

        return static::apiResource($name, $controller);
    }

    public static function customResource(string $name, string $controller, string $routeName = ''): PendingResourceRegistration
    {
        static::get("$name/fetch", [$controller, 'fetch'])->name("$routeName.fetch");
        $resource = static::resource($name, $controller);

        if (! empty($routeName)) {
            $resource->names([
                'index' => "$routeName.index",
                'create' => "$routeName.create",
                'store' => "$routeName.store",
                'show' => "$routeName.show",
                'edit' => "$routeName.edit",
                'update' => "$routeName.update",
                'destroy' => "$routeName.destroy",
            ]);
        }

        return $resource;
    }
}
