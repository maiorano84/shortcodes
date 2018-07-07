<?php
namespace Maiorano\Shortcodes\Contracts\Traits;

use Closure;

/**
 * Trait CallableTrait
 * Assists in providing Closure functionality
 * @package Maiorano\Shortcodes\Contracts\Traits
 */
trait CallableTrait
{
    /**
     * @var Closure|null
     */
    protected $callback;

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     * @see \Maiorano\Shortcodes\Contracts\ShortcodeInterface::handle()
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
