<?php

namespace App\Core;

use BadMethodCallException;
use Support\ResponseTraits;

/**
 * Summary of Controller
 */
abstract class Controller
{
    use ResponseTraits;

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function CallAction(string $action, ...$args)
    {
        return call_user_func_array([$this, $action], ...$args);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $args)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    final public function __construct()
    {
        $this->InitializeComponent();
    }

    abstract protected function InitializeComponent();
}
