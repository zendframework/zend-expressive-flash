<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-flash for the canonical source repository
 * @copyright Copyright (c) 2017-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Flash;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Session\SessionMiddleware;

class FlashMessageMiddleware implements MiddlewareInterface
{
    public const FLASH_ATTRIBUTE = 'flash';

    /**
     * @var string
     */
    private $attributeKey;

    /**
     * @var callable
     */
    private $flashMessageFactory;

    /**
     * @var string
     */
    private $sessionKey;

    public function __construct(
        string $flashMessagesClass = FlashMessages::class,
        string $sessionKey = FlashMessagesInterface::FLASH_NEXT,
        string $attributeKey = self::FLASH_ATTRIBUTE
    ) {
        if (! class_exists($flashMessagesClass)
            || ! in_array(FlashMessagesInterface::class, class_implements($flashMessagesClass), true)
        ) {
            throw Exception\InvalidFlashMessagesImplementationException::forClass($flashMessagesClass);
        }

        $this->flashMessageFactory = [$flashMessagesClass, 'createFromSession'];
        $this->sessionKey = $sessionKey;
        $this->attributeKey = $attributeKey;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE, false);
        if (! $session instanceof SessionInterface) {
            throw Exception\MissingSessionException::forMiddleware($this);
        }

        $flashMessages = ($this->flashMessageFactory)($session, $this->sessionKey);

        return $handler->handle($request->withAttribute($this->attributeKey, $flashMessages));
    }
}
