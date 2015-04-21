<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Class AttributeTrait
 * @package Maiorano\WPShortcodes\Shortcode
 */
trait AttributeTrait{

    /**
     * @param $text
     * @return array
     */
    public function parseAttributes($text)
    {
        $atts = [];
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if(preg_match_all($pattern, $text, $match, PREG_SET_ORDER)){
            foreach($match as $m){
                if(!empty($m[1])){
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                }
                elseif(!empty($m[3])){
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                }
                elseif(!empty($m[5])){
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                }
                elseif(isset($m[7]) and strlen($m[7])){
                    $atts[] = stripcslashes($m[7]);
                }
                elseif(isset($m[8])){
                    $atts[] = stripcslashes($m[8]);
                }
            }
        }

        return $atts;
    }

    /**
     * @param array $atts
     * @return array
     */
    protected function processAttributes($atts)
    {
        return array_merge($this->atts, $atts);
    }
}