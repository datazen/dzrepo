<?php
class AdminConfiguration {

    public static function getAllAdminConfigurations($request) {
        $postData = $request->getParsedBody();  
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;      

	    $sql = "SELECT * FROM configuration WHERE cID = :cID ORDER BY groupId";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute(); 		    
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $db = null;

		    $configs = array();
		    if (is_array($result)) {
		    	foreach ($result as $k => $v) {
		    		$configs[$v['key']] = $v['value']; 
		    	}
		    }
		    return json_encode(array('rpcStatus' => 1, 'data' => $configs));
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}	

    public static function getAdminConfigurationsByGroupId($request) {
        $postData = $request->getParsedBody();  
        $groupId = (isset($postData['groupId'])) ? $postData['groupId'] : 0;
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

	    $sql = "SELECT * FROM configuration WHERE groupId = :groupId AND cID = :cID ORDER BY sortOrder";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindValue(":groupId", $groupId, PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute(); 
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}	

	public static function getAdminConfigurationById($request) {
        $postData = $request->getParsedBody(); 
        $configId = (isset($postData['id'])) ? $postData['id'] : 0;
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

		$sql = "SELECT * FROM configuration WHERE id = :configId AND cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindValue(":configId", $configId, PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}

	public static function updateAdminConfiguration($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();        
        $config = array();
        $config['configId'] = (isset($postData['id'])) ? $postData['id'] : 0;
        $config['cID'] = (isset($postData['cID'])) ? $postData['cID'] : 0;
        $config['title'] = (isset($postData['title'])) ? $postData['title'] : null;
        $config['key'] = (isset($postData['key'])) ? $postData['key'] : null;
        $config['value'] = (isset($postData['value'])) ? $postData['value'] : null;
        $config['description'] = (isset($postData['description'])) ? $postData['description'] : null;
        $config['lastModified'] = $now->format('Y-m-d H:i:s');

		$sql = "UPDATE configuration SET title = :title, value = :value, description = :description, lastModified = :lastModified WHERE id = :configId and cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql); 
			$stmt->bindValue(":configId", $config['configId'], PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $config['cID'], PDO::PARAM_INT);	 
			$stmt->bindvalue(":title", $config['title']);
			$stmt->bindvalue(":value", $config['value']);
			$stmt->bindvalue(":description", $config['description']);
			$stmt->bindvalue(":lastModified", $config['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

    public static function getAllAdminConfigurationGroups($request) {
        $postData = $request->getParsedBody();  
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;  

	    $sql = "SELECT * FROM configurationGroups WHERE cID = :cID ORDER BY sortOrder";
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
}
?>