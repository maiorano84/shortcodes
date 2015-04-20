<?php
namespace Maiorano\WPShortcodes\Manager;

class ShortcodeManager extends BaseShortcodeManager{

    public function hasShortcode($content, $tags=array())
    {
        /*
         * Determine if the provided content has anything that could possibly denote shortcode
         * If not, return false
         */
        if(false === strpos($content, '[')) return false;

        /*
         * Determine if the provided tags exist in the library
         * If nothing is leftover, there's nothing to check
         */
        $tags = $tags ? $this->cleanTags($tags) : $this->getRegistered();
        if(empty($tags)) return false;

        $regex = $this->getShortcodeRegex($tags);
        preg_match_all("/$regex/s", $content, $matches, PREG_SET_ORDER);
        if(empty($matches)) return false;

        foreach($matches as $shortcode)
        {
            if(in_array($shortcode[2], $tags))
            {
                return true;
            }
            elseif(!empty($shortcode[5]) && $this->hasShortcode($shortcode[5], $tags))
            {
                return true;
            }
        }
        return false;
    }
    private function cleanTags($tags)
    {
        if(is_string($tags))
        {
            $tags = explode('|', $tags);
            $tags = is_string($tags) ? array($tags) : $tags;
        }
        foreach($tags as $i=>$tag)
        {
            if(!$this->isRegistered($tag)) unset($tags[$i]);
        }
        return $tags;
    }

    /*public function get_results($m){
        $tag = $m[2];
        $parsed_attr = $this->shortcode_parse_atts($m[3]);
        $attr = $this->shortcode_atts($this->config['tags'][$tag]['atts'], $parsed_attr);
        return array('content'=>(isset($m[5]) ? $m[5] : null), 'atts'=>$attr);
    }
    private function shortcode_parse_atts($text) {
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
        else{
            $atts = ltrim($text);
        }
        return $atts;
    }
    private function shortcode_atts(array $pairs, array $atts){
        $out = array();
        foreach($pairs as $name => $default){
            $out[$name] = array_key_exists($name, $atts) ? $atts[$name] : $default;
        }
        return $out;
    }
    function has_shortcode( $content, $tag ) {


	        if ( shortcode_exists( $tag ) ) {
	                preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
	                if ( empty( $matches ) )
	                        return false;

	                foreach ( $matches as $shortcode ) {
	                        if ( $tag === $shortcode[2] ) {
	                                return true;
	                        } elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
	                                return true;
	                        }
	                }
	        }
	        return false;
    }
    */
}
