<?php
class AdminAccessLevels {

    public static function getAllAdminAccessLevels($request) {
        $postData = $request->getParsedBody(); 
        $cID = (isset($postData['cID'])) ? $postData['cID'] : null;        

	    $sql = "select * FROM accessLevels WHERE cID = :cID ORDER BY level";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute();  
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}	

	public static function getAdminAccessLevelById($request) {
        $postData = $request->getParsedBody(); 
        $cID = (isset($postData['cID'])) ? $postData['cID'] : null;        
        $alId = (isset($postData['id'])) ? $postData['id'] : null;        

		$sql = "select * FROM accessLevels WHERE id = :alId AND cID = :cID LIMIT 1";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":alId", $alId, PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC); 			
			$db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}

	public static function updateAdminAccessLevel($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();        
      
        $access = array();
        $access['cID'] = (isset($postData['cID'])) ? $postData['cID'] : 0;
        $access['aid'] = (isset($postData['id'])) ? $postData['id'] : null;
        $access['title'] = (isset($postData['title'])) ? $postData['title'] : null;
        $access['lastModified'] = $now->format('Y-m-d H:i:s');

		$sql = "UPDATE accessLevels SET title = :title, lastModified = :lastModified WHERE id = :aid AND cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":aid", $access['aid'], PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $access['cID'], PDO::PARAM_INT);			
			$stmt->bindvalue(":title", $access['title']);
			$stmt->bindvalue(":lastModified", $access['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}

	public static function getAdminAccessLevelTitle($cID, $level) {
	    $sql = "SELECT title as accessTitle FROM accessLevels WHERE cID = :cID AND level = :level";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	    	$stmt->bindValue(":level", $level);
	        $stmt->execute(); 
	        $result = $stmt->fetch(PDO::FETCH_ASSOC);	
		    $db = null;
        	return $result['accessTitle'];
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }		
	}

}
?>