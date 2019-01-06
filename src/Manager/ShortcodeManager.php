<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Parsers\DefaultParser;

/**
 * Class ShortcodeManager
 * @package Maiorano\Shortcodes\Manager
 */
class ShortcodeManager extends BaseManager implements ManagerInterface
{

    /**
     * @param array $shortcodes
     */
    public function __construct(array $shortcodes = [])
    {
        parent::__construct(new DefaultParser, $shortcodes);
    }

    /**
     * @param string $content
     * @param string|array $tags
     * @return bool
     */
    public function hasShortcode($content, $tags = [])
    {
        $tags = $this->preProcessTags($tags);
        $matches = $this->parser->parseShortcode($content, $tags);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if (in_array($shortcode['tag'], $tags)) { //Shortcodes matched
                return true;
            } elseif ($shortcode['content']) {
                return $this->hasShortcode($shortcode['content'], $tags); //Check Nested Shortcodes
            }
        }

        return false;
    }

    /**
     * @param string $content
     * @param string|array|null $tags
     * @param bool $deep
     * @return bool|mixed
     */
    public function doShortcode($content, $tags = [], $deep = false)
    {
        $tags = $this->preProcessTags($tags);
        $content = $this->parser->parseShortcode($content, $tags, function ($tag, $content, $atts) {

            $shortcode = $this[$tag];
            if ($shortcode instanceof AttributeInterface) {
                $atts = array_merge($shortcode->getAttributes(), $atts);
            }

            return $shortcode->handle($content, $atts);
        });

        if ($deep && $this->hasShortcode($content, $tags)) {
            return $this->doShortcode($content, $tags, $deep);
        }

        return $content;
    }

    /**
     * @param string|array|null $tags
     * @return array
     */
    private function preProcessTags($tags)
    {
        if (!$tags) {
            return $this->getRegistered();
        }

        if (is_string($tags)) {
            $tags = explode('|', $tags);
        }

        return array_filter($tags, [$this, 'isRegistered']);
    }
}
