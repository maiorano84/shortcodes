<?php
namespace Maiorano\Shortcodes\Shortcode;

/**
 * Class ShortcodeTrait
 * @package Maiorano\Shortcodes\Shortcode
 */
trait ShortcodeTrait
{

    /**
     * @return string
     * * @see Maiorano\Shortcodes\Shortcode\ShortcodeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     * @see Maiorano\Shortcodes\Shortcode\ShortcodeInterface::handle()
     */
    public function handle($content = null, array $atts = [])
    {
        if (!is_null($this->callback)) {
            $c = $this->callback;
            $callback = $c->bindTo($this);

            return $callback($content, $atts);
        }

        return (string)$content;
    }
}