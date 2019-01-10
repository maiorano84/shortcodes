<?php

namespace Maiorano\Shortcodes\Contracts\Traits;

/**
 * Trait Shortcode
 * Assists in satisfying the ShortcodeInterface requirements.
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
