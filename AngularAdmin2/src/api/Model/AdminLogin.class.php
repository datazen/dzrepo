<?php
class AdminLogin {

	public static function processAdminLogin($request) {

        $postData = $request->getParsedBody();
        $email = (isset($postData['email'])) ? $postData['email'] : null;
        $rawPassword = (isset($postData['password'])) ? $postData['password'] : null;

		$sql = "select * FROM users WHERE email = :email";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindParam(":email", $email);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);     
            // add accessLevel title
    		$result['accessTitle'] = AdminAccessLevels::getAdminAccessLevelTitle($result['cID'], $result['accessLevel']);
			$db = null;			
			$info = (isset($result)) ? $result : array();
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