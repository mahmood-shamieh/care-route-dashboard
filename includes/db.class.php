<?php
class db
{
	var $dbConnect;
	function connect($db, $server = "localhost", $user = "root", $pass = "")
	{
		$this->dbserver = trim($server);
		$this->dbuse = trim($user);
		$this->dbpass = trim($pass);
		$this->dbname = trim($db);
		$this->dbConnect = mysqli_connect($this->dbserver, $this->dbuse, $this->dbpass);
		if ($this->dbConnect) {
			$this->dbSelected = mysqli_select_db($this->dbConnect, $this->dbname);
			if (!$this->dbSelected) {
				die($this->debug(mysqli_error($this->dbConnect)));
			}
			mysqli_set_charset($this->dbConnect, 'utf8');
		} else {
			die($this->debug(mysqli_error($this->dbConnect)));
		}
	}

	function affect()
	{
		return mysqli_affected_rows($this->dbConnect);
	}

	function debug($error, $query = NULL)
	{
		$this->error = $error;
		$this->query = $query;
		$message = '<div align="center" ><div style="width:500px;;border:1px dashed red;font-size:15px;padding:15px;margin:10px;direction:ltr;text-align:left;">' .
			'<p style="color:red;	font-weight:bolder;">&nbsp;&nbsp;' .
			'ERROR :</p>' .
			'<p style="direction:ltr;text-align:left;"><strong>' . $this->error . '</strong></p>';
		if (!is_null($this->query)) {
			$message .= '<p> your SQL Statement was  :</p> ' .
				'<p style="direction:ltr;text-align:left;"><strong>' . $this->query . '</strong></p>';
		}
		$message .= '</div></div>';
		return $message;
	}

	function sqlSafe($value, $quote = "'")
	{
		$value = str_replace(array("\'", "'", '"' . '\"'), "", $value);
		$value = stripslashes($value);
		$value = mysqli_real_escape_string($this->dbConnect, $value);
		$value = $quote . $value . $quote;
		return $value;
	}

	function dbquery($query)
	{
		$this->query = $query;
		$result = mysqli_query($this->dbConnect, $this->query) or die($this->debug(mysqli_error($this->dbConnect), $this->query));
		return 'done';
	}

	function close()
	{
		mysqli_close($this->dbConnect);
	}
	function select($query)
	{
		$output = array();
		$this->query = $query;
		// print($this->query.'<br>');
		$result = mysqli_query($this->dbConnect, $this->query) or die($this->debug(mysqli_error($this->dbConnect), $this->query));
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$rows = mysqli_fetch_assoc($result);
			$output[$i] = $rows;
		}
		mysqli_free_result($result);

		return $output;
	}
	function insert($table, $values, $activeTracking = false)
	{
		$this->tableName = trim($table);
		$this->value = $values;
		if (!is_array($this->value)) {
			die($this->debug('The Value that you are trying to deal with is not an array'));
		}
		$count = 0;
		foreach ($this->value as $key => $val) {
			if ($count == 0) {
				$this->fields =       "`" . $key . "`";
				$this->fieldsValues = $val;
			} else {
				$this->fields  .=     ", " . "`" . $key . "`";
				$this->fieldsValues .= ", " . $val . " ";
			}
			// print($key ." : ".$val."<br>");
			$count++;
		}
		$this->query = sprintf("insert into %s (%s) values(%s)", $this->tableName, $this->fields, $this->fieldsValues);
		// print($this->query);
		// die;
		$result = mysqli_query($this->dbConnect, $this->query) or die($this->debug(mysqli_error($this->dbConnect), $this->query));

		if ($this->affect()) {
			$done = mysqli_insert_id($this->dbConnect);
		} else {
			$done = 0;
		}
		if ($activeTracking && isset($_COOKIE['cmd_id'])) {
			$data = $this->select('SELECT * FROM `' . $this->tableName . '` WHERE `Id` = ' . $done);
			$values = array(
				'admin_id' => $_COOKIE['admin_id'],
				'action' => $this->sqlSafe('أضافة'),
				'table_name' => $this->sqlsafe($this->tableName),
				'cmd_id' => $_COOKIE['cmd_id'],
				// 'record_id' => mysqli_insert_id($this->dbConnect),
				'Xdate' => $this->sqlSafe(time())
			);
			foreach ($data[0] as $key => $value) {
				if (!empty($value))
					$values['record_info'] .= $key . " : " . $value . "<br>";
			}
			$values['record_info'] = $this->sqlsafe($values['record_info']);
			$this->insert('admin_tracking', $values, false);
		}
		return $done;
	}
	function truncate($table,  $activeTracking = false)
	{
		$this->tableName = trim($table);
		$this->query = "TRUNCATE TABLE " . $this->tableName;
		$result = mysqli_query($this->dbConnect, $this->query) or die($this->debug(mysqli_error($this->dbConnect), $this->query));
		if ($this->affect()) {
			$done = mysqli_insert_id($this->dbConnect);
		} else {
			$done = 0;
		}
		if ($activeTracking && isset($_COOKIE['cmd_id'])) {
			$data = $this->select('SELECT * FROM `' . $this->tableName . '` WHERE `Id` = ' . $done);
			$values = array(
				'admin_id' => $_COOKIE['admin_id'],
				'action' => $this->sqlSafe('تفريغ'),
				'table_name' => $this->sqlsafe($this->tableName),
				'cmd_id' => $_COOKIE['cmd_id'],
				// 'record_id' => mysqli_insert_id($this->dbConnect),
				'Xdate' => $this->sqlSafe(time())
			);
			$this->insert('admin_tracking', $values, false);
		}
		return $done;
	}




	function update($table, $values, $where = 1,   $limit = 1, $enableTracking = false)
	{
		$this->tableName = trim($table);
		$this->value = $values;
		$this->where = $where;
		$this->limit = $limit;
		$data = $this->select('SELECT * FROM `' . $this->tableName . '` WHERE ' . $this->where);
		if (!is_array($this->value)) {
			die($this->debug('The Value that you are trying to deal with is not an array'));
		}
		$count = 0;
		$this->query = 'update ' . $this->tableName . ' set ';
		foreach ($this->value as $key => $val) {
			if ($count == 0) {
				$this->query .= " `$key`= " . $val . " ";
			} else {
				$this->query .= " , `$key`= " . $val . " ";
			}
			$count++;
		}
		$this->query .= "  WHERE $this->where  LIMIT $this->limit ";
		// print($this->query);
		// die;
		// $result = mysqli_query( $this->query ) or die( $this->debug(mysqli_error($this->dbConnect),$this->query) );
		$result = mysqli_query($this->dbConnect, $this->query) or die($this->debug(mysqli_error($this->dbConnect), $this->query));
		$data1 = $this->select('SELECT * FROM `' . $this->tableName . '` WHERE ' . $this->where);
		if ($enableTracking && isset($_COOKIE['cmd_id'])) {
			$values = array(
				'admin_id' => $_COOKIE['admin_id'],
				'action' => $this->sqlSafe('تعديل'),
				'table_name' => $this->sqlsafe($this->tableName),
				'cmd_id' => $_COOKIE['cmd_id'],
				'Xdate' => $this->sqlSafe(time())
			);
			$values['record_info'] = '<hr> المعلومات قبل <hr>';
			foreach ($data[0] as $key => $value) {
				if (!empty($value))
					$values['record_info'] .= $key . " : " . $value . "<br>";
			}
			$values['record_info'] .= '<hr> المعلومات بعد <hr>';
			foreach ($data1[0] as $key => $value) {
				if (!empty($value))
					$values['record_info'] .= $key . " : " . $value . "<br>";
			}
			$values['record_info'] = $this->sqlsafe($values['record_info']);
			$this->insert('admin_tracking', $values, false);
		}
		return true;
	}


	function delete($table, $where)
	{
		$this->table = trim($table);
		$this->where = $where;
		$data = $this->select('SELECT * FROM `' . $this->table . '` WHERE ' . $this->where);
		$this->query = sprintf("DELETE from %s WHERE %s", $this->table, $this->where);
		// print($this->query);
		// print('<hr>');
		$result = mysqli_query($this->dbConnect, $this->query) or die($this->debug(mysqli_error($this->dbConnect), $this->query));
		if ($this->affect()) {
			$done = true;
		} else {
			$done = false;
		}

		return $done;
	}

	function checkAndAdd($tablename, $where)
	{

		$tablename = trim($tablename);
		$where = trim($where);
		$data = $this->select("SELECT * FROM `" . $tablename . "` where " . $where);
		switch (count($data)) {
			case 0: {
					$data = array();
					if (str_contains($where, ' AND ')) {
						$whereValues = explode('AND', $where);
						foreach ($whereValues as $v) {
							$v = trim($v);
							$v = substr($v, 1);
							$key = substr($v, 0, strpos($v, '`'));
							$val = substr($v, strpos($v, '=') + 1);
							$data[$key] = $this->sqlsafe(trim($val));
						}
					} elseif (str_contains($where, ' and ')) {
						$whereValues = explode('and', $where);
						foreach ($whereValues as $v) {
							$v = trim($v);
							$v = substr($v, 1);
							$key = substr($v, 0, strpos($v, '`'));
							$val = substr($v, strpos($v, '=') + 1);
							$data[$key] = $this->sqlsafe(trim($val));
						}
					} elseif (str_contains($where, ' OR ')) {
						print("<h1>Or</h1>");
						$whereValues = explode('OR', $where);
						foreach ($whereValues as $v) {
							$v = trim($v);
							$v = substr($v, 1);
							$key = substr($v, 0, strpos($v, '`'));
							$val = substr($v, strpos($v, '=') + 1);
							$data[$key] = $this->sqlsafe(trim($val));
						}
					} elseif (str_contains($where, ' or ')) {
						$whereValues = explode('or', $where);

						foreach ($whereValues as $v) {
							$v = trim($v);
							$v = substr($v, 1);
							$key = substr($v, 0, strpos($v, '`'));
							$val = substr($v, strpos($v, '=') + 1);
							$data[$key] = $this->sqlsafe(trim($val));
						}
					} else {
						$v = trim($where);
						$v = substr($v, 1);
						$key = substr($v, 0, strpos($v, '`'));
						$val = substr($v, strpos($v, '=') + 1);
						$data[$key] = $this->sqlsafe(trim($val));
					}
					return $this->insert($tablename, $data, false);
					break;
				}
			case 1: {
					return $data[0]['Id'];
				}
			default:
				throw new Exception("there is no unique data in database");
				return null;
				break;
		}
	}
}
