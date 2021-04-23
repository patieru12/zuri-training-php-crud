<?php
/**
 *
 * MyPDO Class used to manage the database connection
 *
 */
class MyPDO extends PDO
{
    public function __construct($file = 'my_setting.ini')
    {
        if (!$settings = parse_ini_file( dirname(__FILE__) . '/' .$file, TRUE)){
        	throw new exception(  'Unable to open '. dirname(__FILE__) . '/' . $file . '.');
        }
       
        $dns = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
        ';dbname=' . $settings['database']['schema'];
       
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
    }
}

if(!function_exists("db_init")){
	function db_init(&$pdo){
		//Try to check if the users table exist
		try{
			$statement = $pdo->prepare("SHOW TABLES");

			$statement->execute();

			$data = $statement->fetchAll(PDO::FETCH_ASSOC);

			// var_dump($data);
			if(count($data) <= 0){
				//No there is an error database initialization
				if (!$queries = parse_ini_file( dirname(__FILE__) . '/db_setup.ini', TRUE)){
					throw new exception(  'Unable to open '. dirname(__FILE__) . '/db_setup.ini');
				}

				// var_dump($queries);
				$all_queries = $queries['queries'];

				foreach($all_queries AS $q){
					saveData($pdo, $q);// now execute the found queried to configure thr database
				}
			}
		} catch(\Exception $e){
			// require_once "initialize_db.php";
			throw new \Exception($e->getMessage(), 1);
		}
		
	}
}


if(!function_exists("saveData")){
	function saveData($pdo, $query, $params = null){
		// var_dump($query, $params);
		try{
			$statement = $pdo->prepare($query);
			if(is_null($params)){
				$statement->execute();
			} elseif (is_array($params)) {
				$statement->execute($params);
			} else{
				throw new Exception("params should be null or array ".gettype($params)." is found.", 1);
			}
		} catch(\Exception $e){
			// var_dump($params);
			// throw new Exception($e->getMessage()." ".$query." ".implode(",", $params), 1);
			throw new Exception($e->getMessage(), 1);
			
		}
		return true;
	}
}

if(!function_exists("saveAndReturnID")){
	function saveAndReturnID(&$pdo, $query, $params = null){
		try{
			$statement = $pdo->prepare($query);

			if(is_null($params)){
				$statement->execute();
			} elseif (is_array($params)) {
				$statement->execute($params);
			} else{
				throw new Exception("params should be null or array ".gettype($params)." is found.", 1);
			}

			return $pdo->lastInsertId();
		} catch(\Exception $e){
			// var_dump($params);
			// throw new Exception($e->getMessage()." ".$query." ".implode(",", $params), 1);
			throw new Exception( sprintf("%s with query strin of %s ", $e->getMessage(), $query), 1);
		}
		return null;
	}
}

if(!function_exists("insertOrReturnID")){
	function insertOrReturnID(&$pdo, $sql1, $sql2, $field){
		
		$check = returnSingleField($pdo, $sql2,$field);
		if($check){
			return $check;
		}
		return saveAndReturnID($pdo, $sql1);
	}
}


if(!function_exists("first")){
	function first($pdo, $query, $params=null){
		try{
			$statement = $pdo->prepare($query);
			if(is_null($params)){
				$statement->execute();
			} elseif (is_array($params)) {
				$statement->execute($params);
			} else{
				throw new Exception("params should be null or array ".gettype($params)." is found.", 1);
			}
			// var_dump($query, $params);
			return $statement->fetch(PDO::FETCH_ASSOC);

			// var_dump($row);
		} catch(\Exception $e){
			throw new Exception($e->getMessage(), 1);
		}
	}
}


if(!function_exists("returnAllData")){
	function returnAllData(&$pdo, $query, $params=null){
		// var_dump($query);
		// echo $query. implode(" ", $params);
		try{
			// var_dump($query);
			$statement = $pdo->prepare($query);
			if(is_null($params)){
				$statement->execute();
			} elseif (is_array($params)) {
				$statement->execute($params);
			} else{
				throw new Exception("params should be null or array ".gettype($params)." is found.", 1);
			}

			return $statement->fetchAll(PDO::FETCH_ASSOC);
		} catch(Exception $e){
			throw new Exception($e->getMessage(), 1);
			
		}
	}
}

if(!function_exists("returnSingleField")){
	function returnSingleField(&$pdo, $query, $field, $params = null){
		//Here Create the query to return the state
		$statement = $pdo->prepare($query);
		// $statement->execute();
		// var_dump($query);
		if(is_null($params)){
			$statement->execute();
		} elseif (is_array($params)) {
			$statement->execute($params);
		} else{
			throw new Exception("params should be null or array ".gettype($params)." is found.", 1);
		}

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if(!is_array($row)){
			return null;
		}
		if(array_key_exists($field, $row)){
			return $row[$field];
		}
		// var_dump($row, $query, $params);
		throw new Exception("Requested columns '".$field."' is not in the selected columns", 1);
	}
}

$db = new MyPDO();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

db_init($db); //Make sure the database is initialized!
// die();