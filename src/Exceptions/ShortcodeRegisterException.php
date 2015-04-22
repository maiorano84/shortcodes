<?php
namespace Maiorano\Shortcodes\Exceptions;

/**
 * Class ShortcodeRegisterException
 * @package Maiorano\Shortcodes\Exceptions
 */
class ShortcodeRegisterException extends ShortcodeException
{
    const DUPLICATE = 'The shortcode \'%s\' has already been registered';
    const MISSING = 'No shortcode with identifier \'%s\' has been registered';
}