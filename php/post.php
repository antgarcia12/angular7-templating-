<?php

require_once("classes/autoload.php");

use Gkephart\Assessment\Post;

$secrets =  new \Secrets("/etc/apache2/capstone-mysql/assessment.ini");
$pdo = $secrets->getPdoObject();

try {

	//create the post object
	$object = new Post("9bfacccd-75fe-413e-9886-2bf4e0d7f89e","George", "is a", new DateTime(), "dumb dumb");

	//insert the post object into the database
	$object->insert($pdo);

	//grab the object out of the database
	var_dump(Post::getPostByPostId($pdo, $object->getPostId()));

} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
	echo $exception->getMessage() . PHP_EOL;
	echo $exception->getLine() .PHP_EOL;
}

