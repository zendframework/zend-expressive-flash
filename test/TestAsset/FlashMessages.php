<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-flash for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Expressive\Flash\TestAsset;

use Zend\Expressive\Flash\FlashMessagesInterface;
use Zend\Expressive\Session\SessionInterface;

class FlashMessages implements FlashMessagesInterface
{
    public $session;
    public $sessionKey;

    public function __construct(SessionInterface $session, string $sessionKey)
    {
        $this->session = $session;
        $this->sessionKey = $sessionKey;
    }

    public static function createFromSession(
        SessionInterface $session,
        string $sessionKey = 'this-should-not-be-used'
    ) : FlashMessagesInterface {
        return new self($session, $sessionKey);
    }

    public function flash(string $key, $value, int $hops = 1) : void
    {
    }

    public function flashNow(string $key, $value, int $hops = 1) : void
    {
    }

    public function getFlash(string $key, $default = null)
    {
    }

    public function clearFlash() : void
    {
    }

    public function prolongFlash() : void
    {
    }
}
