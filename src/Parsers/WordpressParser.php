<?php
namespace Maiorano\Shortcodes\Parsers;

/**
 * Class WordpressParser
 * @package Maiorano\Shortcodes\Parsers
 */
class WordpressParser implements ParserInterface
{

    /**
     * @param $content
     * @param array $tags
     * @param callable $callback
     * @return mixed
     * @link https://core.trac.wordpress.org/browser/tags/4.2/src/wp-includes/shortcodes.php#L203
     */
    public function parseContent($content, array $tags = [], Callable $callback = null)
    {
        $tagregexp = join('|', array_map('preg_quote', $tags));
        $regex =
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


        preg_match_all("/$regex/", $content, $matches, PREG_SET_ORDER);

        if (is_null($callback)) {
            $results = [];
            foreach ($matches as $match) {
                $results[] = $this->parseShortcode($match);
            }

            return $results;
        }

        return preg_replace_callback("/$regex/", $callback, $content);
    }

    /**
     * Human-readable format
     * @param array $match
     * @return array
     */
    public function parseShortcode($match)
    {
        return [
            'tag' => $match[2],
            'escaped' => $match[1] == '[' && $match[6] == ']' ? substr($match[0], 1, -1) : null,
            'content' => isset($match[5]) ? $match[5] : null,
            'attributes' => isset($match[3]) ? $this->parseAttributes($match[3]) : []
        ];
    }

    /**
     * @param $text
     * @return array
     * @link https://core.trac.wordpress.org/browser/tags/4.2/src/wp-includes/shortcodes.php#L293
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
}