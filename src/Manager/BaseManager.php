<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Exceptions\DeregisterException;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;

/**
 * Class BaseManager
 * @package Maiorano\Shortcodes\Manager
 */
abstract class BaseManager implements ManagerInterface, ArrayAccess, IteratorAggregate
{
    /**
     * @var array
     */
    protected $shortcodes = [];

    /**
     * @param ShortcodeInterface $shortcode
     * @param string|null $name
     * @return static
     * @throws RegisterException
     */
    public function register(ShortcodeInterface $shortcode, ?string $name = null): ManagerInterface
    {
        $name = $name ?: $shortcode->getName();

        if (!$name) {
            throw RegisterException::blank();
        } elseif ($this->isRegistered($name)) {
            throw RegisterException::duplicate($name);
        }

        if ($shortcode instanceof ContainerAwareInterface) {
            $shortcode->bind($this);
        }

        $this->shortcodes[$name] = $shortcode;

        return $this;
    }

    /**
     * @param string $name
     * @return static
     * @throws DeregisterException
     */
    public function deregister(string $name): ManagerInterface
    {
        if (!$name) {
            throw DeregisterException::blank();
        } elseif (!$this->isRegistered($name)) {
            throw DeregisterException::missing($name);
        }

        unset($this->shortcodes[$name]);

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isRegistered(string $name): bool
    {
        return isset($this->shortcodes[$name]);
    }

    /**
     * @return array
     */
    public function getRegistered(): array
    {
        return array_keys($this->shortcodes);
    }

    /**
     * @param string $content
     * @param array $tags
     * @return bool
     */
    public abstract function hasShortcode(string $content, $tags = []): bool;

    /**
     * @param string $content
     * @param array $tags
     * @param bool $deep
     * @return string
     */
    public abstract function doShortcode(string $content, $tags = [], bool $deep = false): string;

    /**
     * @param mixed $offset
     * @return ShortcodeInterface
     * @throws RegisterException
     */
    public function offsetGet($offset): ShortcodeInterface
    {
        if (!$this->isRegistered($offset)) {
            throw RegisterException::missing($offset);
        }

        return $this->shortcodes[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws RegisterException
     */
    public function offsetSet($offset, $value): void
    {
        $name = is_string($offset) && !is_numeric($offset) ? $offset : null;
        $this->register($value, $name);
    }

    /**
     * @param mixed $offset
     * @throws DeregisterException
     */
    public function offsetUnset($offset): void
    {
        $this->deregister($offset);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->isRegistered($offset);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->shortcodes);
    }
}
