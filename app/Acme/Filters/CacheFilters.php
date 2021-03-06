<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 7/31/16
 * Time: 9:50 AM
 */

namespace Acme\Filters;

use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CacheFilters
{
    public function fetch(Route $route, Request $request)
    {
        $key = self::makeCacheKey($request->url());

        if (Cache::has($key)) {
            return Cache::get($key);
        }
    }

    public function put(Route $route, Request $request, Response $response)
    {
        $key = $this->makeCacheKey($request->url());

        if (! Cache::has($key)) {
            Cache::put($key, $response->getContent(), 10);
        }
    }

    private function makeCacheKey($url)
    {
        return 'route_' . Str::slug($url);
    }
}
