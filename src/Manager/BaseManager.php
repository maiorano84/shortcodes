<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Exceptions\DeregisterException;
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
     * @return ManagerInterface
     * @throws RegisterException
     */
    public function register(ShortcodeInterface $shortcode, ?string $name = null): ManagerInterface
    {
        $name = $name ?: $shortcode->getName();

        if (!$name) {
            throw new RegisterException(RegisterException::BLANK);
        } elseif ($this->isRegistered($name)) {
            $e = sprintf(RegisterException::DUPLICATE, $name);
            throw new RegisterException($e);
        }

        if ($shortcode instanceof ContainerAwareInterface) {
            $shortcode->bind($this);
        }

        $this->shortcodes[$name] = $shortcode;

        return $this;
    }

    /**
     * @param string $name
     * @return ManagerInterface
     * @throws DeregisterException
     */
    public function deregister(string $name): ManagerInterface
    {
        if (!$this->isRegistered($name)) {
            $e = sprintf(DeregisterException::MISSING, $name);
            throw new DeregisterException($e);
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
            $e = sprintf(RegisterException::MISSING, $offset);
            throw new RegisterException($e);
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
