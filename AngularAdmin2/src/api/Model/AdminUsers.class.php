<?php
class AdminUsers {

    public static function getAllAdminUsers($request) {
		$postData = $request->getParsedBody();
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

	    $sql = "SELECT * FROM users WHERE cID = :cID ORDER BY id";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
	        $stmt->execute(); 
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
            // add accessLevel title
			foreach ($result as $key => $value) {
				$result[$key]['accessTitle'] = AdminAccessLevels::getAdminAccessLevelTitle($cID, $value['accessLevel']);
			}
		    $db = null;
        	return json_encode(array('rpcStatus' => 1, 'data' => $result));
	    } catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
	    }
	}

	public static function getAdminUserById($request) {
		$postData = $request->getParsedBody();
        $uid = (isset($postData['id'])) ? $postData['id'] : 0;
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

		$sql = "SELECT * FROM users WHERE id = :uid AND cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);    	
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
            // move password to encrpted   
            $result['encrypted'] = $result['password'];
            // reset password for forms
            $result['password'] = '';
            // add accessLevel title
    		$result['accessTitle'] = AdminAccessLevels::getAdminAccessLevelTitle($cID, $result['accessLevel']);
			$db = null;
        	return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function getAdminUserByEmail($request) {
		$postData = $request->getParsedBody();
        $email = (isset($postData['email'])) ? $postData['email'] : '';
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;

		$sql = "SELECT * FROM users WHERE email = :email AND cID = :cID";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindParam(":email", $email);
	    	$stmt->bindParam(":cID", $cID);
	        $stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
            // move password to encrpted   
            $result['encrypted'] = $result['password'];
            // reset password for forms
            $result['password'] = '';
            // add accessLevel title
    		$result['accessTitle'] = AdminAccessLevels::getAdminAccessLevelTitle($cID, $result['accessLevel']);
			$db = null;
        	return json_encode(array('rpcStatus' => 1, 'data' => $result));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function addAdminUser($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();

        $user = array();
        $user['cID'] = (isset($postData['cID'])) ? $postData['cID'] : 0;
        $user['email'] = (isset($postData['data']['email'])) ? $postData['data']['email'] : '';
        $user['password'] = (isset($postData['data']['password'])) ? $postData['data']['password'] : '';
        $user['firstName'] = (isset($postData['data']['firstName'])) ? $postData['data']['firstName'] : '';
        $user['lastName'] = (isset($postData['data']['lastName'])) ? $postData['data']['lastName'] : '';
        $user['lastModified'] = $now->format('Y-m-d H:i:s');
        // sanity check to see if email already exists
        $sql = "SELECT id FROM users WHERE email = :email AND cID = :cID LIMIT 1";
		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindParam(":email", $user['email']);
			$stmt->bindParam(":cID", $user['cID']);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);          
			$db = null;
            $exists = (count($result) > 0) ? true : false;
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}        
        
        if ($exists === false) {
			$sql = "INSERT INTO users (cID, email, password, firstName, lastName, lastModified) 
			        VALUES (:cID, :email, :password, :firstName, :lastName, :lastModified)";
			try {
				$encrypted = self::_encryptPassword($user['password']);
	            // don't send back raw password
	            $user['password'] = $encrypted;				
				$db = Database::getConnection();
				$stmt = $db->prepare($sql);  
				$stmt->bindParam("cID", $user['cID']);
				$stmt->bindParam("email", $user['email']);
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
			// email already exists
			return json_encode(array('rpcStatus' => 0, 'msg' => 'E-mail already exists.'));
		}
	}

	public static function updateAdminUserAvatar($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();  
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;
        $uid = (isset($postData['id'])) ? $postData['id'] : 0;

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

			$sql = "UPDATE users SET avatar = :avatar, lastModified = :lastModified WHERE id = :uid AND cID = :cID";
			try {
				$db = Database::getConnection();
				$stmt = $db->prepare($sql);  
		    	$stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
		    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
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

	public static function updateAdminUser($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();  
        $user = array();
        $user['cID'] = (isset($postData['cID'])) ? $postData['cID'] : 0;
        $user['uid'] = (isset($postData['id'])) ? $postData['id'] : 0;
        $user['email'] = (isset($postData['email'])) ? $postData['email'] : '';
        $user['password'] = (isset($postData['password'])) ? $postData['password'] : '';
        $user['firstName'] = (isset($postData['firstName'])) ? $postData['firstName'] : '';
        $user['lastName'] = (isset($postData['lastName'])) ? $postData['lastName'] : '';
        $user['accessLevel'] = (isset($postData['accessLevel'])) ? $postData['accessLevel'] : 0;
        $user['avatar'] = (isset($filename)) ? $filename : 'na.png';
        $user['lastModified'] = $now->format('Y-m-d H:i:s');

        $savePassword = (trim($user['password']) != null || trim($user['password']) != '') ? true : false;
        if ($savePassword) {
		    $encrypted = self::_encryptPassword($user['password']);
   		    $sql = "UPDATE users SET password = :password, firstName = :firstName, lastName = :lastName, email = :email, accessLevel = :accessLevel, lastModified = :lastModified WHERE id = :uid AND cID = :cID";
   		} else {    
   		    $sql = "UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email, accessLevel = :accessLevel, lastModified = :lastModified WHERE id = :uid AND cID = :cID";
        }

		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":uid", $user['uid'], PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $user['cID'], PDO::PARAM_INT); 
			if ($savePassword) $stmt->bindvalue(":password", $encrypted);
			$stmt->bindvalue(":firstName", $user['firstName']);
			$stmt->bindvalue(":lastName", $user['lastName']);
    		$stmt->bindvalue(":email", $user['email']);
			$stmt->bindvalue(":accessLevel", $user['accessLevel']);
			$stmt->bindvalue(":lastModified", $user['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

	public static function deleteAdminUser($request) {
        $postData = $request->getParsedBody();  
        $cID = (isset($postData['cID'])) ? $postData['cID'] : 0;
        $uid = (isset($postData['id'])) ? $postData['id'] : '';	

	    $sql = "DELETE FROM users WHERE id = :uid AND cID = :cID";
	    try {
		    $db = Database::getConnection();
			$stmt = $db->prepare($sql);  
	    	$stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
	    	$stmt->bindValue(":cID", $cID, PDO::PARAM_INT);
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