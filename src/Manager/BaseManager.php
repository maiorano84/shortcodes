<?php
namespace Maiorano\WPShortcodes\Manager;

use Maiorano\WPShortcodes\Shortcode\ShortcodeInterface;
use Maiorano\WPShortcodes\Exceptions\WPShortcodeRegisterException;
use Maiorano\WPShortcodes\Exceptions\WPShortcodeDeregisterException;
use \ArrayAccess;
use \IteratorAggregate;
use \ArrayIterator;

/**
 * Class BaseManager
 * @package Maiorano\WPShortcodes\Manager
 */
abstract class BaseManager implements ArrayAccess, IteratorAggregate, ShortcodeManagerInterface
{

    /**
     * @var array
     */
    protected $shortcodes = [];

    /**
     * @param array $shortcodes
     * @throws WPShortcodeRegisterException
     */
    public function __construct(array $shortcodes = [])
    {
        foreach ($shortcodes as $k => $s) {
            $this->register($s);
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws WPShortcodeRegisterException
     */
    public function offsetSet($offset, $value)
    {
        $this->register($value);
    }

    /**
     * @param mixed $offset
     * @throws WPShortcodeDeregisterException
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
     * @throws WPShortcodeRegisterException
     */
    public function offsetGet($offset)
    {
        if ($this->isRegistered($offset)) {
            return $this->shortcodes[$offset];
        }
        $e = sprintf(WPShortcodeRegisterException::MISSING, $offset);
        throw new WPShortcodeRegisterException($e);
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
     * @throws WPShortcodeRegisterException
     */
    public function register(ShortcodeInterface $shortcode)
    {
        $name = $shortcode->getName();
        if (!$this->isRegistered($name)) {
            $this->shortcodes[$name] = $shortcode;

            return $this;
        }
        $e = sprintf(WPShortcodeRegisterException::DUPLICATE, $name);
        throw new WPShortcodeRegisterException($e);
    }

    /**
     * @param $name
     * @return bool
     * @throws WPShortcodeDeregisterException
     */
    public function deregister($name)
    {
        if ($this->isRegistered($name)) {
            unset($this->shortcodes[$name]);

            return $this;
        }
        $e = sprintf(WPShortcodeDeregisterException::MISSING, $name);
        throw new WPShortcodeDeregisterException($e);
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
        return array_map('preg_quote', array_keys($this->shortcodes));
    }

    /**
     * @param null $tags
     * @return string
     * @see https://core.trac.wordpress.org/browser/tags/4.1.1/src/wp-includes/shortcodes.php#L221
     */
    protected function getShortcodeRegex($tags = null)
    {
        $tagregexp = join('|', $tags ?: $this->getRegistered());

        return
            '\\['                // Opening bracket
            . '(\\[?)'           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"     // 2: Shortcode name
            . '(?![\\w-])'       // Not followed by word character or hyphen
            . '('                // 3: Unroll the loop: Inside the opening shortcode tag
            . '[^\\]\\/]*'       // Not a closing bracket or forward slash
            . '(?:'
            . '\\/(?!\\])'       // A forward slash not followed by a closing bracket
            . '[^\\]\\/]*'       // Not a closing bracket or forward slash
            . ')*?'
            . ')'
            . '(?:'
            . '(\\/)'            // 4: Self closing tag ...
            . '\\]'              // ... and closing bracket
            . '|'
            . '\\]'              // Closing bracket
            . '(?:'
            . '('                // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            . '[^\\[]*+'         // Not an opening bracket
            . '(?:'
            . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            . '[^\\[]*+'         // Not an opening bracket
            . ')*+'
            . ')'
            . '\\[\\/\\2\\]'     // Closing shortcode tag
            . ')?'
            . ')'
            . '(\\]?)';          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }
}