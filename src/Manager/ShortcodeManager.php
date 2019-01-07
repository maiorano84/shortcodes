<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Parsers\ParserInterface;
use Maiorano\Shortcodes\Parsers\DefaultParser;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Class ShortcodeManager
 * @package Maiorano\Shortcodes\Manager
 */
class ShortcodeManager extends BaseManager
{
    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * ShortcodeManager constructor.
     * @param array $shortcodes
     * @param ParserInterface|null $parser
     * @throws RegisterException
     */
    public function __construct(array $shortcodes = [], ParserInterface $parser = null)
    {
        $this->parser = $parser ?? new DefaultParser;
        $this->registerAll($shortcodes);
    }

    /**
     * @param array $shortcodes
     * @return ManagerInterface
     * @throws RegisterException
     */
    public function registerAll(array $shortcodes): ManagerInterface
    {
        foreach ($shortcodes as $k => $s) {
            $this->register($s);
        }
        return $this;
    }

    /**
     * @param string $name
     * @param string $alias
     * @return ManagerInterface
     * @throws RegisterException
     */
    public function alias(string $name, string $alias): ManagerInterface
    {
        if (!($this[$name] instanceof AliasInterface)) {
            throw new RegisterException(RegisterException::NO_ALIAS);
        }
        if (!$this->isRegistered($name)) {
            $e = sprintf(RegisterException::MISSING, $name);
            throw new RegisterException($e);
        }
        $this[$name]->alias($alias);

        return $this;
    }

    /**
     * @param string $content
     * @param array $tags
     * @return bool
     */
    public function hasShortcode(string $content, $tags = []): bool
    {
        $tags = $this->preProcessTags($tags);
        $matches = $this->parser->parseShortcode($content, $tags);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if (in_array($shortcode['tag'], $tags)) { //Shortcodes matched
                return true;
            } else if ($shortcode['content']) {
                return $this->hasShortcode($shortcode['content'], $tags); //Check Nested Shortcodes
            }
        }

        return false;
    }

    /**
     * @param string $content
     * @param array $tags
     * @param bool $deep
     * @return string
     */
    public function doShortcode(string $content, $tags = [], bool $deep = false): string
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
     * @param array|string $tags
     * @return array
     */
    private function preProcessTags($tags): array
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
