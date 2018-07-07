<?php
namespace Maiorano\Shortcodes\Parsers;

use Closure;

/**
 * Class WordpressParser
 * @package Maiorano\Shortcodes\Parsers
 */
class DefaultParser implements ParserInterface
{
    /**
     * @param string $content
     * @param array $tags
     * @param Closure $callback
     * @return mixed
     */
    public function parseShortcode(string $content, array $tags, Closure $callback = null)
    {
        if (strpos($content, '[') === false && empty($tags)) {
            return is_null($callback) ? [] : $content;
        }

        $regex = $this->getRegex($tags);

        preg_match_all("/$regex/", $content, $matches, PREG_SET_ORDER);

        if (is_null($callback)) {
            return iterator_to_array($this->generateResults($matches));
        }

        return preg_replace_callback("/$regex/", function ($match) use ($callback) {
            if ($match[1] == '[' && $match[6] == ']') {
                return substr($match[0], 1, -1);
            }

            $content = isset($match[5]) ? $match[5] : null;
            $atts = isset($match[3]) ? $this->parseAttributes($match[3]) : [];

            return $callback($match[2], $content, $atts);
        }, $content);
    }

    /**
     * @param string $text
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
                } elseif (isset($m[7]) && strlen($m[7])) {
                    $atts[] = stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $atts[] = stripcslashes($m[8]);
                }
            }
        }

        return $atts;
    }

    /**
     * @param array $tags
     * @return string
     * @link https://core.trac.wordpress.org/browser/tags/4.2/src/wp-includes/shortcodes.php#L203
     */
    private function getRegex(array $tags)
    {
        $tagregexp = join('|', array_map('preg_quote', $tags));

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
            . '(\\/)'            // 4: Self closing tag
            . '\\]'              // followed by closing bracket
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


    /**
     * @param array $matches
     * @return \Generator
     */
    private function generateResults(array $matches){
        foreach ($matches as $match) {
            if ($match[1] == '[' && $match[6] == ']') {
                continue;
            }
            yield [
                'tag' => $match[2],
                'content' => isset($match[5]) ? $match[5] : null,
                'attributes' => isset($match[3]) ? $this->parseAttributes($match[3]) : []
            ];
        }
    }
}
