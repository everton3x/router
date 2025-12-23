<?php

namespace Router;

/**
 * Descreve uma determinada rota.
 * 
 * Usado internamente por `Router\Route`
 */
final class RouteDescriptor
{
    public ?string $name = null {
        get => $this->name;
        set (?string $name) => $this->name = $name;
    }

    public function __construct(
        public readonly string $requestMethod,
        public readonly string $route,
        public readonly string $controller,
        public readonly ?string $method = null
    )
    {
        
    }

    public function name(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUrl(?array $params): string
    {
        $chunks = explode('/', $this->route);
        $urlChunks = [];
        $i = 0;
        foreach($chunks as $chunk){
            if(substr($chunk, 0, 1) === '{' && substr($chunk, -1, 1) === '}'){
                $urlChunks[] = array_values($params)[$i];
                $i++;
            }else{
                $urlChunks[] = $chunk;
            }
        }
        
        return join('/', $urlChunks);
    }

}