<?php
namespace Maiorano\Shortcodes\Exceptions;

/**
 * Class ShortcodeDeregisterException
 * @package Maiorano\Shortcodes\Exceptions
 */
class ShortcodeDeregisterException extends ShortcodeException
{
    const MISSING = 'The shortcode \'%s\' does not exist in the current library';
}