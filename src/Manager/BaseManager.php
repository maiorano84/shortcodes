<?php
namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Exceptions\DeregisterException;
use Maiorano\Shortcodes\Parsers\ParserInterface;
use \ArrayAccess;
use \IteratorAggregate;
use \ArrayIterator;

/**
 * Class BaseManager
 * @package Maiorano\Shortcodes\Manager
 */
abstract class BaseManager implements ArrayAccess, IteratorAggregate
{

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var array
     */
    protected $shortcodes = [];

    /**
     * @param array $shortcodes
     * @param ParserInterface $parser
     * @throws RegisterException
     */
    public function __construct(array $shortcodes = [], ParserInterface $parser)
    {
        $this->parser = $parser;
        foreach ($shortcodes as $k => $s) {
            $this->register($s);
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws RegisterException
     */
    public function offsetSet($offset, $value)
    {
        $this->register($value);
    }

    /**
     * @param mixed $offset
     * @throws DeregisterException
     */
    public function offsetUnset($offset)
    {
        $this->deregister($offset);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->isRegistered($offset);
    }

    /**
     * @param mixed $offset
     * @return null
     * @throws RegisterException
     */
    public function offsetGet($offset)
    {
        if (!$this->isRegistered($offset)) {
            $e = sprintf(RegisterException::MISSING, $offset);
            throw new RegisterException($e);
        }

        return $this->shortcodes[$offset];
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->shortcodes);
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @param string $alias
     * @param bool $includeAlias
     * @return ManagerInterface
     * @throws RegisterException
     */
    public function register(ShortcodeInterface $shortcode, $alias = null, $includeAlias = true)
    {
        $name = $alias ?: $shortcode->getName();

        if (!$name) {
            throw new RegisterException(RegisterException::BLANK);
        } elseif ($this->isRegistered($name)) {
            $e = sprintf(RegisterException::DUPLICATE, $name);
            throw new RegisterException($e);
        }

        $this->shortcodes[$name] = $shortcode;
        $shortcode->bind($this);

        if ($includeAlias && $shortcode instanceof AliasInterface) {
            foreach ($shortcode->getAlias() as $alias) {
                $shortcode->alias($alias);
            }
        }

        return $this;
    }

    /**
     * @param $name
     * @param bool $includeAlias
     * @return ManagerInterface
     * @throws DeregisterException
     */
    public function deregister($name, $includeAlias = true)
    {
        if ($this->isRegistered($name)) {
            $shortcode = $this->shortcodes[$name];

            if ($includeAlias && $shortcode instanceof AliasInterface) {
                foreach ($this->shortcodes[$name]->getAlias() as $alias) {
                    unset($this->shortcodes[$alias]);
                }
            }
            unset($this->shortcodes[$name]);

            return $this;
        }
        $e = sprintf(DeregisterException::MISSING, $name);
        throw new DeregisterException($e);
    }

    /**
     * @param $name
     * @return bool
     */
    public function isRegistered($name)
    {
        return isset($this->shortcodes[$name]);
    }

    /**
     * @return array
     */
    public function getRegistered()
    {
        return array_keys($this->shortcodes);
    }


    /**
     * @param string $name
     * @param string $alias
     * @return ManagerInterface $this
     * @throws RegisterException
     */
    public function alias($name, $alias)
    {
        if(!($this[$name] instanceof AliasInterface)) {
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
     * @return ParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }
}