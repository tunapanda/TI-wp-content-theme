<?php

require_once __DIR__."/WpUtil.php";
require_once WpUtil::getWpLoadPath();

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
	$data["nodes"][]=array(
		"name"=>$swag,
		"type"=>"swag"
	);
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

//echo "hello: ".sizeof($swagpaths);
/*$data=array(
	"nodes"=>array(
		array(
			"name"=>"Inkscape",
			"type"=>"swagpath"
		),

		array(
			"name"=>"basic-inkscape",
			"type"=>"swag"
		),

		array(
			"name"=>"Introduction to javascript",
			"type"=>"swagpath"
		),

		array(
			"name"=>"basic-javascript",
			"type"=>"swag"
		),

		array(
			"name"=>"Game programming",
			"type"=>"swagpath"
		),

		array(
			"name"=>"game-programming",
			"type"=>"swag"
		)
	),

	"links"=>array(
		array("source"=>0, "target"=>1),
		array("source"=>2, "target"=>3),
		array("source"=>1, "target"=>4),
		array("source"=>3, "target"=>4),
		array("source"=>4, "target"=>5)
	)
);

echo json_encode($data);*/