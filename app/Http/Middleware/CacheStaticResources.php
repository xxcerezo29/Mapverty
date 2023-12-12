<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheStaticResources
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if($response->isSuccessful() && $this->isStaticResource($request->getPathInfo())){
            $response->header('Cache-Control', 'public, max-age=31536000');
        }

        return $response;
    }

    private function isStaticResource($path){
        return preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg)$/i', $path);

//        return preg_match('/\.(?:png|jpg|jpeg|css|js|ico|woff|woff2|ttf|svg|eot)$/', $path);
    }
}
