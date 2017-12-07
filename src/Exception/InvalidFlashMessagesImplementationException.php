<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-flash for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Flash\Exception;

use InvalidArgumentException;
use Zend\Expressive\Flash\FlashMessageMiddleware;
use Zend\Expressive\Flash\FlashMessagesInterface;

class InvalidFlashMessagesImplementationException extends InvalidArgumentException implements ExceptionInterface
{
    public static function forClass(string $class) : self
    {
        return new self(sprintf(
            'Cannot use "%s" within %s; does not implement %s',
            $class,
            FlashMessageMiddleware::class,
            FlashMessagesInterface::class
        ));
    }
}
