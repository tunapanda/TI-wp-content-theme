<?php

require_once __DIR__."/../../src/utils/ShortcodeUtil.php";

$text=
	"[course]\n".
	"  [h5p-course-item slug=\"how-are-you-testing\"]\n".
	"  [h5p-course-item slug=\"introduction-to-p5js\"]\n".
	"  [h5p-course-item title=\"how are you? (testing)\"]\n".
	"[/course]";

/*$shortcodes=ShortcodeUtil::extractShortcodes($text);
print_r($shortcodes);*/

//$text="[h5p-course-item title='something with space']";

/*$shortcodes=ShortcodeUtil::extractShortcodes($text);
print_r($shortcodes);*/

//$text='"hello"world';

print_r(ShortcodeUtil::extractShortcodes($text));
