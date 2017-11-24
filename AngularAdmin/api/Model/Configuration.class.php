<?php
class Configuration {

    public static function getAllConfigurations() {
	    $sql = "select * FROM configuration ORDER BY id";
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

	private static function _loadAllConfigurationValues() {

	}

	public static function getConfigurationById($request) {
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

	public static function updateConfiguration($request) {
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

    public static function getConfigurationGroups() {
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