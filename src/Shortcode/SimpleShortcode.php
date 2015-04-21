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

    /**
     * @param array $atts
     * @param string|null $content
     * @return string
     * @see Maiorano\WPShortcodes\Shortcode\ShortcodeInterface::handle()
     */
    public function handle(array $atts = [], $content = null)
    {
        if (!is_null($this->callback)) {
            return call_user_func($this->callback, $this->getAttributes($atts), $content);
        }

        return (string)$content;
    }
}