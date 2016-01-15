<?php

/**
 * Wordpress shortcode util.
 */
class ShortcodeUtil {

	/**
	 * Extract shortcodes from text.
	 */
	public static function extractShortcodes($text) {
		$res=array();
		preg_match_all("(\[.*?\])",$text,$matches);

		foreach ($matches[0] as $match) {
			$attrs=array();
			$parts=preg_split("/[\s]+/",$match);

			for ($i=0; $i<sizeof($parts); $i++) {
				$parts[$i]=str_replace("[","",$parts[$i]);
				$parts[$i]=str_replace("]","",$parts[$i]);
			}

			$attrs["_"]=$parts[0];
			array_shift($parts);

			foreach ($parts as $part) {
				$keyvalue=explode("=",$part);
				$key=$keyvalue[0];
				$value=$keyvalue[1];

				$value=str_replace("'","",$value);
				$value=str_replace('"',"",$value);

				$attrs[$key]=$value;
			}

			$res[]=$attrs;
		}

		return $res;
	}
}