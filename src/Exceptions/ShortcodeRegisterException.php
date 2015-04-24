<?php
namespace Maiorano\Shortcodes\Exceptions;

/**
 * Class ShortcodeRegisterException
 * @package Maiorano\Shortcodes\Exceptions
 */
class ShortcodeRegisterException extends ShortcodeException
{
    /**
     * @const string
     */
    const DUPLICATE = 'The shortcode \'%s\' has already been registered';

    /**
     * @const string
     */
    const MISSING = 'No shortcode with identifier \'%s\' has been registered';

    /**
     * @const string
     */
    const BLANK = 'You must provide a name for your shortcode';
}