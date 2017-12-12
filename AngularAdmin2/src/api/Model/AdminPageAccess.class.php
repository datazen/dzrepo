<?php
class AdminPageAccess {

    public static function getAllAdminPageAccess($request) {
        $postData = $request->getParsedBody(); 
        $states = (isset($postData['states'])) ? $postData['states'] : array();
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;      

        self::_syncRoutes($states);

	    $sql = "SELECT * FROM pageAccess WHERE cID = :cID ORDER BY id";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute(); 		    
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);       
            // add accessLevel title
			foreach ($result as $key => $value) {
				$result[$key]['accessTitle'] = AdminAccessLevels::getAdminAccessLevelTitle($cID, $value['level']);
			}	        
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));	    
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}	

    public static function getAdminPageAccessByAccessLevel($request) {
		$postData = $request->getParsedBody();
        $level = (isset($postData['level'])) ? $postData['level'] : '';
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

	    $sql = "SELECT page FROM pageAccess WHERE level <= :level AND cID = :cID ORDER BY id";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	    	$stmt->bindValue(":level", $level);
	        $stmt->execute(); 
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 		    
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));	    
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}

	public static function getAdminPageAccessById($request) {
		$postData = $request->getParsedBody();		
        $paid = (isset($postData['id'])) ? $postData['id'] : '';
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

		$sql = "SELECT * FROM pageAccess WHERE id = :paid AND cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":paid", $paid, PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}

	public static function getAdminPageAccessByRoute($request) {
		$postData = $request->getParsedBody();;		
        $route = (isset($postData['route'])) ? $postData['route'] : '';
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

		$new = '';
		if(strpos($route, '/') !== false) {
			$rArr = explode("/", $route);
			$route = end($rArr);
		}
    
		$sql = "SELECT * FROM pageAccess WHERE page = :route AND cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);		
	    	$stmt->bindValue(":route", $route);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);		
			$db = null;
			return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function getPageAccessByConfigRoute($request, $route) {

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

	public static function updateAdminPageAccess($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody(); 

        $page = array();
        $page['id'] = (isset($postData['id'])) ? $postData['id'] : null;
        $page['cID'] = (isset($postData['cID'])) ? $postData['cID'] : null;
        $page['level'] = (isset($postData['level'])) ? $postData['level'] : null;
        //$page['page'] = (isset($postData['page'])) ? $postData['page'] : null;
        $page['lastModified'] = $now->format('Y-m-d H:i:s');

		$sql = "UPDATE pageAccess SET level = :level, lastModified = :lastModified WHERE id = :pid AND cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindvalue(":pid", $page['id'], PDO::PARAM_INT);
			$stmt->bindvalue(":cID", $page['cID'], PDO::PARAM_INT);
			$stmt->bindvalue(":level", $page['level']);
			$stmt->bindvalue(":lastModified", $page['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	private static function _syncRoutes($states) {

        $routeArr = array();
        $prev = '';

		foreach($states as $key => $data) {
			if (isset($data['site']) && $data['site'] == 'Admin') {
	            $route = (isset($data['url'])) ? $data['url'] : '';
				$route = str_replace("/Admin/", "", $route);
				$route = str_replace("/", "", $route);
				if (strpos($route, ":")) $route = substr($route, 0, strpos($route, ":"));
				if ($route == 'null' || trim($route) == '' || $route == $prev) continue;
				$routeArr[] = $route;
				$prev = $route;
		   }
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

	


}
?>