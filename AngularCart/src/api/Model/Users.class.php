<?php
class Users {

    public static function getAll() {
	    $sql = "select * FROM users ORDER BY id";
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

	public static function getUserById($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $uid = end($uriArr);

		$sql = "select * FROM users WHERE id = :uid";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
            // move password to encrpted   
            $result['encrypted'] = $result['password'];
            // reset password for forms
            $result['password'] = '';
			$db = null;
        	return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function getUserByUsername($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $username = end($uriArr);

		$sql = "select u.*, al.title as accessTitle FROM users u LEFT JOIN accessLevels al ON (u.accessLevel = al.level) WHERE u.username = :username";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindParam(":username", $username);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
            // move password to encrpted   
            $result['encrypted'] = $result['password'];
            // reset password for forms
            $result['password'] = '';			
			$db = null;
        	return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function processLogin($request) {

        $postData = $request->getParsedBody();
        $username = (isset($postData['username'])) ? $postData['username'] : null;
        $rawPassword = (isset($postData['password'])) ? $postData['password'] : null;

		$sql = "select u.*, al.title as accessTitle FROM users u LEFT JOIN accessLevels al ON (u.accessLevel = al.level) WHERE u.username = :username LIMIT 1";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindParam(":username", $username);
	        $stmt->execute(); 
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);          
			$db = null;

			$info = (isset($result[0])) ? $result[0] : array();
            if (isset($info['username']) && isset($info['password'])) {
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

	public static function addUser($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();
        $user = array();
        $user['username'] = (isset($postData['username'])) ? $postData['username'] : '';
        $user['password'] = (isset($postData['password'])) ? $postData['password'] : '';
        $user['firstName'] = (isset($postData['firstName'])) ? $postData['firstName'] : '';
        $user['lastName'] = (isset($postData['lastName'])) ? $postData['lastName'] : '';
        $user['lastModified'] = $now->format('Y-m-d H:i:s');

        // sanity check to see if username already exists
        $sql = "SELECT id FROM users WHERE username = :username LIMIT 1";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindParam("username", $user['username']);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);          
			$db = null;
            $exists = (count($result) > 0) ? true : false;
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}        
        
        if ($exists === false) {
			$sql = "INSERT INTO users (username, password, firstName, lastName, lastModified) VALUES (:username, :password, :firstName, :lastName, :lastModified)";
			try {
				$encrypted = self::_encryptPassword($user['password']);
				$db = Database::getConnection();
				$stmt = $db->prepare($sql);  
				$stmt->bindParam("username", $user['username']);
				$stmt->bindParam("password", $encrypted);
				$stmt->bindParam("firstName", $user['firstName']);
				$stmt->bindParam("lastName", $user['lastName']);
				$stmt->bindParam("lastModified", $user['lastModified']);
				$stmt->execute();
				$user['id'] = $db->lastInsertId();
				$db = null;
				return json_encode(array('rpcStatus' => 1, 'data' => $user));
			} catch(PDOException $e) {
				return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
			}
		} else {
			// username already exists
			return json_encode(array('rpcStatus' => 0, 'msg' => 'Username already exists.'));
		}
	}

	public static function updateAvatar($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $uid = end($uriArr);

	    $directory = substr(__DIR__, 0, strpos(__DIR__, 'api/')) . 'media';
	    $uploadedFiles = $request->getUploadedFiles();

	    // handle single input with single file upload
	    $uploadedFile = $uploadedFiles['file'];
	       
	    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
	        $filename = $uploadedFile->getClientFilename();
		    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
		    $allowedExtensions = array("jpg", "jpeg", "gif", "png");

	        if (!in_array($extension, $allowedExtensions)) {
				return json_encode(array('rpcStatus' => 0, 'msg' => 'Incorrect file type for avatar.  Must be .jpg, .jpeg, .gif or .png'));
	        } 

		    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
		    // resize to 128x128 and move to /img folder
		    self::_resizeAvatar($filename);

	        $now = new DateTime();
	        $user = array();
	        $user['avatar'] = (isset($filename)) ? $filename : 'na.png';
	        $user['lastModified'] = $now->format('Y-m-d H:i:s');

			$sql = "UPDATE users SET avatar = :avatar, lastModified = :lastModified WHERE id = :uid";
			try {
				$db = Database::getConnection();
				$stmt = $db->prepare($sql);  
		    	$stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
				$stmt->bindvalue(":avatar", $user['avatar']);
				$stmt->bindvalue(":lastModified", $user['lastModified']);
				$stmt->execute();
				$db = null;
				return json_encode(array('rpcStatus' => 1));
			} catch(PDOException $e) {
				return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
			}		    
	    }
	}

	public static function updateUser($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();  
        $user = array();
        $user['username'] = (isset($postData['username'])) ? $postData['username'] : '';

        $user['password'] = (isset($postData['password'])) ? $postData['password'] : '';
        $savePassword = (trim($user['password']) != null || trim($user['password']) != '') ? true : false;

        $user['firstName'] = (isset($postData['firstName'])) ? $postData['firstName'] : '';
        $user['lastName'] = (isset($postData['lastName'])) ? $postData['lastName'] : '';
        $user['accessLevel'] = (isset($postData['accessLevel'])) ? $postData['accessLevel'] : 0;
        $user['avatar'] = (isset($filename)) ? $filename : 'na.png';
        $user['lastModified'] = $now->format('Y-m-d H:i:s');

        if ($savePassword) {
		    $encrypted = self::_encryptPassword($user['password']);
   		    $sql = "UPDATE users SET password = :password, firstName = :firstName, lastName = :lastName, accessLevel = :accessLevel, lastModified = :lastModified WHERE username = :username";
   		} else {    
   		    $sql = "UPDATE users SET firstName = :firstName, lastName = :lastName, accessLevel = :accessLevel, lastModified = :lastModified WHERE username = :username";
        }

		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindvalue(":username", $user['username']);
			if ($savePassword) $stmt->bindvalue(":password", $encrypted);
			$stmt->bindvalue(":firstName", $user['firstName']);
			$stmt->bindvalue(":lastName", $user['lastName']);
			$stmt->bindvalue(":accessLevel", $user['accessLevel']);
			$stmt->bindvalue(":lastModified", $user['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function deleteUser($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $uid = end($uriArr);		

	    $sql = "DELETE FROM users WHERE id = :uid";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
			$stmt->execute();  
		    $db = null;
		    return json_encode(array('rpcStatus' => 1));
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

	private static function _resizeAvatar($file_name) {
        $maxDim = 128;
        $sourceFile = substr(__DIR__, 0, strpos(__DIR__, 'api/')) . 'media/' . $file_name;
        $targetFile = substr(__DIR__, 0, strpos(__DIR__, 'api/')) . 'assets/img/Admin/' . $file_name;

        //$file_name = $_FILES['myFile']['tmp_name'];
        list($width, $height, $type, $attr) = getimagesize( $sourceFile );
        if ( $width > $maxDim || $height > $maxDim ) {
            $target_filename = $sourceFile;
            $ratio = $width/$height;
            if( $ratio > 1) {
                $new_width = $maxDim;
                $new_height = $maxDim/$ratio;
            } else {
                $new_width = $maxDim*$ratio;
                $new_height = $maxDim;
            }
            $src = imagecreatefromstring( file_get_contents( $sourceFile ) );
            $dst = imagecreatetruecolor( $new_width, $new_height );
            imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
            imagedestroy( $src );
            imagepng( $dst, $target_filename ); // adjust format as needed
            imagedestroy( $dst );
        }		

        copy($sourceFile, $targetFile);
	}

}
?>