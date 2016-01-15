<?php

require_once __DIR__."/WpUtil.php";
require_once __DIR__."/xapi.php";
require_once WpUtil::getWpLoadPath();

$completedSwag=array();

$user=wp_get_current_user();
if ($user && $user->user_email) {
	$xapi=new Xapi(
		get_option("ti_xapi_endpoint_url"),
		get_option("ti_xapi_username"),
		get_option("ti_xapi_password")
	);

	$statements=$xapi->getStatements(array(
		"agentEmail"=>$user->user_email,
		"activity"=>"http://swag.tunapanda.org/",
		"verb"=>"http://adlnet.gov/expapi/verbs/completed",
		"related_activities"=>"true"
	));

	foreach ($statements as $statement) {
		$objectId=$statement["object"]["id"];
		$swag=str_replace("http://swag.tunapanda.org/","",$objectId);

		if (!in_array($swag,$completedSwag))
			$completedSwag[]=$swag;
	}
}

$q=new WP_Query(array(
	"post_type"=>"page",
	"post_status"=>"published",
	"posts_per_page"=>-1,
));
$posts=$q->get_posts();

$swagpaths=array();

foreach ($posts as $post) {
	$post->requires=get_post_meta($post->ID,"requires");
	$post->provides=get_post_meta($post->ID,"provides");

	if ($post->requires || $post->provides)
		$swagpaths[]=$post;
}

$swags=array();
$data=array();
$data["nodes"]=array();
$data["links"]=array();

foreach ($swagpaths as $swagpath) {
	$data["nodes"][]=array(
		"name"=>$swagpath->post_title,
		"type"=>"swagpath",
		"url"=>get_permalink($swagpath->ID)
	);

	foreach (array_merge($swagpath->requires,$swagpath->provides) as $swag)
		if (!in_array($swag,$swags))
			$swags[]=$swag;
}

$firstSwagIndex=sizeof($data["nodes"]);
foreach ($swags as $swag) {
	$swagData=array(
		"name"=>$swag,
		"type"=>"swag"
	);

	if (in_array($swag,$completedSwag))
		$swagData["completed"]=TRUE;

	$data["nodes"][]=$swagData;
}

$swagpathIndex=0;
foreach ($swagpaths as $swagpath) {
	foreach ($swagpath->requires as $require) {
		$data["links"][]=array(
			"source"=>$firstSwagIndex+array_search($require,$swags),
			"target"=>$swagpathIndex
		);
	}

	foreach ($swagpath->provides as $provide) {
		$data["links"][]=array(
			"source"=>$swagpathIndex,
			"target"=>$firstSwagIndex+array_search($provide,$swags)
		);
	}

	$swagpathIndex++;
}

echo json_encode($data);
