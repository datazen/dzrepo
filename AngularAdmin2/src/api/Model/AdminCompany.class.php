<?php
class AdminCompany {

	public static function getCompanyById($request) {
	    $uri = $request->getUri();
	    $uriArr = explode("/", $uri);
	    $cid = end($uriArr);

		$sql = "select * FROM companies WHERE id = :cid";
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

	public static function updateCompany($request) {
        $now = new DateTime();
        $postData = $request->getParsedBody();        
        $company = array();
        $company['id'] = (isset($postData['id'])) ? $postData['id'] : 0;
        $company['legalName'] = (isset($postData['legalName'])) ? $postData['legalName'] : '';
        $company['tradeName'] = (isset($postData['tradeName'])) ? $postData['tradeName'] : '';
        $company['address1'] = (isset($postData['address1'])) ? $postData['address1'] : '';
        $company['address2'] = (isset($postData['address2'])) ? $postData['address2'] : '';
        $company['city'] = (isset($postData['city'])) ? $postData['city'] : '';
        $company['state'] =(isset($postData['state'])) ? $postData['state'] : '';
        $company['zip'] = (isset($postData['zip'])) ? $postData['zip'] : '';
        $company['www'] = (isset($postData['www'])) ? $postData['www'] : '';
        $company['contactName'] = (isset($postData['contactName'])) ? $postData['contactName'] : '';
        $company['contactPhone'] = (isset($postData['contactPhone'])) ? $postData['contactPhone'] : '';
        $company['contactFax'] = (isset($postData['contactFax'])) ? $postData['contactFax'] : '';
        $company['contactEmail'] = (isset($postData['contactEmail'])) ? $postData['contactEmail'] : '';
        $company['lastModified'] = $now->format('Y-m-d H:i:s');

      
		$sql = "UPDATE companies SET legalName = :legalName, tradeName = :tradeName, address1 = :address1, address2 = :address2, 
		                             city = :city, state = :state, zip = :zip, contactName = :contactName, contactPhone = :contactPhone, 
		                             contactFax = :contactFax, contactEmail = :contactEmail,
		                             lastModified = :lastModified WHERE id = :id";

		try {
			$db = Database::getConnection();
			$stmt = $db->prepare($sql);  
			$stmt->bindvalue(":id", $company['id']);
			$stmt->bindvalue(":legalName", $company['legalName']);
			$stmt->bindvalue(":tradeName", $company['tradeName']);
			$stmt->bindvalue(":address1", $company['address1']);
			$stmt->bindvalue(":address2", $company['address2']);
			$stmt->bindvalue(":city", $company['city']);
			$stmt->bindvalue(":state", $company['state']);
			$stmt->bindvalue(":zip", $company['zip']);
			$stmt->bindvalue(":contactName", $company['contactName']);
			$stmt->bindvalue(":contactPhone", $company['contactPhone']);
			$stmt->bindvalue(":contactFax", $company['contactFax']);
			$stmt->bindvalue(":contactEmail", $company['contactEmail']);
			$stmt->bindvalue(":lastModified", $company['lastModified']);
			$stmt->execute();
			$db = null;
			return json_encode(array('rpcStatus' => 1));
		} catch(PDOException $e) {
			return json_encode(array('rpcStatus' => 0, 'msg' => $e->getMessage()));
		}
	}	

}
?>