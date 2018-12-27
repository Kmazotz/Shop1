<?php

namespace App\Core;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Closure;

/**
 * Router short summary.
 *
 * Router description.
 *
 * @version 1.0
 * @author Usuario
 */
class Router
{

    /**
     * Summary of $routes
     * @var RouteCollection
     */
    protected $routes;

    /**
     * All of the verbs supported by the router.
     *
     * @var array
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function GetRoutes():RouteCollection
    {
        return $this->routes;
    }

    /**
     * Add a route
     * @param string $name The route name
     * @param string          $uri        The path pattern to match
     * @param array           $defaults     An array of default parameter values
     * @param array           $requirements An array of requirements for parameters (regexes)
     * @param array           $options      An array of options
     * @param string          $host         The host pattern to match
     * @param string|string[] $schemes      A required URI scheme or an array of restricted schemes
     * @param string|string[] $methods      A required HTTP method or an array of restricted methods
     * @param string          $condition    A condition that should evaluate to true for the route to match
     */
    public function Route(string $name,string $uri,$methods=[],$defaults=[],$requirements=[],$options=[],string $condition='',string $host='',$schemes=[])
    {
        $this->routes->add($name,new Route($uri,$defaults,$requirements,$options,$host,$schemes,$methods,$condition));
    }

    /**
     * Summary of Post
     * @param string $name
     * @param string $uri
     * @param array           $requirements An array of requirements for parameters (regexes)
     * @param string|\Closure|callable|array $action
     */
    public function Post(string $name,string $uri,$action,array $requirements=[])
    {
        if(is_array($action))
        {
            $action = implode('::',$action);
        }

        $this->routes->add($name,static::CreateRoute($uri,$action,['POST'],$requirements));
    }

    /**
     * Summary of Post
     * @param string $name
     * @param string $uri
     * @param string|\Closure|callable|array $action
     */
    public function Get(string $name,string $uri,$action,array $requirements=[])
    {
        if(is_array($action))
        {
            $action = implode('::',$action);
        }

        $this->routes->add($name,static::CreateRoute($uri,$action,['GET','HEAD'],$requirements));
    }

    /**
     * Summary of Post
     * @param string $name
     * @param string $uri
     * @param string|\Closure|callable|array $action
     */
    public function Put(string $name,string $uri,$action,array $requirements=[])
    {
        if(is_array($action))
        {
            $action = implode('::',$action);
        }

        $this->routes->add($name,static::CreateRoute($uri,$action,['PUT'],$requirements));
    }

    /**
     * Summary of Post
     * @param string $name
     * @param string $uri
     * @param string|\Closure|callable|array $action
     */
    public function Delete(string $name,string $uri,$action,array $requirements=[])
    {
        if(is_array($action))
        {
            $action = implode('::',$action);
        }

        $this->routes->add($name,static::CreateRoute($uri,$action,['DELETE'],$requirements));
    }

    /**
     * Summary of Post
     * @param string $name
     * @param string $uri
     * @param string|\Closure|callable|array $action
     */
    public function Options(string $name,string $uri,$action,array $requirements=[])
    {
        if(is_array($action))
        {
            $action = implode('::',$action);
        }

        $this->routes->add($name,static::CreateRoute($uri,$action,['OPTIONS'],$requirements));
    }

    /**
     * Summary of Post
     * @param string $name
     * @param string $uri
     * @param string|\Closure|callable|array $action
     */
    public function Any(string $name,string $uri,$action,array $requirements=[])
    {
        if(is_array($action))
        {
            $action = implode('::',$action);
        }

        $this->routes->add($name,static::CreateRoute($uri,$action,self::$verbs,$requirements));
    }

    /**
     *
     * @param string $uri
     * @param string|\closure|callable|array $action
     * @param array $methods
     * @return Route
     */
    private static function CreateRoute(string $uri,$action,array $methods,array $requirements=[]):Route
    {
        return new Route($uri,['_controller'=>$action],$requirements,[],'',[],$methods,'');
    }
}