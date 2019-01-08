<?php

namespace Maiorano\Shortcodes\Exceptions;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Exception;

/**
 * Class ShortcodeException
 * @package Maiorano\Shortcodes\Exceptions
 */
class ShortcodeException extends Exception
{
    /**
     * @const string
     */
    const NO_ALIAS = 'Cannot alias a shortcode that does not implement ' . AliasInterface::class;
}
