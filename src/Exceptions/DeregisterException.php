<?php

namespace Maiorano\Shortcodes\Exceptions;

/**
 * Class DeregisterException
 * @package Maiorano\Shortcodes\Exceptions
 */
class DeregisterException extends ShortcodeException
{
    /**
     * @const string
     */
    const MISSING = 'The shortcode \'%s\' does not exist in the current library';
}
