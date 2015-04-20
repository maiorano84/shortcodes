<?php

namespace Maiorano\WPShortcodes\Shortcode;

class SimpleShortcode implements ShortcodeInterface{
    use ShortcodeTrait;

    protected $callback;
    public function __construct($name, $atts=array(), Closure $callback=null)
    {
        $this->name = $name;
        $this->atts = $atts;
        $this->callback = $callback;
    }
    public function handle($atts=array(), $content=null)
    {
        return $this->callback(array_merge($this->atts, $atts), $content);
    }
}