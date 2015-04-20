<?php
namespace Maiorano\WPShortcodes\Manager;

use \ArrayAccess;
use \IteratorAggregate;
use \ArrayIterator;

use Maiorano\WPShortcodes\Shortcode\ShortcodeInterface;
use Maiorano\WPShortcodes\Exceptions\WPShortcodeRegisterException;
use Maiorano\WPShortcodes\Exceptions\WPShortcodeDeregisterException;

abstract class BaseShortcodeManager implements ArrayAccess, IteratorAggregate, ShortcodeManagerInterface{
    protected $shortcodes = [];

    public function offsetSet($offset, $value)
    {
        $this->register($value);
    }

    public function offsetUnset($offset)
    {
        $this->deregister($offset);
    }

    public function offsetExists($offset)
    {
        return $this->isRegistered($offset);
    }

    public function offsetGet($offset)
    {
        return isset($this->shortcodes[$offset]) ? $this->shortcodes[$offset] : null;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->shortcodes);
    }

    public function register(ShortcodeInterface $shortcode)
    {
        $name = $shortcode->getName();
        if(!$this->isRegistered($name)) {
            $this->shortcodes[$name] = $shortcode;
            return $shortcode;
        }
        throw new WPShortcodeRegisterException(sprintf('The shortcode \'%s\' has already been registered', $name));
    }

    public function deregister($name)
    {
        if(isset($this->shortcodes[$name])){
            unset($this->shortcodes[$name]);
            return true;
        }
        throw new WPShortcodeDeregisterException(sprintf('The shortcode \'%s\' does not exist in the current library', $name));
    }

    public function isRegistered($name)
    {
        return isset($this->shortcodes[$name]);
    }

    public function getRegistered()
    {
        return array_map('preg_quote', array_keys($this->shortcodes));
    }

    protected function getShortcodeRegex($tags=null){
        $tagregexp = join('|', $tags ?: $this->getRegistered());
        return
            '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }
}