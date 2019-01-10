<?php

namespace Maiorano\Shortcodes\Exceptions;

/**
 * Class DeregisterException.
 */
class DeregisterException extends ShortcodeException
{
    /**
     * @return static
     */
    public static function blank(): self
    {
        return new static(parent::BLANK);
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public static function missing(string $name): self
    {
        $e = sprintf(parent::MISSING, $name);

        return new static($e);
    }
}
