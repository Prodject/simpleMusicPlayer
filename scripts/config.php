<?php
	define('PATH_TO_CONTENT','media/');
	define('ROOT_DIRECTORY','media/');
	define('PATH_TO_ASSETS_FOR_FILES','assets/');

	ini_set('memory_limit','96M');
	ini_set('post_max_size','64M');
	ini_set('upload_max_filesize','64M');

	function exec_sql_query($db, $sql, $params = array()) {
		$query = $db->prepare($sql);
		if ($query and $query->execute($params)) {
			return $query;
		}
		return NULL;
	}

	// open connection to database
	function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
		if (!file_exists($db_filename)) {
			$db = new PDO('sqlite:' . $db_filename);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$db_init_sql = file_get_contents($init_sql_filename);
			if ($db_init_sql) {
				try {
					$result = $db->exec($db_init_sql);
					if ($result) {
						return $db;
					}
				}
				catch (PDOException $exception) {
					unlink($db_filename);
					throw $exception;
				}
			}
		} else {
			$db = new PDO('sqlite:' . $db_filename);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		}
		return NULL;
	}

	function makedirs($dirpath, $mode=0777) {
    	return is_dir($dirpath) || mkdir($dirpath, $mode, true);
	}

	function closeFileNew($arr, $comm = true) {
		print(json_encode($arr, JSON_UNESCAPED_SLASHES));
		return;
	}
		

	function closeFile($arr, $comm = true) {
		global $db;
		if ($comm) $db->commit();
		else $db->rollback();
		$db->close();
		print(json_encode($arr, JSON_UNESCAPED_SLASHES));
		return;
	}

	function myUrlDecode($string) {
	    $entities = array(
	    	'%20',
	    	'%21', 
	    	'%2A', 
	    	'%27', 
	    	'%28', 
	    	'%29', 
	    	'%3B', 
	    	'%3A', 
	    	'%40', 
	    	'%26', 
	    	'%3D', 
	    	'%2B', 
	    	'%24', 
	    	'%2C', 
	    	'%2F', 
	    	'%3F', 
	    	'%25', 
	    	'%23', 
	    	'%5B', 
	    	'%5D');
	    $replacements = array(
	    	' ',
	    	'!', 
	    	'*', 
	    	"'", 
	    	"(", 
	    	")", 
	    	";", 
	    	":", 
	    	"@", 
	    	"&", 
	    	"=",
	    	"+", 
	    	"$", 
	    	",", 
	    	"/", 
	    	"?", 
	    	"%", 
	    	"#", 
	    	"[", 
	    	"]");
	    return str_replace($entities, $replacements, $string);
	}

	function convertToSeconds($str_time) {
    	sscanf($str_time, "%d:%d.%d", $minutes, $seconds, $milliseconds);
    	$time_seconds = isset($milliseconds) ? ($minutes*60 + $seconds) . "." . $milliseconds : ($minutes*60 + $seconds) . ".000";
    	return $time_seconds;
    }

    function convertToMilliseconds($str_time) {
    	sscanf($str_time, "%d:%d.%d", $minutes, $seconds, $milliseconds);
    	$time_seconds = isset($milliseconds) ? $minutes*60000 + $seconds*1000 + $milliseconds : $minutes*60000 + $seconds*1000;
    	return $time_seconds;
    }

	function myUrlEncode($string) {
		//$entities = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		$entities = array('!', '*', "'", "(", ")", ":", "[", "]");
		$replacements = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
	    return str_replace($entities, $replacements, $string);
	}

	function decodeLyricsForPrint($string) {
		$htmlEntities = array('|NL|');
		$printEntities = array('<br/>');
		return str_replace( $htmlEntities, $printEntities, $string );
	}
?>