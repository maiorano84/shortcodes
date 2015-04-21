<?php
namespace Maiorano\WPShortcodes\Exceptions;

/**
 * Class WPShortcodeRegisterException
 * @package Maiorano\WPShortcodes\Exceptions
 */
class WPShortcodeRegisterException extends WPShortcodeException
{
    const DUPLICATE = 'The shortcode \'%s\' has already been registered';
    const MISSING = 'No shortcode with identifier \'%s\' has been registered';
}