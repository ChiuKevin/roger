<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    use ResponseTrait;

    private $methodActionMapping = [
        'get'    => 'read',
        'post'   => 'create',
        'put'    => 'update',
        'patch'  => 'update',
        'delete' => 'delete',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $admin_user = Auth::guard('admin')->user();

        if (!$admin_user) {
            return $this->error(__('error.401'), 401);
        }

        $action = $this->methodActionMapping[strtolower($request->method())] ?? null;

        if (!$action) {
            return $this->error(__('error.403'), 403);
        }

        $resource = $this->extractResource($request);
        $permission = "{$action}-{$resource}";

        if (!$admin_user->can($permission)) {
            return $this->error(__('error.403'), 403);
        }

        return $next($request);
    }

    private function extractResource(Request $request): string
    {
        $route = $request->route();
        $uriParts = explode('/', $route->uri());
        if (end($uriParts) === 'filter' && count($uriParts) > 1) {
            array_pop($uriParts);
        }
        $resource = Str::singular(end($uriParts));

        if (count($route->parameters()) > 0 && count($uriParts) > 1) {
            $resource = Str::singular($uriParts[count($uriParts) - 2]);
        }

        return $resource;
    }
}
