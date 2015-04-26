<?php
namespace Maiorano\Shortcodes\Contracts;

/**
 * Trait CallableTrait
 * Assists in providing Closure functionality
 * @package Maiorano\Shortcodes\Contracts
 */
trait CallableTrait
{

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     * @see Maiorano\Shortcodes\Contracts\ShortcodeInterface::handle()
     */
    public function handle($content = null, array $atts = [])
    {
        if (is_null($this->callback)) {
            return (string)$content;
        }
        $callback = $this->callback->bindTo($this, $this);

        return $callback($content, $atts);
    }
}