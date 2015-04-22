<?php
namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Manager\ShortcodeManagerInterface;

/**
 * Assists in satisfying the ShortcodeInterface requirements
 * @package Maiorano\Shortcodes\Contracts
 */
trait ShortcodeTrait
{
    /**
     * @return string
     * * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::handle()
     */
    public function handle($content = null, array $atts = [])
    {
        if (!is_null($this->callback)) {
            $c = $this->callback;
            $callback = $c->bindTo($this, $this);

            return $callback($content, $atts);
        }

        return (string)$content;
    }
}