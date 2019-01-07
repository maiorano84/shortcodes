<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\ContainerAwareInterface;
use Maiorano\Shortcodes\Contracts\AliasInterface;
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

        if ($shortcode instanceof AliasInterface) {
            foreach ($shortcode->getAlias() as $alias) {
                if (!$this->isRegistered($alias)) {
                    $this->register($shortcode, $alias);
                }
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param bool $includeAlias
     * @return ManagerInterface
     * @throws DeregisterException
     */
    public function deregister(string $name, $includeAlias = true): ManagerInterface
    {
        if (!$this->isRegistered($name)) {
            $e = sprintf(DeregisterException::MISSING, $name);
            throw new DeregisterException($e);
        }

        $shortcode = $this->shortcodes[$name];

        if ($includeAlias && $shortcode instanceof AliasInterface) {
            foreach ($shortcode->getAlias() as $alias) {
                unset($this->shortcodes[$alias]);
            }
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
     * @return void
     * @throws RegisterException
     */
    public function offsetSet($offset, $value): void
    {
        $this->register($value);
    }

    /**
     * @param mixed $offset
     * @return void
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
