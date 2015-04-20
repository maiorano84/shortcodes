<?php
namespace Maiorano\WPShortcodes\Shortcode;

trait ShortcodeTrait{
    protected $name;
    protected $atts;

    public function getName()
    {
        return $this->name;
    }
}