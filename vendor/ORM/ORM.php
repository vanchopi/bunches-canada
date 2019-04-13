<?php


    namespace weekly\vendor\ORM;

    use \PDO;
    use \PDOException;
    use \Exception;

class ORM
{
	const FETCH_ASSOC  = PDO::FETCH_ASSOC;
	const FETCH_NUM    = PDO::FETCH_NUM;
	const FETCH_BOTH   = PDO::FETCH_BOTH;
	const FETCH_COLUMN = PDO::FETCH_COLUMN;

	private static $instance       = null;
	private static $db_config      = null;
	private static $driver_options = null;
	private static $dbc            = null;
	private static $connected      = false;
	private static $registry       = array();
	private static $sql_history    = array();
	private static $active_slug    = null;

	final function __construct( $db_config ) {

	    self::$db_config = $db_config;
		self::connect();
	}

	final public static function instance($db_config = null, $driver_options = null)
	{
        $db_config = ( $db_config ? $db_config : self::$db_config );
		if (self::$instance && !$db_config)
			return self::$instance;
		if (self::$instance && self::$db_config === $db_config && self::$driver_options === $driver_options)
			return self::$instance;

		self::$db_config = $db_config;
		self::$driver_options = $driver_options;
		self::$instance = new self();
	}

	protected static function connect() {

		if (! self::$db_config['server']) {
			throw new Exception('Database configuration not found ! Check settings and will try again, please.');
		}

		try {
			self::$dbc = new PDO(
				self::$db_config['type'].':host='.self::$db_config['server'].(self::$db_config['port']?';port='.self::$db_config['port']:'').';dbname='.self::$db_config['database'].(self::$db_config['socket']? ';unix_socket='.self::$db_config['socket']:''),
				self::$db_config['user'],
				self::$db_config['password'],
				self::$driver_options
			);

			self::$connected = true;
			self::query('SET NAMES ' . self::$db_config['encode']);
		} catch (PDOException $e) {
			throw new Exception("PDO Error!: ".$e->getMessage());
		}
	}

	public static function connected() {
		return self::$connected;
	}

	public static function slug($slug)
	{
		self::$active_slug = $slug;

		return self::$instance;
	}

	public function get()
	{
		if (! array_key_exists(self::$active_slug, self::$registry))
			return null;

		$key = self::forgetActiveSlug();
		return self::$registry[$key];
	}

	private static function forgetActiveSlug()
	{
		$key = self::$active_slug;
		self::$active_slug = null;
		return $key;
	}

	private static function getBySlug()
	{
		if (self::$active_slug && array_key_exists(self::$active_slug, self::$registry))
			return self::$registry[self::$active_slug];

		return null;
	}

	private static function setBySlug($result)
	{
		if (! self::$active_slug)
			return $result;

		$key = self::forgetActiveSlug();
		self::$registry[$key] = $result;
		return self::$registry[$key];		
	}

	public static function query($query, $params = null)
	{
		if ($result = self::getBySlug())
		{
			self::forgetActiveSlug();
			return $result;
		}

		self::$sql_history[] = $query;

		if (is_array($params)) {
			$result = self::$dbc->prepare($query);
			$result->execute($params);
		} elseif (! empty($params)) {
			$result = self::$dbc->prepare($query);
			$result->execute(array($params));
		} else {
			$result = self::$dbc->query($query);
		}

		if ((int)self::$dbc->errorCode()) {
			$info = self::$dbc->errorInfo();
			throw new Exception('Error in query syntax: ' . $query . ' in sql: ' . $query, self::$dbc->errorCode());
			return false;
		}

		return self::setBySlug($result->fetch(ORM::FETCH_ASSOC));
	}

	public static function queryAll($query, $params = null)
	{
		if ($result = self::getBySlug())
		{
			self::forgetActiveSlug();
			return $result;
		}

		self::$sql_history[] = $query;

		if (is_array($params)) {
			$result = self::$dbc->prepare($query);
			$result->execute($params);
		} else {
			$result = self::$dbc->query($query);
		}

		if (! $result) {
			$info = self::$dbc->errorInfo();
			throw new Exception('Error in query syntax: ' . $info[2].' in sql: '.$query. self::$dbc->errorCode());
			return false;
		}

		return self::setBySlug($result->fetchAll(ORM::FETCH_ASSOC));
	}

	/**
	 * Добавить строки в базу
	 *
     * @throws \Exception
	 * @param string $table_name Название таблицы
	 * @param array  $arr       Массив колонок и знаничей либо массив массивов колонок и значений
	 *
	 * @return mixed   last insert id
	 */
	public static function insert($table_name = null, $arr = null, $where = '')
	{
		if (! ($arr && $table_name))
			return;

		if (gettype($arr[0]) == 'array') {
			$query = 'INSERT INTO `' . $table_name . '` (`' . implode('`,`', array_keys($arr)) . '`) VALUES ';
			$exec_arr = array();
			foreach ($arr as $i => $row) {
				$query .= '(';
				foreach ($row as $key => $value) {
					$exec_arr[$key . '_' . $i] = $value;
					$query .= ':' . $key . '_' . $i . ',';
				}
				$query .= substr($query, 0, -1) . '),';
			}
			$query = substr($query, 0, -1) . ';';
		} else {
			$exec_arr = $arr;
			$query = 'INSERT INTO `' . $table_name . '` (`' . implode('`,`', array_keys($arr)) . '`) VALUES (:' . implode(',:', array_keys($arr)) . ' ' . $where . ' );';
		}
		$res = self::$dbc->prepare($query);
		$res->execute($exec_arr);

		if ((int)$res->errorCode()) {
			throw new Exception('Error in query syntax: ' . print_r($res->errorInfo(), true) . ' in sql: ' . $res->errorCode());
		}

		return self::$dbc->lastInsertId();
	}

	/**
	 * Обновнить информацию в таблице
	 *
     * @throws \Exception
	 * @param string $tablename Название таблицы
	 * @param array  $arr       Массив названий колонок и их значений
	 * @param array  $where     Условие WHERE
	 *
	 * @return mixed   Количество обновленных колонок
	 */
	public static function save($tablename = null, $arr = null, $where = null)
	{

		if (! ($arr && $tablename && $where)) {
			throw new Exception('primary key!');
			return ;
		}

		$set = array();
		foreach ($arr as $key => $value)
			$set[] = '`' . $key . '` = :' . $key;

		$query = 'UPDATE ' . $tablename . ' SET ' . implode(', ', $set) . ' WHERE ' . implode(' && ', $where) . ';';

		self::$sql_history[] = $query;

		$res = self::$dbc->prepare($query);
		$res->execute($arr);

		if ((int)$res->errorCode())
			print_r($res->errorInfo());

		return $res->rowCount();
	}

	/**
	 * Удалить данные из таблицы
	 *
	 * @param string $tablename Название таблицы
	 * @param array  $where     Условия WHERE
	 *
	 * @return int   Количество удаленных строк
	 */
	public static function delete($tablename, $where = null)
	{
		$query = 'DELETE FROM `' . $tablename . '`';
		if ($where)
			$query .= ' WHERE ' . implode(' && ', $where);
		$query .= ';';

		self::$sql_history[] = $query;

		$res = self::$dbc->prepare($query);
		$res->execute();

		if ((int)$res->errorCode())
			print_r($res->errorInfo());

		return $res->rowCount();
	}


	/**
	 * Execute an SQL statement and return the number of affected rows
	 * Just analog of PDO functions
	 * @param string $query
	 * @param boolean $escapeCheck - Need to escape for deletes,updates and inserts?
	 * @return integer numrows
	 */
	public static function exec($query = '', $escapeCheck = false) {
		self::$sql_history[] = $query;

		if ($escapeCheck)
			return self::$dbc->exec($query);

		return false;
	}

	/**
	 * Begins the transaction
	 * @see PDO->beginTransaction();
	 * @return Boolean
	 */
	public static function beginTransaction() {
		return self::$dbc->beginTransaction();
	}

	/**
	 * Rolls back the transaction
	 * @see PDO->rollBack()
	 * @return Boolean
	 */
	public static function rollBack() {
		return self::$dbc->rollBack();
	}

	/**
	 * Commits the transaction
	 * @see PDO->commit()
	 * @return boolean
	 */
	public static function commit() {
		return self::$dbc->commit();
	}

	/**
	 * returns true, if has alternative transaction;
	 * @return boolean
	 */
	public static function hasActiveTransaction() {
		return self::$dbc->hasActiveTransaction();
	}

	/**
	 * Fetch extended error information associated with the last operation on the database handle
	 * @return string errorInfo
	 */
	public static function errorInfo() {
		return self::$dbc->errorInfo();
	}

	/**
	 *  Fetch the SQLSTATE associated with the last operation on the database handle
	 * @return string errorCode
	 */
	public static function errorCode() {
		return self::$dbc->errorCode();
	}

	public static function lastInsertId() {
		return self::$dbc->lastInsertId();
	}

	/**
	 * Self class name
	 *
	 * @return string
	 */
	public function __toString() {
		return __CLASS__ ;//. ": [{$this->code}]: {$this->message}\n";
	}

	/**
	 * Get PDO connection attributes and db status
	 * @return mixed array
	 */
	public static function getConnectionAttributes() {
		$attributes = array(
				'AUTOCOMMIT', 'ERRMODE', 'CASE', 'CLIENT_VERSION', 'CONNECTION_STATUS',
				'ORACLE_NULLS', 'PERSISTENT', 'SERVER_INFO', 'SERVER_VERSION'
		);
		$res = array();
		foreach ($attributes as $val) {
			$res['PDO::ATTR_'.$val] = self::$dbc->getAttribute(constant('PDO::ATTR_'.$val));
		}
		return $res;
	}

	/**
	 * Function like mysql_real_escape_string in older DB versions
	 *
	 * @param string $string
	 * @return string
	 * @access public
	 */
	public static function escapeString($string=NULL) {
		return $string;
	}

	/**
	 * Unescape
	 */
	private function unescapeString($string=NULL) {
		return $string;
	}

	/**
	 * Returns the array of SQL hostory
	 *
	 * @access public static
	 * @return array mixed
	 */
	public static function getHistory() {
		return self::$sql_history;
	}

	/**
	 * Gets the PDO Object
	 * @return PDO
	 */
	public static function getPDO() {
		return self::$dbc;
	}

}