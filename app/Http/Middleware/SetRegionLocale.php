<?php

namespace App\Http\Middleware;

use App\Rules\RegionLocaleRule;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Validator;

class SetRegionLocale
{
    /**
     * Handle an incoming header.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headers = collect(request()->headers->all())->mapWithKeys(function ($value, $key) {
            return [$key => $value[0]];
        })->toArray();

        //驗證 header 合法性
        $rules = ([
            'region' => ['sometimes', new RegionLocaleRule()],
            'locale' => ['sometimes', new RegionLocaleRule()],
        ]);
        $validator = Validator::make($headers, $rules);
        throw_if($validator->fails(), ValidationException::class, $validator);

        $region = $request->header('region', 'hk');
        $locale = $request->header('locale', 'zh_hk');

        App::setLocale(str_starts_with($locale, 'zh') ? 'zh' : $locale);

        app()->instance('region', $region);
        app()->instance('locale', $locale);

        return $next($request);
    }
}
