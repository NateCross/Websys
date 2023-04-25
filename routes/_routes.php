<?php

enum Method {
  case GET;
  case POST;
}

class Route {
  // private $pattern = "";
  // private $methods = [];
  // private $function = "";
  // private $parameters = [];

  public function __construct(
    private string $pattern, 
    private array $methods, 
    private string $function, 
    private array $parameters, 
  ) {
    
  }
}

class Router {
  static private $routes = [];

  static public function add(String $name, Method $method) {
    $routes += [
      path
    ]
  }
}
