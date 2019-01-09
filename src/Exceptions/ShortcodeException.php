<?php

namespace Maiorano\Shortcodes\Exceptions;

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
    const BLANK = 'You must provide a name for your shortcode';

    /**
     * @const string
     */
    const MISSING = 'No shortcode with identifier \'%s\' has been registered';
}
