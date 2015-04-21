<?php
namespace Maiorano\WPShortcodes\Manager;

use Maiorano\WPShortcodes\Shortcode\AttributeInterface;

/**
 * Class ShortcodeManager
 * @package Maiorano\WPShortcodes\Manager
 */
class ShortcodeManager extends BaseManager
{

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

        $regex = $this->getShortcodeRegex($tags);
        preg_match_all("/$regex/s", $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if (in_array($shortcode[2], $tags)) //Shortcode matched
            {
                return true;
            } elseif (!empty($shortcode[5]) && $this->hasShortcode($shortcode[5], $tags)) //Nested Shortcode matched
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $content
     * @param string|array $tags
     * @return bool|mixed
     */
    public function doShortcode($content, $tags = [])
    {
        $tags = $this->preProcessTags($tags);
        if ($this->precheck($content, $tags) === false) {
            return $content;
        }

        $regex = $this->getShortcodeRegex($tags);
        $content = preg_replace_callback("/$regex/s", [$this, 'handleShortcode'], $content);

        return $content;
    }

    /**
     * @param $match
     * @return string
     */
    private function handleShortcode($match)
    {
        //Escaped shortcode (ie: [[tag]])
        if ($match[1] == '[' && $match[6] == ']') {
            return substr($match[0], 1, -1);
        }

        $shortcode = $this[$match[2]];
        $content = isset($match[5]) ? $match[5] : null;

        if ($shortcode instanceof AttributeInterface) {
            $atts = $shortcode->parseAttributes($match[3]);

            return $shortcode->handle($atts, $content);
        }

        return $shortcode->handle($content);
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
            $tags = is_string($tags) ? [$tags] : $tags;
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
