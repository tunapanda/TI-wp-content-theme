<?php

	require_once __DIR__."/../../xapi.php";

	$xapi=new Xapi(
		"http://localhost/repo/learninglocker/public/data/xAPI/",
		"7b880fc1f371715ce24309b90e051fcd24d700c3",
		"c089ce76ca667862e615995b909f2ddf9acc1795"
	);

/*	$params=array(
		"activity"=>"http://localhost/wordpress/index.php/courses/testing/how-are-you/",
		"verb"=>"http://adlnet.gov/expapi/verbs/completed",
		"related_activities"=>"true"
	);*/

	$params=array(
		"activity"=>"http://swag.tunapanda.org/",
		"verb"=>"http://adlnet.gov/expapi/verbs/completed",
		"related_activities"=>"true"
	);

	$statements=$xapi->getStatements($params);

	echo "statements: ".sizeof($statements)."\n";