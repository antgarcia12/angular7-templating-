<?php

namespace Gkephart\Assessment;

use Ramsey\Uuid\Uuid;

require_once("autoload.php");

/**
 * Straight foreword php Class. The class instantiates and is error free .
 *
 **/
class Post implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;

	/**
	 * Primary key (uuid v4) for the quote.
	 * @var Uuid $postId
	 */
	private $postId;

	/**
	 * author for the quote.
	 * @var string $postAuthor
	 **/
	private $postAuthor;

	/**
	 * Content for the quote.
	 * @var string $postContent
	 */
	private $postContent;

	/**
	 * Date for the quote.
	 * @var \DateTime $postDate
	 **/
	private $postDate;

	/**
	 * title for the quote
	 * @var string $postTitle
	 */
	private $postTitle;


	/**
	 * Post constructor.
	 *
	 * @param string|Uuid $newPostId unsanitized uuid v4  passed to the setPostId() method.
	 * @param string $newPostAuthor unsanitized value passed to the setPostAuthor() method.
	 * @param string $newPostContent unsanitized value passed to the setPostContent() method.
	 * @param \DateTime $newPostDate unsanitized value passed to the setPostDate() method.
	 * @param string $newPostTitle unsanitized value passed to the setPostTitle() method.
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newPostId, string $newPostAuthor, string $newPostContent, $newPostDate, string $newPostTitle) {

		try {
			$this->setPostId($newPostId);
			$this->setPostAuthor($newPostAuthor);
			$this->setPostContent($newPostContent);
			$this->setPostDate($newPostDate);
			$this->setPostTitle($newPostTitle);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for postId
	 * @return Uuid value of postId
	 **/
	public function getPostId(): Uuid {
		return $this->postId;
	}

	/**
	 * @param $newPostId string|Uuid new uuid v4 value of postId
	 * @throws \RangeException if $newTweetId is not positive
	 * @throws \TypeError if $newTweetId is not a uuid or string
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 */
	public function setPostId($newPostId): void {

		try {
			$uuid = self::validateUuid($newPostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		$this->postId = $uuid;
	}

	/**
	 * accessor method for postAuthor
	 * @return string value of postAuthor.
	 */
	public function getPostAuthor(): string {
		return $this->postAuthor;
	}

	/**
	 * mutator method for postAuthor
	 * @param string $newPostAuthor new value for postAuthor.
	 * @throws \InvalidArgumentException if the postAuthor is empty or insecure.
	 * @throws \RangeException if the postAuthor is longer than 24 characters.
	 *
	 */
	public function setPostAuthor(string $newPostAuthor): void {

		$newPostAuthor = trim($newPostAuthor);
		$newPostAuthor = filter_var($newPostAuthor, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPostAuthor) === true) {
			throw(new \InvalidArgumentException("post author is empty or insecure"));
		}

		if(strlen($newPostAuthor) > 24) {
			throw(new \RangeException("post author is too large"));
		}


		$this->postAuthor = $newPostAuthor;
	}

	/**
	 * accessor method for postContent.
	 *
	 * @return string value of postContent
	 */
	public function getPostContent(): string {
		return $this->postContent;
	}

	/**
	 * mutator method for post content.
	 * @param string $newPostContent new value for postContent.
	 * @throws \InvalidArgumentException if the postContent is empty or insecure.
	 * @throws \RangeException if the postContent is longer than 1024 characters.
	 */
	public function setPostContent(string $newPostContent): void {

		$newPostContent = trim($newPostContent);
		$newPostContent = filter_var($newPostContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		if(empty($newPostContent) === true) {
			throw(new \InvalidArgumentException("post content is empty or insecure"));
		}

		if(strlen($newPostContent) > 1024) {
			throw(new \RangeException("post content is too large"));
		}

		$this->postContent = $newPostContent;
	}

	/**
	 * accessor method for postDate.
	 * @return \DateTime value of postDate
	 **/
	public function getPostDate(): \DateTime {
		return $this->postDate;
	}

	/**
	 * mutator method for postDate
	 * @param \DateTime $newPostDate new value for postDate
	 * @throws \InvalidArgumentException if newPostDate is not a valid object
	 * @throws \RangeException if newPostDate is not a valid datetime
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 *
	 */
	public function setPostDate($newPostDate): void {

		// store the like date using the ValidateDate trait
		try {
			$newPostDate = self::ValidateDateTime($newPostDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		$this->postDate = $newPostDate;
	}

	/**
	 *accessor method for postTitle
	 * @return string value of postTitle
	 */
	public function getPostTitle(): string {
		return $this->postTitle;
	}

	/**
	 * @param string $newPostTitle new value for postTitle.
	 * @throws \InvalidArgumentException if the postTitle is empty or insecure.
	 * @throws \RangeException if the postTitle is longer than 24 characters.
	 *
	 */
	public function setPostTitle(string $newPostTitle): void {

		$newPostTitle = trim($newPostTitle);
		$newPostTitle = filter_var($newPostTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		if(empty($newPostTitle) === true) {
			throw(new \InvalidArgumentException("post title is empty or insecure"));
		}

		if(strlen($newPostTitle) > 24) {
			throw(new \RangeException("post title is too large"));
		}
		$this->postTitle = $newPostTitle;
	}

	/**
	 * inserts the post into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {

		$query = "INSERT INTO post	(postId, postAuthor, postContent, postDate, postTitle)  VALUES (:postId, :postAuthor, :postContent, :postDate, :postTitle)";
		$statement = $pdo->prepare($query);

		$parameters = ["postId" => $this->postId->getBytes(), "postAuthor" => $this->postAuthor, "postContent" => $this->postContent, "postDate" => $this->postDate->format("Y-m-d H:i:s.u"),"postTitle" => $this->postTitle];
		$statement->execute($parameters);
	}

	/**
	 * gets the post by postId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $tweetId tweet id to search for
	 * @return Post|null Tweet found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getPostByPostId(\PDO $pdo, $postId): ?Post {
		// sanitize the tweetId before searching
		try {
			$postId = self::validateUuid($postId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT postId, postAuthor, postContent, postDate, postTitle  FROM post WHERE postId = :postId";
		$statement = $pdo->prepare($query);

		// bind the tweet id to the place holder in the template
		$parameters = ["postId" => $postId->getBytes()];
		$statement->execute($parameters);

		// grab the tweet from mySQL
		try {
			$post = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$post = new Post($row["postId"], $row["postAuthor"], $row["postContent"], $row["postDate"], $row["postTitle"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($post);
	}

	/**
	 * gets the post by postAuthor
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $postAuthor profile id to search by
	 * @return \SplFixedArray SplFixedArray of Tweets found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getPostsByPostAuthor(\PDO $pdo, string $postAuthor): \SPLFixedArray {

		$postAuthor = trim($postAuthor);
		$postAuthor = filter_var($postAuthor, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		// create query template
		$query = "SELECT postId, postAuthor, postContent, postDate, postTitle  FROM post WHERE postAuthor = :postAuthor";
		$statement = $pdo->prepare($query);

		// bind the tweet profile id to the place holder in the template
		$parameters = ["postAuthor" => $postAuthor];
		$statement->execute($parameters);
		// build an array of tweets
		$posts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$post = new Post($row["postId"], $row["postAuthor"], $row["postContent"], $row["postDate"], $row["postTitle"]);
				$posts[$posts->key()] = $post;
				$posts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($posts);
	}

	/**
	 * gets the post by postAuthor
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $postAuthor profile id to search by
	 * @return \SplFixedArray SplFixedArray of Tweets found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllPosts(\PDO $pdo) : \SPLFixedArray {

		// create query template
		$query = "SELECT postId, postAuthor, postContent, postDate, postTitle  FROM post";
		$statement = $pdo->prepare($query);

		$statement->execute();
		// build an array of tweets
		$posts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$post = new Post($row["postId"], $row["postAuthor"], $row["postContent"], $row["postDate"], $row["postTitle"]);
				$posts[$posts->key()] = $post;
				$posts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($posts);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["postDate"] = round(floatval($this->getPostDate()->format("U.u") * 1000));
		return ($fields);
	}
}
