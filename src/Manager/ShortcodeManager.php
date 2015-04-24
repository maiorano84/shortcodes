<?php
namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Parsers\WordpressParser;

/**
 * Class ShortcodeManager
 * @package Maiorano\Shortcodes\Manager
 */
class ShortcodeManager extends BaseManager
{

    /**
     * @param array $shortcodes
     */
    public function __construct(array $shortcodes = [])
    {
        parent::__construct($shortcodes, new WordpressParser);
    }

    /**
     * @param $content
     * @param string|array $tags
     * @return bool
     */
    public function hasShortcode($content, $tags = [])
    {
        $tags = $this->preProcessTags($tags);
        if ($this->precheck($content, $tags) === false) {
            return false;
        }

        $matches = $this->parser->parseShortcode($content, $tags);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if (in_array($shortcode[2], $tags)) //Shortcodes matched
            {
                return true;
            } elseif (!empty($shortcode[5]) && $this->hasShortcode($shortcode[5], $tags)) //Nested Shortcodes matched
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $content
     * @param string|array $tags
     * @param bool $deep
     * @return bool|mixed
     */
    public function doShortcode($content, $tags = [], $deep = false)
    {
        $tags = $this->preProcessTags($tags);
        if ($this->precheck($content, $tags) === false) {
            return $content;
        }

        $content = $this->parser->parseShortcode($content, $tags, function ($match) {
            //Escaped shortcode
            if ($match[1] == '[' && $match[6] == ']') {
                return substr($match[0], 1, -1);
            }

            $shortcode = $this[$match[2]];
            $content = isset($match[5]) ? $match[5] : null;
            $atts = [];

            if ($shortcode instanceof AttributeInterface) {
                $parsed = isset($match[3]) ? $this->parser->parseAttributes($match[3]) : $atts;
                $atts = array_merge($shortcode->getAttributes(), $parsed);
            }

            return $shortcode->handle($content, $atts);
        });

        if ($deep && $this->hasShortcode($content, $tags)) {
            return $this->doShortcode($content, $tags, $deep);
        }

        return $content;
    }

    /**
     * @param string|array $tags
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

    /**
     * @param string $content
     * @param array $tags
     * @return bool
     */
    private function preCheck($content, $tags)
    {
        return strpos($content, '[') !== false && !empty($tags);
    }
}
