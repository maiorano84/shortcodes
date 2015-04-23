<?php
namespace Maiorano\Shortcodes\Contracts;


/**
 * Trait AttributeTrait
 * Assists in satisfying the AttributeInterface requirements
 * Allows shortcodes to use the attribute format if needed
 * @package Maiorano\Shortcodes\Contracts
 */
trait AttributeTrait
{
    /**
     * @return array
     * @see AttributeInterface::getAttributes()
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}