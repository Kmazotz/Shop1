<?php

namespace App\Core;

use Closure;
use Support\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as BaseRequest;

/**
 * Summary of Request
 */
class Request extends BaseRequest
{

    public const Error = 'error';

    public const Success = 'success';

    public const Invalid = 'invalid';

    public const Failed = 'failed';

    public const CollectionParams = 'collection_param';

    /**
     * @return mixed
     */
    public function GetAction(): string
    {
        $action = $this->get('_controller');

        $action = explode('::', $action);

        return $action[1];
    }

    /**
     * @param Request $request
     * @param string $keys
     * @return mixed
     */
    public static function HasAnyRequirement(Request &$request, string...$keys)
    {
        $matches = 0;

        foreach ($keys as $requestKey)
        {
            if ($request->get($requestKey) !== null)
            {
                $matches++;
            }
        }

        return $matches > 0;
    }

    public static function EmptyRequirements() : array
    {
        return [];
    }

    /**
     * @param Request $request
     * @param array $keys
     * @param Controller $controller
     * @param null|Closure $callBack
     * @param null|Closure $errorCallBack
     * @return mixed
     */
    public static function HasRequirements(Request &$request, array $keys,array $subParams, Controller $controller = null, Closure $callBack = null, Closure $errorCallBack = null,Closure $invalidArgumentsCallBack = null)
    {
        $matches = 0;

        $parameters = [];

        $noFound = [];

        foreach ($keys as $requestKey)
        {
            if ($request->get($requestKey) !== null)
            {
                $matches++;

                // request param contains sub parameters

                if (is_array($request->get($requestKey)) && array_key_exists($requestKey, $subParams))
                {

                    foreach($request->get($requestKey) as $reqKey)
                    {
                        // var_dump(is_array($reqKey));
                        if(is_array($reqKey) && array_key_exists(Request::CollectionParams,$subParams[$requestKey]))
                        {
                            if($arrayKeys = ArrayCollection::ContainsKeys($reqKey,...array_values($subParams[$requestKey][Request::CollectionParams])))
                            {
                                $parameters[$requestKey]=$arrayKeys;
                            }
                        }else
                        {
                            if($require = ArrayCollection::ContainsKeys($request->get($requestKey), ...$subParams[$requestKey]))
                            {
                                $parameters[$requestKey] = $require;
                            }
                        }
                    }
                }
            }else
            {
                $noFound[] = $requestKey;
            }
        }

        if ($callBack !== null || $errorCallBack !== null||$invalidArgumentsCallBack != null)
        {
            if ($matches === count($keys))
            {
                if(count($parameters) !== 0)
                {
                    return $invalidArgumentsCallBack->call($controller, $parameters);
                }

                return $callBack->call($controller, $request);
            }
            else
            {
                return $errorCallBack->call($controller, $noFound);
            }

        }

        return $matches === count($keys) && count($parameters) !== 0;
    }

    public static function NotFoundCallBack(): Closure
    {
        return function ($noFound)
        {
            return new JsonResponse(['data' => 'Request requires the parameters', 'params' => $noFound], 500);
        };
    }

    public static function InvalidArgumentCallBack()
    {
        return function ($requestParams) {

            return new JsonResponse(['data' => 'Request parameters were not found','params'=> $requestParams],500);
        };
    }

    /**
     * Summary of getControllerName
     * @return mixed
     */
    public function GetControllerName()
    {
        $controller = $this->get('_controller');

        $controller = explode('::', $controller);

        $controller = explode('\\', $controller[0]);

        // use this line if you want to remove the trailing "Controller" string
        //return isset($controller[4]) ? preg_replace('/Controller$/', '', $controller[4]) : false;

        return isset($controller[4]) ? $controller[4] : false;
    }

}
