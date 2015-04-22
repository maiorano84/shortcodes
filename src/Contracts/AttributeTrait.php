<?php
namespace Maiorano\Shortcodes\Contracts;

/**
 * Assists in satisfying the AttributeInterface requirements
 * Allows shortcodes to use the attribute format if needed
 * IE: [tag foo=bar]
 * @package Maiorano\Shortcodes\Contracts
 */
trait AttributeTrait
{

    /**
     * @param string $text
     * @return array
     * @see AttributeInterface::parseAttributes()
     * @link https://core.trac.wordpress.org/browser/tags/4.1.1/src/wp-includes/shortcodes.php#L302
     */
    public function parseAttributes($text)
    {
        $atts = [];
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[7]) and strlen($m[7])) {
                    $atts[] = stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $atts[] = stripcslashes($m[8]);
                }
            }
        }

        return $atts;
    }

    /**
     * Non-strict merge of attributes
     * @param array $atts
     * @return array
     * @see AttributeInterface::getAttributes()
     */
    public function getAttributes(array $atts = [])
    {
        return array_merge($this->attributes, $atts);
    }
}