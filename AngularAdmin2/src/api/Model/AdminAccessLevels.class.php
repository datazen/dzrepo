<?php
class AdminAccessLevels {

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

}
?>