<?php
namespace Maiorano\WPShortcodes\Manager;

class ShortcodeManager extends BaseShortcodeManager{

    public function hasShortcode($content, $tags=array())
    {
        /*
         * Precheck fails if:
         * 1) Content contains no shortcode
         * 2) Provided tags are not registered
         * */
        $content = $this->preCheckContent($content);
        $tags = $this->preCheckTags($tags);

        if($content === false || $tags === false) return false;

        $regex = $this->getShortcodeRegex($tags);
        preg_match_all("/$regex/s", $content, $matches, PREG_SET_ORDER);

        if(empty($matches)) return false;

        foreach($matches as $shortcode)
        {
            if(in_array($shortcode[2], $tags)) //Shortcode matched
            {
                return true;
            }
            elseif(!empty($shortcode[5]) && $this->hasShortcode($shortcode[5], $tags)) //Nested Shortcode matched
            {
                return true;
            }
        }
        return false;
    }
    public function doShortcode($content, $tags=array())
    {
        $content = $this->preCheckContent($content);
        $tags = $this->preCheckTags($tags);

        if($content === false || $tags === false) return $content;

        $regex = $this->getShortcodeRegex($tags);
        $content = preg_replace_callback("/$regex/s", array($this, 'handleShortcode'), $content);
        return $content;
    }
    public function handleShortcode($match)
    {
        //Escaped shortcode (ie: [[tag]])
        if($match[1] == '[' && $match[6] == ']'){
            return substr($match[0], 1, -1);
        }

        $tag = $match[2];
        $atts = $this->parseAttributes($match[3]);
        $content = isset($match[5]) ? $match[5] : null;

        return $this[$tag]->handle($atts, $content);
    }
    private function preCheckContent($content)
    {
        /*
         * Determine if the provided content has anything that could possibly denote shortcode
         * If not, return false
         */
        if(strpos($content, '[') === false)
        {
            return false;
        }
        return $content;
    }
    private function preCheckTags($tags)
    {
        /*
         * Determine if the provided tags exist in the library
         * If nothing is leftover, there's nothing to check
         */
        if(!$tags) return $this->getRegistered();

        if(is_string($tags))
        {
            $tags = explode('|', $tags);
            $tags = is_string($tags) ? array($tags) : $tags;
        }
        foreach($tags as $i=>$tag)
        {
            if(!$this->isRegistered($tag)) unset($tags[$i]);
        }

        if(empty($tags))
        {
            return false;
        }
        return $tags;
    }
    private function parseAttributes($text) {
        $atts = array();
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
}
