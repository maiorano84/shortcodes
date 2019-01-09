<?php

namespace Maiorano\Shortcodes\Exceptions;

/**
 * Class DeregisterException
 * @package Maiorano\Shortcodes\Exceptions
 */
class DeregisterException extends ShortcodeException
{
    /**
     * @return static
     */
    public static function blank(): DeregisterException
    {
        return new static(parent::BLANK);
    }

    /**
     * @param string $name
     * @return static
     */
    public static function missing(string $name): DeregisterException
    {
        $e = sprintf(parent::MISSING, $name);
        return new static($e);
    }
}
