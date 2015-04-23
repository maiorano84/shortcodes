<?php
namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Exceptions\ShortcodeRegisterException;
use Maiorano\Shortcodes\Exceptions\ShortcodeDeregisterException;
use Maiorano\Shortcodes\Parsers\ParserInterface;
use \ArrayAccess;
use \IteratorAggregate;
use \ArrayIterator;

/**
 * Class BaseManager
 * @package Maiorano\Shortcodes\Manager
 */
abstract class BaseManager implements ArrayAccess, IteratorAggregate, ManagerInterface
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
     * @throws ShortcodeRegisterException
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
     * @throws ShortcodeRegisterException
     */
    public function offsetSet($offset, $value)
    {
        $this->register($value);
    }

    /**
     * @param mixed $offset
     * @throws ShortcodeDeregisterException
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
     * @throws ShortcodeRegisterException
     */
    public function offsetGet($offset)
    {
        if ($this->isRegistered($offset)) {
            return $this->shortcodes[$offset];
        }
        $e = sprintf(ShortcodeRegisterException::MISSING, $offset);
        throw new ShortcodeRegisterException($e);
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
     * @return ShortcodeInterface
     * @throws ShortcodeRegisterException
     */
    public function register(ShortcodeInterface $shortcode)
    {
        $name = $shortcode->getName();
        if (!$this->isRegistered($name)) {
            $this->shortcodes[$name] = $shortcode;
            $shortcode->bind($this);

            return $this;
        }
        $e = sprintf(ShortcodeRegisterException::DUPLICATE, $name);
        throw new ShortcodeRegisterException($e);
    }

    /**
     * @param $name
     * @return bool
     * @throws ShortcodeDeregisterException
     */
    public function deregister($name)
    {
        if ($this->isRegistered($name)) {
            unset($this->shortcodes[$name]);

            return $this;
        }
        $e = sprintf(ShortcodeDeregisterException::MISSING, $name);
        throw new ShortcodeDeregisterException($e);
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
     * @return ParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }
}