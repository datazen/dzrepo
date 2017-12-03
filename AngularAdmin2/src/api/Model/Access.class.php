<?php
class Access {

    public static function getAllAccessLevels() {
	    $sql = "select * FROM accessLevels ORDER BY id";
	    try {
		    $db = Database::getConnection();
		    $stmt = $db->query($sql);  
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}	

	public static function getAccessLevelById($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $lid = end($uriArr);

		$sql = "select * FROM accessLevels WHERE id = :lid";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":lid", $lid, PDO::PARAM_INT);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}

	public static function updateAccessLevel($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();        
        $access = array();
        $access['level'] = (isset($postData['level'])) ? $postData['level'] : null;
        $access['name'] = (isset($postData['name'])) ? $postData['name'] : null;
        $access['lastModified'] = $now->format('Y-m-d H:i:s');

		$sql = "UPDATE accessLevels SET name = :name, lastModified = :lastModified WHERE level = :level";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindvalue(":level", $access['level']);
			$stmt->bindvalue(":name", $access['name']);
			$stmt->bindvalue(":lastModified", $access['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

    public static function getAllPages($request) {
        $routes = $request->getParsedBody(); 

        self::_syncRoutes($routes);

	    $sql = "SELECT pa.*, al.name as accessName FROM pageAccess pa LEFT JOIN accessLevels al ON (pa.level = al.level) ORDER BY pa.id";
	    try {
		    $db = Database::getConnection();
		    $stmt = $db->query($sql);  
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));	    
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}	

    public static function getPagesByAccessLevel($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $level = end($uriArr);

	    $sql = "SELECT page FROM pageAccess WHERE level <= :level ORDER BY id";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":level", $level);
	        $stmt->execute(); 
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));	    
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}

	private static function _syncRoutes($routes) {

        $routeArr = array();
        $prev = '';

		foreach($routes as $route => $data) {

			$route = str_replace("/Admin/", "", $route);
			$route = str_replace("/", "", $route);
			if (strpos($route, ":")) $route = substr($route, 0, strpos($route, ":"));
			if ($route == 'null' || trim($route) == '' || $route == $prev) continue;
			$routeArr[] = $route;
			$prev = $route;
		}	

        // add routes that do not exist in the database
		foreach ($routeArr as $route) {
			$exists = self::_checkRoute($route);  // route does not exists in database
			if (!$exists) {
               self::_addRoute($route);  // add route to the database
			}
		}

		// remove routes from database that are no longer in the $routes array
	    $result = array();
	    $sql = "SELECT * FROM pageAccess ORDER BY id";
	    try {
		    $db = Database::getConnection();
		    $stmt = $db->query($sql);  
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);	    
		    $db = null;
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }	

        foreach ($result as $value) {
            if (!in_array($value['page'], $routeArr)) {
                self::_deleteRoute($value['id']);
            } 
        }
	}

	private static function _checkRoute($route) {	
	    $sql = "SELECT id FROM pageAccess WHERE page = :page";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":page", $route);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);   
		    $db = null;
		    return (isset($result['id']) ? true : false);
	    } catch(PDOException $e) {
			return false;
	    }		
	}

	private static function _addRoute($route) {
        $now = new DateTime();
	    $sql = "INSERT INTO pageAccess (page, level, lastModified) VALUES (:page, :level, :lastModified)";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":page", $route);
	    	$stmt->bindValue(":level", 0);
	    	$stmt->bindValue(":lastModified", $now->format('Y-m-d H:i:s'));
	        $stmt->execute(); 
		    $db = null;
            return true;
        } catch(PDOException $e) {
			return false;
	    }		

	}
	private static function _deleteRoute($id) {
        $now = new DateTime();
	    $sql = "DELETE FROM pageAccess WHERE id = :pid";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindvalue(":pid", $id, PDO::PARAM_INT);
	        $stmt->execute(); 
		    $db = null;
			return true;
	    } catch(PDOException $e) {
			return false;
	    }		
	}	

	public static function getPageById($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $pid = end($uriArr);

		$sql = "select * FROM pageAccess WHERE id = :pid";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":pid", $pid, PDO::PARAM_INT);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}

	public static function getPageByRoute($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $route = end($uriArr);

echo '[' . $route . ']<br>';

	    
		$sql = "select * FROM pageAccess WHERE page = :route";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":route", $route);
	        $stmt->execute(); 
			$info = $stmt->fetch(PDO::FETCH_ASSOC);
			$db = null;
			return json_encode(array('rpcStatus' => 1, 'data' => $info));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function getPageByConfigRoute($request, $route) {

		$sql = "select * FROM pageAccess WHERE page = :route";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":route", $route);
	        $stmt->execute(); 
			$info = $stmt->fetch(PDO::FETCH_ASSOC);
			$db = null;
			return json_encode(array('rpcStatus' => 1, 'data' => $info));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function updatePage($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody(); 

        $page = array();
        $page['id'] = (isset($postData['id'])) ? $postData['id'] : null;
        $page['level'] = (isset($postData['level'])) ? $postData['level'] : null;
        //$page['page'] = (isset($postData['page'])) ? $postData['page'] : null;
        $page['lastModified'] = $now->format('Y-m-d H:i:s');

		$sql = "UPDATE pageAccess SET level = :level, lastModified = :lastModified WHERE id = :pid";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindvalue(":pid", $page['id'], PDO::PARAM_INT);
			$stmt->bindvalue(":level", $page['level']);
			$stmt->bindvalue(":lastModified", $page['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	


}
?>