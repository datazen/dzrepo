<?php
class AdminLogin {

	public static function processLogin($request) {

        $postData = $request->getParsedBody();
        $email = (isset($postData['email'])) ? $postData['email'] : null;
        $rawPassword = (isset($postData['password'])) ? $postData['password'] : null;

		$sql = "select u.*, al.title as accessTitle FROM users u LEFT JOIN accessLevels al ON (u.accessLevel = al.level) WHERE u.email = :email LIMIT 1";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindParam(":email", $email);
	        $stmt->execute(); 
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);          
			$db = null;
			$info = (isset($result[0])) ? $result[0] : array();
            if (isset($info['email']) && isset($info['password'])) {
			    if (self::_validatePassword($rawPassword, $info['password'])) {
			    	return json_encode(array('rpcStatus' => 1, 'data' => $info));
                   // validation success
			    } else {
                   // validation failed
			        return json_encode(array('rpcStatus' => 0, 'msg' => 'Validation Failed'));
	  			}
    		} else {
    			// user not found
    		    return json_encode(array('rpcStatus' => 0, 'msg' => 'User Not Found'));
    		}

		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}

	private static function _encryptPassword($plain) {
        $password = '';
    
        for ($i=0; $i<10; $i++) {
            $password .= mt_rand();
        }
        $salt = substr(hash('sha256', $password), 0, 2);
        $password = hash('sha256', $salt . $plain) . '::' . $salt;

        return $password;
	}

	private static function _validatePassword($plain, $encrypted) {
		if (!empty($plain) && !empty($encrypted)) {  
	        // split apart the hash / salt
			$stack = explode('::', $encrypted);

			if (sizeof($stack) != 2) {
				return false;
			}

			if (hash('sha256', $stack[1] . $plain) == $stack[0]) {
				return true;
			}      	
		}

        return false;
    }

}
?>