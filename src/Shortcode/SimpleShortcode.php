<?php

namespace Maiorano\WPShortcodes\Shortcode;

class SimpleShortcode implements ShortcodeInterface{
    use ShortcodeTrait;

    protected $callback;
    public function __construct($name, $atts=array(), Callable $callback=null)
    {
        $this->name = $name;
        $this->atts = $atts;
        $this->callback = $callback;
    }
    public function handle(array $atts=array(), $content=null)
    {
        if(!is_null($this->callback))
        {
            return call_user_func($this->callback, array_merge($this->atts, $atts), $content);
        }
        return (string)$content;
    }
}