<?php
use App\Core\Core;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;

$containerBuilder = new ContainerBuilder();

$containerBuilder->register('context', RequestContext::class);

$containerBuilder->register('matcher', UrlMatcher::class)
    ->setArguments(array($routes, new Reference('context')));

$containerBuilder->register('request_stack', RequestStack::class);

$containerBuilder->register('controller_resolver', ControllerResolver::class);

$containerBuilder->register('argument_resolver', ArgumentResolver::class);

$containerBuilder->register('listener.router', RouterListener::class)
    ->setArguments(array(new Reference('matcher'), new Reference('request_stack')));

$containerBuilder->register('listener.response', ResponseListener::class)
    ->setArguments(array('UTF-8'));

$containerBuilder->register('listener.exception', ExceptionListener::class)
    ->setArguments(array('App\Core\ErrorController::exception'));

$containerBuilder->register('dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')));

$containerBuilder->register('core', Core::class)
    ->setArguments(array(
        new Reference('dispatcher'),
        new Reference('controller_resolver'),
        new Reference('request_stack'),
        new Reference('argument_resolver')
    ));

return $containerBuilder;