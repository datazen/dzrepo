<?php
class AdminConfiguration {

    public static function getAllAdminConfigurations() {
	    $sql = "select * FROM configuration ORDER BY groupId";
	    try {
		    $db = Database::getConnection();
		    $stmt = $db->query($sql);  
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
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $groupId = end($uriArr);

	    $sql = "SELECT * FROM configuration WHERE groupId = :groupId ORDER BY sortOrder";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":groupId", $groupId, PDO::PARAM_INT);
	        $stmt->execute(); 
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $db = null;
		    return json_encode(array('rpcStatus' => 1, 'data' => $result));
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}	

	public static function getAdminConfigurationById($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $cid = end($uriArr);

		$sql = "select * FROM configuration WHERE id = :cid";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cid", $cid, PDO::PARAM_INT);
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
        $config['id'] = (isset($postData['id'])) ? $postData['id'] : 0;
        $config['title'] = (isset($postData['title'])) ? $postData['title'] : null;
        $config['key'] = (isset($postData['key'])) ? $postData['key'] : null;
        $config['value'] = (isset($postData['value'])) ? $postData['value'] : null;
        $config['description'] = (isset($postData['description'])) ? $postData['description'] : null;
        $config['lastModified'] = $now->format('Y-m-d H:i:s');

		$sql = "UPDATE configuration SET title = :title, value = :value, description = :description, lastModified = :lastModified WHERE id = :cid";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql); 
	    	$stmt->bindValue(":cid", $config['id'], PDO::PARAM_INT);		 
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

    public static function getAdminConfigurationGroups() {
	    $sql = "select * FROM configurationGroups ORDER BY sortOrder";
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
}
?>