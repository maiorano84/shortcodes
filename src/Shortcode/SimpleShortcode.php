<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Class SimpleShortcode
 * @package Maiorano\WPShortcodes\Shortcode
 */
class SimpleShortcode implements ShortcodeInterface{
    use ShortcodeTrait;
    use AttributeTrait;

    protected $name;

    protected $atts;
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param string $name
     * @param array $atts
     * @param callable $callback
     */
    public function __construct($name, $atts=[], Callable $callback=null)
    {
        $this->name = $name;
        $this->atts = $atts;
        $this->callback = $callback;
    }

    /**
     * @param array $atts
     * @param string|null $content
     * @return string
     */
    public function handle(array $atts=[], $content=null)
    {
        if(!is_null($this->callback))
        {
            return call_user_func($this->callback, $this->processAttributes($atts), $content);
        }
        return (string)$content;
    }
}