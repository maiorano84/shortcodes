<?php

namespace Maiorano\Shortcodes\Contracts\Traits;

/**
 * Trait Shortcode
 * Assists in satisfying the ShortcodeInterface requirements
 * @package Maiorano\Shortcodes\Contracts\Traits
 */
trait Shortcode
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
