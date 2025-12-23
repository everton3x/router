<?php

namespace Router;

use DomainException;

/**
 * Classe estática base do Router.
 */
final class Route
{
    private static array $routes = [];
    private static array $paramStore = [];

    /**
     * Registra uma rota para método GET.
     *
     * @param string $route
     * @param string $controller
     * @param string|null $method
     * @return RouteDescriptor
     */
    public static function get(string $route, string $controller, ?string $method = null): RouteDescriptor
    {
        return self::route('GET', $route, $controller, $method);
    }
    
    /**
     * Registra uma rota para método POST.
     *
     * @param string $route
     * @param string $controller
     * @param string|null $method
     * @return RouteDescriptor
     */
    public static function post(string $route, string $controller, ?string $method = null): RouteDescriptor
    {
        return self::route('POST', $route, $controller, $method);
    }

    private static function route(string $requestMethod, string $route, string $controller, ?string $method = null): RouteDescriptor
    {
        $descriptor = new RouteDescriptor($requestMethod, $route, $controller, $method);
        self::$routes[strtoupper($requestMethod).'::'.$route] = $descriptor;
        return $descriptor;
    }

    /**
     * Inicia o roteamento.
     *
     * @return void
     */
    public static function routing(): void
    {
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
        $routes = array_filter(array_keys(self::$routes), function($route) use($requestMethod) {return str_starts_with($route, "$requestMethod::");});
        $url = self::getUrl();

        // se a rota é a rota raíz ...
        if(self::isRoot($url)){
            self::dispatch(self::getDescriptorForRoute('/'));
            return;
        }
        
        // ... se não é rota raíz
        $url = self::trimSlashes($url);

        $descriptor = self::searchRoute($routes, explode('/', $url));

        if(is_null($descriptor)){
            http_response_code(404);
            return;
        } else {
            self::dispatch($descriptor);
            return;
        }
    }

    private static function getDescriptorForRoute(string $route): RouteDescriptor
    {
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
        return self::$routes["$requestMethod::$route"];
    }

    private static function dispatch(RouteDescriptor $descriptor): void
    {
        if(strtolower($descriptor->requestMethod) !== strtolower($_SERVER['REQUEST_METHOD'])) throw new DomainException("Request method {$_SERVER['REQUEST_METHOD']} invalid for route. Expected {$descriptor->requestMethod}.");
        is_null($descriptor->method)? $method = 'index' : $method = $descriptor->method;
        $dispatcher = new $descriptor->controller();
        $dispatcher->$method(...array_values(self::$paramStore));
    }

    private static function isRoot(string $url): bool
    {
        return $url === '/';
    }

    private static function trimSlashes(string $str): string
    {
        return trim($str, '/');
    }

    private static function getUrl(): string
    {
        $query_string = $_SERVER['QUERY_STRING'] ?? '';
        return str_replace('?', '', str_replace($query_string, '', $_SERVER['REQUEST_URI']));
    }

    private static function searchRoute(array $routes, array $urlChunks): ?RouteDescriptor
    {
        foreach($routes as $route){
            $route = preg_replace('/^(GET|POST)::/i', '', $route);

            if(self::processChunks(explode('/', self::trimSlashes($route)), $urlChunks)) {
                $descriptor = self::getDescriptorForRoute($route);
                if(strtolower($descriptor->requestMethod) === strtolower($_SERVER['REQUEST_METHOD'])) return $descriptor;
            }
        }
        return null;
    }

    private static function processChunks(array $routeChunks, array $urlChunks): bool
    {
        if(count($routeChunks) !== count($urlChunks)) return false;

        $paramStore = [];
        foreach($routeChunks as $i => $routeChunk){
            $urlChunk = $urlChunks[$i];
            if(self::isUrlParam($routeChunk, $urlChunk, $paramStore)) continue;
            if($routeChunk !== $urlChunk) return false;
        }
        self::$paramStore = $paramStore;
        return true;
    }

    private static function isUrlParam(string $routeChunk, string $urlChunk, array &$paramStore): bool
    {
        $start = substr($routeChunk, 0, 1);
        $end = substr($routeChunk, -1, 1);
        if($start === '{' && $end === '}'){
            $paramStore[$routeChunk] = $urlChunk;
            return true;
        }
        return false;
    }

    /**
     * Retorna a url para uma rota e seus parâmetros.
     *
     * @param string $name
     * @param array|null $params
     * @return string
     */
    public static function url(string $name, ?array $params): string
    {
        foreach(self::$routes as $descriptor){
            if($descriptor->name === $name) {
                return $descriptor->getUrl($params);
            }
        }

        throw new DomainException("Route name $name not found!");
    }
}