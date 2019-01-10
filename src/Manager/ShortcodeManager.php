<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Exceptions\DeregisterException;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Parsers\DefaultParser;
use Maiorano\Shortcodes\Parsers\ParserInterface;

/**
 * Class ShortcodeManager.
 */
class ShortcodeManager extends BaseManager
{
    /**
     * @var DefaultParser|ParserInterface
     */
    protected $parser;

    /**
     * ShortcodeManager constructor.
     *
     * @param array                $shortcodes
     * @param ParserInterface|null $parser
     */
    public function __construct(array $shortcodes = [], ParserInterface $parser = null)
    {
        $this->parser = $parser ?? new DefaultParser();
        $this->registerAll($shortcodes);
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @param string|null        $name
     *
     * @throws RegisterException
     *
     * @return static
     */
    public function register(ShortcodeInterface $shortcode, ?string $name = null): ManagerInterface
    {
        parent::register($shortcode, $name);
        if ($shortcode instanceof AliasInterface) {
            foreach ($shortcode->getAlias() as $alias) {
                if (!$this->isRegistered($alias)) {
                    parent::register($shortcode, $alias);
                }
            }
        }

        return $this;
    }

    /**
     * @param array $shortcodes
     *
     * @return static
     */
    public function registerAll(array $shortcodes): ManagerInterface
    {
        foreach ($shortcodes as $k => $s) {
            $this[$k] = $s;
        }

        return $this;
    }

    /**
     * @param string $name
     * @param bool   $includeAlias
     *
     * @throws DeregisterException
     *
     * @return static
     */
    public function deregister(string $name, bool $includeAlias = true): ManagerInterface
    {
        $shortcode = $this->shortcodes[$name] ?? false;
        if ($shortcode && $shortcode instanceof AliasInterface) {
            if ($name === $shortcode->getName() && $includeAlias) {
                foreach ($shortcode->getAlias() as $alias) {
                    parent::deregister($alias);
                }
            }
        }

        return parent::deregister($name);
    }

    /**
     * @param string $name
     * @param string $alias
     *
     * @throws RegisterException
     *
     * @return static
     */
    public function alias(string $name, string $alias): ManagerInterface
    {
        if (!$this->isRegistered($name)) {
            throw RegisterException::missing($name);
        }
        if ($this[$name] instanceof AliasInterface) {
            $this[$name]->alias($alias);
        } else {
            throw RegisterException::noAlias();
        }

        if (!$this[$name] instanceof ContainerAwareInterface) {
            parent::register($this[$name], $alias);
        }

        return $this;
    }

    /**
     * @param string       $content
     * @param string|array $tags
     *
     * @return bool
     */
    public function hasShortcode(string $content, $tags = []): bool
    {
        $tags = $this->preProcessTags($tags);
        $matches = $this->parser->parseShortcode($content, $tags);

        return !empty($matches);
    }

    /**
     * @param string       $content
     * @param string|array $tags
     * @param bool         $deep
     *
     * @return string
     */
    public function doShortcode(string $content, $tags = [], bool $deep = false): string
    {
        $tags = $this->preProcessTags($tags);
        $handler = function (string $tag, ?string $content = null, array $atts = []) {
            $shortcode = $this[$tag];

            if ($shortcode instanceof AttributeInterface) {
                $atts = array_merge($shortcode->getAttributes(), $atts);

                return $shortcode->handle($content, $atts);
            }

            return $shortcode->handle($content);
        };

        $result = (string) $this->parser->parseShortcode($content, $tags, $handler);

        if ($deep && $this->hasShortcode($result, $tags)) {
            return $this->doShortcode($result, $tags, $deep);
        }

        return $result;
    }

    /**
     * @param string|array $tags
     *
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
