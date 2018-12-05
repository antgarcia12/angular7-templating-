<?php

namespace Gkephart\Assessment\Test;

use Gkephart\Assessment\Post;

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
require_once ("AssessmentTest.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");



/**
 * Full PHPUnit test for the Post class
 *
 * This is a complete PHPUnit test of the Tweet class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see \GKephart\Assessment\Post
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class PostTest extends AssessmentTest {

	/**
	 * valid post author to create the post  object
	 * @var string $VALID_POST
	 */
	private $VALID_POST_AUTHOR = "GKephart";

	/**
	 * valid post content to create the post  object
	 * @var string $VALID_POST_CONTENT
	 */
	private $VALID_POST_CONTENT = "Nice Things";

	/**
	 * valid post date to create the post  object
	 * @var \DateTime $VALID_POST_DATE
	 */
	private $VALID_POST_DATE;

	/**
	 * valid post to create the post  object
	 * @var $VALID_POST_TITLE
	 */
	private $VALID_POST_TITLE = "Cant have";

	public final function setUp()  : void {
		// run the default setUp() method first
		parent::setUp();
		$this->VALID_POST_DATE = new \DateTime();
	}

	public function testInsertValidPost() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		$post = new Post(generateUuidV4(), $this->VALID_POST_AUTHOR, $this->VALID_POST_CONTENT, $this->VALID_POST_DATE, $this->VALID_POST_TITLE);
		var_dump($post);
		$post->insert($this->getPDO());

		$pdoPost = Post::getPostByPostId($this->getPDO(), $post->getPostId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertEquals($pdoPost->getPostId(), $post->getPostId());
		$this->assertEquals($pdoPost->getPostAuthor(), $this->VALID_POST_AUTHOR);
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_POST_CONTENT);
		$this->assertEquals($pdoPost->getPostDate(), $this->VALID_POST_DATE);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_POST_TITLE);
	}

	public function testGetValidPostByPostAuthor() {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		$post = new Post(generateUuidV4(), $this->VALID_POST_AUTHOR, $this->VALID_POST_CONTENT, $this->VALID_POST_DATE, $this->VALID_POST_TITLE);
		$post->insert($this->getPDO());

		$results = Post::getPostsByPostAuthor($this->getPDO(), $post->getPostAuthor());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("GKephart\\Assessment\\Post", $results);

		$pdoPost = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertEquals($pdoPost->getPostId(), $post->getPostId());
		$this->assertEquals($pdoPost->getPostAuthor(), $this->VALID_POST_AUTHOR);
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_POST_CONTENT);
		$this->assertEquals($pdoPost->getPostDate(), $this->VALID_POST_DATE);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_POST_TITLE);
	}
}