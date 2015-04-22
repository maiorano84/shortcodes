<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Creation of Shortcodes programatically
 * @package Maiorano\WPShortcodes\Shortcode
 */
class SimpleShortcode implements ShortcodeInterface, AttributeInterface
{
    use ShortcodeTrait;
    use AttributeTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param string $name
     * @param array $atts
     * @param callable $callback
     */
    public function __construct($name, $atts = [], Callable $callback = null)
    {
        $this->name = $name;
        $this->attributes = (array)$atts;
        $this->callback = $callback;
    }
}