<?php

require_once dirname(__DIR__,3 ) .  "/php/lib/xsrf.php";
require_once dirname(__DIR__,3 ) .  "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use Gkephart\Assessment\Post;

$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

$secrets =  new \Secrets("/etc/apache2/capstone-mysql/assessment.ini");
$pdo = $secrets->getPdoObject();

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

try {
	//verify the HTTP method being used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$postAuthor = filter_input(INPUT_GET, "postAuthor", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	if($method === "GET") {
		setXsrfCookie();

		if(empty($id) === false) {
			$reply->data = Post::getPostByPostId($pdo,$id);
		} if(empty($postAuthor) === false) {
			$reply->data = Post::getPostsByPostAuthor($pdo,$postAuthor);
		} else {
			$reply->data = Post::getAllPosts($pdo);
		}
	} elseif($method === "POST") {
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if(empty($requestObject->postAuthor) === true) {
			throw(new \InvalidArgumentException ("No author for post.", 405));
		}

		if(empty($requestObject->postContent) === true) {
			throw(new \InvalidArgumentException ("No content for post.", 405));
		}

		if(empty($requestObject->postTitle) === true) {
			throw(new \InvalidArgumentException ("No Author for post.", 405));
		}

		$post = new Post(generateUuidV4(), $requestObject->postAuthor, $requestObject->postContent, new \DateTime(), $requestObject->postTitle);
		$post->insert($pdo);
		$reply->message = "Post created successfully";

	}else {
		throw (new \InvalidArgumentException("Attempting to brew coffee with a teapot", 418));
	}
} catch(\Exception  | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();

}
// encode and return reply to front end caller
echo json_encode($reply);