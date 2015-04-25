<?php
namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Parsers\WordpressParser;

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

        $matches = $this->parser->parseContent($content, $tags);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if (in_array($shortcode['tag'], $tags)) //Shortcodes matched
            {
                return true;
            } elseif ($shortcode['content']) {
                return $this->hasShortcode($shortcode['content'], $tags); //Check Nested Shortcodes
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

        $content = $this->parser->parseContent($content, $tags, function ($match) {
            $result = $this->parser->parseShortcode($match);

            if ($result['escaped']) {
                return $result['escaped'];
            }

            $shortcode = $this[$result['tag']];

            $atts = [];
            if ($shortcode instanceof AttributeInterface) {
                $atts = array_merge($shortcode->getAttributes(), $result['attributes']);
            }

            return $shortcode->handle($result['content'], $atts);
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
