<?php

namespace Maiorano\Shortcodes\Exceptions;

use Maiorano\Shortcodes\Contracts\AliasInterface;

/**
 * Class RegisterException
 * @package Maiorano\Shortcodes\Exceptions
 */
class RegisterException extends ShortcodeException
{
    /**
     * @const string
     */
    const DUPLICATE = 'The shortcode \'%s\' has already been registered';

    /**
     * @const string
     */
    const NO_ALIAS = 'Cannot alias a shortcode that does not implement ' . AliasInterface::class;

    /**
     * @return static
     */
    public static function blank(): RegisterException
    {
        return new static(parent::BLANK);
    }

    /**
     * @param string $name
     * @return static
     */
    public static function missing(string $name): RegisterException
    {
        $e = sprintf(parent::MISSING, $name);
        return new static($e);
    }

    /**
     * @param string $name
     * @return static
     */
    public static function duplicate(string $name): RegisterException
    {
        $e = sprintf(self::DUPLICATE, $name);
        return new static($e);
    }

    /**
     * @return static
     */
    public static function noAlias(): RegisterException
    {
        return new static(self::NO_ALIAS);
    }
}
