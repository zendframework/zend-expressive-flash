# Flash Message Middleware

In order to provide flash messages to your middleware, you will first need to
register `Zend\Expressive\Flash\FlashMessageMiddleware` in your application
pipeline or routed middleware pipeline; additionally, you'll need to ensure
`Zend\Expressive\Session\SessionMiddleware` is registered prior to it.

This middleware does the following:

- Pulls the session container from the request via the attribute
  `Zend\Expressive\Session\SessionMiddleware::SESSION_ATTRIBUTE` ("session").
- Passes the container, along with a defined session key, to a factory for
  generating a `FlashMessagesInterface` instance.
- Passes that instance to a request that the delegate processes, using another
  request attribute.

## Default configuration

By default, `FlashMessageMiddleware` uses `FlashMessages::createFromSession()`
to generate the flash messages container, the key
`Zend\Expressive\Flash\FlashMessagesInterface::FLASH_NEXT` (this is a literal
string) to pull stored flash messages from the session, and the request
attribute `FlashMessageMiddleware::FLASH_ATTRIBUTE` ("flash") to pass the flash
messages container to the next middleware.

If you are using the [zend-component-installer](https://docs.zendframework.com/zend-component-installer/)
Composer plugin, the middleware will already be wired for you. Otherwise, you
will need to map the middleware to your dependency injection container as an
invokable (no constructor arguments).

If these defaults will work for you, you have no further configuration to do.

## Custom configuration

If you want to specify a different flash messages container implementation, a
different session key, or a different flash messages request attribute name, you
will need to create a new factory for your `FlashMessagesMiddleware`. As an
example, in the following, I specify:

- `Application\FlashMessages` as the flash messages container; this class will
  need to implement `FlashMessagesInterface`, including the static method
  `createFromSession()`.
- The string `Application\FlashMessages::FLASH_NEXT` as the session key in which
  flash messages will be stored.
- The request attribute `flash-messages` in which to store the flash messages
  container.

```php
use Application\FlashMessages;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Flash\FlashMessageMiddleware;

class FlashMessageMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new FlashMessageMiddleware(
            FlashMessages::class,
            FlashMessages::class . '::FLASH_NEXT',
            'flash-messages'
        );
    }
}
```

Once you have created this factory, map the `FlashMessageMiddleware` to it in
your dependency injection configuration:

```php
'dependencies' => [
    'factories' => [
        \Zend\Expressive\Flash\FlashMessageMiddleware::class => FlashMessageMiddlewareFactory::class,
    ],
],
```

## Piping the middleware

You may pipe this middleware either in your application pipeline
(`config/pipeline.php`) or a routed middleware pipeline (`config/routes.php`, or
a delegator factory). When you do, you **MUST** register it **AFTER** the
`Zend\Expressive\Session\SessionMiddleware` as it depends on that middleware for
its session container.

As an example within an application pipeline:

```php
$app->pipe(\Zend\Expressive\Session\SessionMiddleware::class);
$app->pipe(\Zend\Expressive\Flash\FlashMessageMiddleware::class);
```

Within a routed middleware definition:

```php
$app->post('/user/login', [
    \Zend\Expressive\Session\SessionMiddleware::class,
    \Zend\Expressive\Flash\FlashMessageMiddleware::class,
    LoginHandler::class,
]);
```
