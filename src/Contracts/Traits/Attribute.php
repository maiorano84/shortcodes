<?php
namespace Maiorano\Shortcodes\Contracts\Traits;

/**
 * Trait Attribute
 * Assists in satisfying the AttributeInterface requirements
 * Allows shortcodes to use the attribute format if needed
 * @package Maiorano\Shortcodes\Contracts\Traits
 */
trait Attribute
{

    /**
     * @return array
     * @see \Maiorano\Shortcodes\Contracts\AttributeInterface::getAttributes()
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
