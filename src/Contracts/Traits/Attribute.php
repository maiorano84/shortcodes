<?php

namespace Maiorano\Shortcodes\Contracts\Traits;

/**
 * Trait Attribute
 * Assists in satisfying the AttributeInterface requirements
 * Allows shortcodes to use the attribute format if needed.
 */
trait Attribute
{
    use Shortcode;

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
