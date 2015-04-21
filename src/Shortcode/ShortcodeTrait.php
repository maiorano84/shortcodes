<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Class ShortcodeTrait
 * @package Maiorano\WPShortcodes\Shortcode
 */
trait ShortcodeTrait
{

    /**
     * @return string
     * * @see Maiorano\WPShortcodes\Shortcode\ShortcodeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
}