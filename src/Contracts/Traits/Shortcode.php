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
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
}
