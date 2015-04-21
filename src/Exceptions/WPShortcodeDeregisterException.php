<?php
namespace Maiorano\WPShortcodes\Exceptions;

/**
 * Class WPShortcodeDeregisterException
 * @package Maiorano\WPShortcodes\Exceptions
 */
class WPShortcodeDeregisterException extends WPShortcodeException
{
    const MISSING = 'The shortcode \'%s\' does not exist in the current library';
}