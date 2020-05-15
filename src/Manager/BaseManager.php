<?php

namespace Maiorano\Shortcodes\Manager;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Exceptions\DeregisterException;
use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Class BaseManager.
 *
 * @implements ArrayAccess<string, ShortcodeInterface>
 * @implements IteratorAggregate<string, ShortcodeInterface>
 */
abstract class BaseManager implements ManagerInterface, ArrayAccess, IteratorAggregate
{
    /**
     * @var ShortcodeInterface[]
     */
    protected $shortcodes = [];

    /**
     * @param ShortcodeInterface $shortcode
     * @param string|null        $name
     *
     * @throws RegisterException
     *
     * @return ManagerInterface
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
     *
     * @throws DeregisterException
     *
     * @return ManagerInterface
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
     *
     * @return bool
     */
    public function isRegistered(string $name): bool
    {
        return isset($this->shortcodes[$name]);
    }

    /**
     * @return string[]
     */
    public function getRegistered(): array
    {
        return array_keys($this->shortcodes);
    }

    /**
     * @param string $content
     * @param string[]  $tags
     *
     * @return bool
     */
    abstract public function hasShortcode(string $content, $tags = []): bool;

    /**
     * @param string $content
     * @param string[]  $tags
     * @param bool   $deep
     *
     * @return string
     */
    abstract public function doShortcode(string $content, $tags = [], bool $deep = false): string;

    /**
     * @param string $offset
     *
     * @throws RegisterException
     *
     * @return ShortcodeInterface
     */
    public function offsetGet($offset): ShortcodeInterface
    {
        if (!$this->isRegistered($offset)) {
            throw RegisterException::missing($offset);
        }

        return $this->shortcodes[$offset];
    }

    /**
     * @param string $offset
     * @param mixed $value
     *
     * @throws RegisterException
     */
    public function offsetSet($offset, $value): void
    {
        $name = is_string($offset) && !is_numeric($offset) ? $offset : null;
        $this->register($value, $name);
    }

    /**
     * @param mixed $offset
     *
     * @throws DeregisterException
     */
    public function offsetUnset($offset): void
    {
        $this->deregister($offset);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->isRegistered($offset);
    }

    /**
     * @return ArrayIterator<string, ShortcodeInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->shortcodes);
    }
}
