<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Class ShortcodeTrait
 * @package Maiorano\WPShortcodes\Shortcode
 */
trait ShortcodeTrait{

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}