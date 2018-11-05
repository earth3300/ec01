<?php
/**
 * MySQL Class
 *
 * Where, Limit, Order, Delete. Have to provide connection info.
 *
 * @link: https://gist.github.com/mloberg/1181537
 *
 * File: mysql-read.php
 * Created: 2018-11-05
 * Updated: 2018-11-05
 * Time: 10:09 EST
 */

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/**
 * MySQL Read
 *
 * No Delete, Insert or Update Methods.
*/
class MySQLRead extends MySQLReader
{

  private function read()
  {
  	$reader = new MySQLReader();

  	// get all posts
  	try {
  		$articles = $reader->get('posts');
  		print_r($articles);
  		echo $reader->num_rows(); // number of rows returned
  	} catch(Exception $e) {
  		echo 'Caught exception: ', $e->getMessage();
  	}

  	// get all post titles and authors
  	try{
  		$articles = $reader->get('posts', array('title', 'author');
  		// or
  		$articles = $reader->get('posts', 'title,author');
  		print_r($articles);
  		echo $reader->last_query(); // the raw query that was ran
  	}catch(Exception $e) {
  		echo 'Caught exception: ', $e->getMessage();
  	}

  	// get one post
  	try{
  		$article = $reader->limit(1)->get('posts');
  		print_r($article);
  	}catch(Exception $e){
  		echo 'Caught exception: ', $e->getMessage();
  	}

  	// get post with an id of 1
  	try{
  		$article = $reader->where('id', 1)->get('posts');
  		// or
  		$article = $reader->where(array('id', 1))->get('posts');
  		print_r($article);
  	} catch(Exception $e) {
  		echo 'Caught exception: ', $e->getMessage();
  	}

  	// get all posts by the author of "John Doe"
  	try{
  		$articles = $reader->where(array('author' => 'John Doe'))->get('posts');
  		print_r($articles);
  	}catch(Exception $e){
  		echo 'Caught exception: ', $e->getMessage();
  	}

	} // End function.
} // End class.

/**
 * MySQL Reader Class
 *
 * No Delete, Insert or Update Methods.
*/
class MySQLReader
{
	/** @var $link */
	static private $link = null;

	/** @var array $info */
	static private $info = array(
		'last_query' => null,
		'num_rows' => null,
		'insert_id' => null
	);

	/** @var  $connection_info */
	static private $connection_info = array();

	/** @var string $where */
	static private $where;

	/** @var string $limit */
	static private $limit;

	/** @var string $order */
	static private $order;

	/**
	 * Consruct
	 *
	 * Set up the connection.
	 */
	function __construct($host, $user, $pass, $db){
		self::$connection_info = array('host' => $host, 'user' => $user, 'pass' => $pass, 'db' => $db);
	}

	/**
	 * Close the Connection.
	 *
	 * @return void
	 */
	function __destruct(){
		if(is_resource(self::$link)) mysql_close(self::$link);
	}

	/**
	 * Setter Method
	 *
	 * @param $field
	 * @param $value
	 *
	 * @return self info field=$value
	 */
	static private function set($field, $value){
		self::$info[$field] = $value;
	}

	/**
	 * Get the Last Query.
	 *
	 * @return string
	 */
	public function last_query(){
		return self::$info['last_query'];
	}

	/**
	 * Get the Number of Rows.
	 *
	 * @return integer
	 */
	public function num_rows(){
		return self::$info['num_rows'];
	}

	/**
	 * Create or Return a Connection to the MySQL Server.
	 *
	 * @return mysql $link ???
	 */
	static private function connection(){
		if(!is_resource(self::$link) || empty(self::$link)){
			if(($link = mysql_connect(
				self::$connection_info['host'],
				self::$connection_info['user'],
				self::$connection_info['pass']))
				&& mysql_select_db(
					self::$connection_info['db'],
					$link)
				){
				self::$link = $link;
				mysql_set_charset('utf8');
			}else{
				throw new Exception('Could not connect to MySQL database.');
			}
		}
		return self::$link;
	}

	/**
	 * MySQL Where (Private)
	 *
 	 * @param array $info
 	 * @param string $type
 	 *
 	 * @return string
	 */
	static private function __where($info, $type = 'AND'){
		$link =& self::connection();
		$where = self::$where;
		foreach($info as $row => $value){
			if(empty($where)){
				$where = sprintf("WHERE `%s`='%s'", $row, mysql_real_escape_string($value));
			}else{
				$where .= sprintf(" %s `%s`='%s'", $type, $row, mysql_real_escape_string($value));
			}
		}
		self::$where = $where;
	}

	/**
	 * MySQL Where (Public)
	 *
 	 * @param array|string $field
 	 * @param string $equal
 	 *
 	 * @return string
	 */
	public function where($field, $equal = null){
		if(is_array($field)){
			self::__where($field);
		}else{
			self::__where(array($field => $equal));
		}
		return $this;
	}

	/**
	 * And Where
	 *
 	 * @param array|string $field
 	 * @param string $equal
 	 *
 	 * @return string
	 */
	public function and_where($field, $equal = null){
		return $this->where($field, $equal);
	}

	/**
	 * Or Where
	 *
 	 * @param array|string $field
 	 * @param string $equal
 	 *
 	 * @return string
	 */
	public function or_where($field, $equal = null){
		if(is_array($field)){
			self::__where($field, 'OR');
		}else{
			self::__where(array($field => $equal), 'OR');
		}
		return $this;
	}

	/**
	 * MySQL limit method
	 *
 	 * @param string $limit
 	 *
 	 * @return string
	 */
	public function limit($limit){
		self::$limit = 'LIMIT '.$limit;
		return $this;
	}

	/**
	 * MySQL Order By method
	 *
	 * @param array $by
 	 * @param string $order_type
 	 *
 	 * @return string|bool  Associative
	 */
	public function order_by($by, $order_type = 'DESC'){
		$order = self::$order;
		if(is_array($by)){
			foreach($by as $field => $type){
				if(is_int($field) && !preg_match('/(DESC|desc|ASC|asc)/', $type)){
					$field = $type;
					$type = $order_type;
				}
				if(empty($order)){
					$order = sprintf("ORDER BY `%s` %s", $field, $type);
				}else{
					$order .= sprintf(", `%s` %s", $field, $type);
				}
			}
		}else{
			if(empty($order)){
				$order = sprintf("ORDER BY `%s` %s", $by, $order_type);
			}else{
				$order .= sprintf(", `%s` %s", $by, $order_type);
			}
		}
		self::$order = $order;
		return $this;
	}

	/**
	 * MySQL Query Helper
	 *
	 * @return string
	 */
	static private function extra(){
		$extra = '';
		if(!empty(self::$where)) $extra .= ' '.self::$where;
		if(!empty(self::$order)) $extra .= ' '.self::$order;
		if(!empty(self::$limit)) $extra .= ' '.self::$limit;
		// cleanup
		self::$where = null;
		self::$order = null;
		self::$limit = null;
		return $extra;
	}

	/**
	 * MySQL Query Methods
 	 *
 	 * @param array $table
 	 * @param string $select
 	 *
 	 * @return array|bool  Associative
 	 */
	public function query($qry, $return = false){
		$link =& self::connection();
		self::set('last_query', $qry);
		$result = mysql_query($query);
		if(is_resource($result)){
			self::set('num_rows', mysql_num_rows($result));
		}
		if($return){
			if(preg_match('/LIMIT 1/', $qry)){
				$data = mysql_fetch_assoc($result);
				mysql_free_result($result);
				return $data;
			}else{
				$data = array();
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				mysql_free_result($result);
				return $data;
			}
		}
		return true;
	}

	/**
	 * Get
	 *
	 * @param array $table
	 * @param string $select
	 *
	 * @return array  Associative
	 */
	public function get($table, $select = '*')
	{
		$link =& self::connection();
		if(is_array($select)){
			$cols = '';
			foreach($select as $col){
				$cols .= "`{$col}`,";
			}
			$select = substr($cols, 0, -1);
		}
		$sql = sprintf("SELECT %s FROM %s%s", $select, $table, self::extra());
		self::set('last_query', $sql);
		if(!($result = mysql_query($sql))){
			throw new Exception('Error executing MySQL query: '.$sql.'. MySQL error '.mysql_errno().': '.mysql_error());
			$data = false;
		}elseif(is_resource($result)){
			$num_rows = mysql_num_rows($result);
			self::set('num_rows', $num_rows);
			if($num_rows === 0){
				$data = false;
			}elseif(preg_match('/LIMIT 1/', $sql)){
				$data = mysql_fetch_assoc($result);
			}else{
				$data = array();
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
			}
		}else{
			$data = false;
		}
		mysql_free_result($result);
		return $data;
	}

} // End Class
