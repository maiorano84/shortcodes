<?php
namespace Maiorano\Shortcodes\Contracts;

/**
 * Assists in satisfying the ShortcodeInterface requirements
 * @package Maiorano\Shortcodes\Contracts
 */
trait ShortcodeTrait
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
