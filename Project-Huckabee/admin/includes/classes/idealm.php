<?php
/*
  $Id: idealm.php,v 1.1 2005/11/13 22:50:52 jb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com 

  Released under the GNU General Public License
*/

  class ThinMPI {

    /**
    * creates a new ThinMPI core Object
    *
    */
    function ThinMPI () {
      $this->security = new Security();
    }

    //var to do all the security stuff
    var $security;

    //holds the data of the configuration file
    var $conf;

    /**
    * This method sends HTTP XML DirectoryRequest to the Acquirer system.
    * Befor calling, all mandatory properties have to be set in the Request object
    * by calling the associated getter methods.
    * If the request was successful, the response Object is returned.
    * @param Request Object filled with necessary data for the XML Request
    * @return Response Object with the data of the XML response.
    */

    /**
    * This method logs the message given to a file.
    */
    function log($message) {
      if (MODULE_PAYMENT_IDEALM_LOGGING == 'False') return;
      $file = fopen(MODULE_PAYMENT_IDEALM_LOGFILE, 'a');
      fputs($file, "--------------------Start of message\r\n", 38);
      fputs($file, $message, strlen($message));
      fputs($file, "--------------------End of message\r\n\r\n", 38);
      fclose($file);
    }

    function parseFromXml ($key, $xml) {
      $begin = 0;
      $end = 0;

      $begin = strpos($xml, "<" . $key . ">");
      if ($begin===false) {
        return false;
      }

      $begin += strlen($key) + 2;
      $end = strpos($xml, "</" . $key . ">");

      if ($end===false) {
        return false;
      }

      $end = $end - $begin;
      $result = substr($xml, $begin, $end);

      return $result;
    }

    /**
    * This method sends HTTP XML AcquirerStatusRequest to the Acquirer system.
    * Befor calling, all mandatory properties have to be set in the Request object
    * by calling the associated getter methods.
    * If the request was successful, the response Object is returned.
    * @param Request Object filled with necessary data for the XML Request
    * @return Response Object with the data of the XML response.
    */
    function processStatRequest($req) {

      $res = new AcquirerStatusResponse();
      $res->setAuthenticated(false);
      $res->setOk(false);
      /*
      if (!$req->checkMandatory()) {
        $res->setErrorMessage("Required fields missing.");
        return $res;
      }*/

      if ("SHA1_RSA" != $req->getAuthentication()) {
        $res->setErrorMessage("Unknown or not implemented authentication: " . $req->getAuthentication());
        return $res;
      }
      $timestamp = date("Y-m-d\TH:i:s.000\Z", time());

      // build concatenated string
      $message = $timestamp . $req->getMerchantID() . $req->getSubID() . $req->getTransactionID();
      $message = $this->strip_message($message);

      //build fingerprint of your own certificate
      $token = $this->security->createCertFingerprint(MODULE_PAYMENT_IDEALM_OWN_CERT);
      $tokenCode64 = "";

      //sign the part of the message that need to be signed
      $tokenCode64 = $this->security->signMessage(MODULE_PAYMENT_IDEALM_PRIVATE_KEY, MODULE_PAYMENT_IDEALM_PRIVATE_PASSWORD, $message);

      //encode with base64
      $tokenCode64 = base64_encode($tokenCode64);

      $reqMsg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . 
                "<AcquirerStatusReq xmlns=\"http://www.idealdesk.com/Message\" version=\"1.1.0\">" . 
                "  <createDateTimeStamp>" . $timestamp . "</createDateTimeStamp>" . 
                "  <Merchant>" . 
                "    <merchantID>" . $req->getMerchantID() . "</merchantID>" . 
                "    <subID>" . $req->getSubID() . "</subID>" . 
                "    <authentication>" . $req->getAuthentication() . "</authentication>" . 
                "    <token>" . $token . "</token>" . 
                "    <tokenCode>" . $tokenCode64 . "</tokenCode>" . 
                "  </Merchant>" . 
                "  <Transaction>" . 
                "    <transactionID>" . $req->getTransactionID() . "</transactionID>" . 
                "  </Transaction>" . 
                "</AcquirerStatusReq>";


      switch (MODULE_PAYMENT_IDEALM_OWN_BANK) {
        case 'ING/POSTBANK' :
          $sAnswer = $this->PostToHost(MODULE_PAYMENT_IDEALM_ING_IP, MODULE_PAYMENT_IDEALM_ING_PORT, MODULE_PAYMENT_IDEALM_ING_PATH, $reqMsg);
          break;
        case 'RABO' :
          $sAnswer = $this->PostToHost(MODULE_PAYMENT_IDEALM_RABO_IP, MODULE_PAYMENT_IDEALM_RABO_PORT, MODULE_PAYMENT_IDEALM_RABO_PATH, $reqMsg);
          break;
        case 'ABN/AMRO' :
          $sAnswer = $this->PostToHost(MODULE_PAYMENT_IDEALM_ABN_IP, MODULE_PAYMENT_IDEALM_ABN_PORT, MODULE_PAYMENT_IDEALM_ABN_STATUS_PATH . MODULE_PAYMENT_IDEALM_ABN_STATUS, $reqMsg);
          break;
      }

      if ($this->parseFromXml("errorCode", $sAnswer)) {
        $errorMsg = $this->parseFromXml("errorMessage", $sAnswer);
        $errorCode = $this->parseFromXml("errorCode", $sAnswer);
        $errorDetail = $this->parseFromXml("errorDetail", $sAnswer);
        $sugAction = $this->parseFromXml("suggestedAction", $sAnswer);
        $sugExpPeriod = $this->parseFromXml("suggestedExpirationPeriod", $sAnswer);
        $consMsg = $this->parseFromXml("consumerMessage", $sAnswer);

        $res->setErrorMessage($errorMsg);
        $res->setErrorCode($errorCode);
        $res->setErrorDetail($errorDetail);
        $res->setSuggestedAction($sugAction);
        $res->setSuggestedExpirationPeriod($sugExpPeriod);
        $res->setConsumerMessage($consMsg);

        $res->setOk(false);
        return $res;
      }

      $status = $this->parseFromXml("status", $sAnswer); 

      if ($status == false) {
        $res->setAuthenticated(false);
        $res->setErrorMessage("Sent message is not a status response!");
        return $res;
      }

      $res->setAuthenticated($status);

      $creationTime = $this->ParseFromXml("createDateTimeStamp", $sAnswer);
      $txnId = $this->ParseFromXml("transactionID", $sAnswer);
      $consumerAccountNumber = $this->parseFromXml("consumerAccountNumber", $sAnswer);
      $consumerName = $this->ParseFromXml("consumerName", $sAnswer);
      $consumerCity = $this->ParseFromXml("consumerCity", $sAnswer);
      $transactionID = $this->ParseFromXml("transactionID", $sAnswer);
      $res->setConsumerCity($consumerCity);
      $res->setConsumerName($consumerName);
      $res->setConsumerAccountNumber($consumerAccountNumber);
      $res->setTransactionID($transactionID);

      // now check the signature
      //create signed message string
      $message = $creationTime . $txnId . $status . $consumerAccountNumber;

      //now we want to check the signature that has been sent
      $signature64 = $this->ParseFromXml("signatureValue", $sAnswer);

      //decode the base64 encoded signature
      $sig = base64_decode($signature64);
      //$sig = $signature64;

      //get the fingerprint out of the response
      $fingerprint = $this->ParseFromXml("fingerprint", $sAnswer);

      //search for the certificate file with the given fingerprint
      $certfile = $this->security->getCertificateName($fingerprint, $this->conf);
      /*
      if ($certfile == false) {
        $res->setAuthenticated(false);
        $res->setErrorMessage("Fingerprint unknown!");
        return $res;
      }

      $valid = $this->security->verifyMessage($certfile, $message, $sig);

      if ($valid != 1) {
        $res->setAuthenticated(false);
        $res->setErrorMessage("Bad signature!");
        return $res;
      }
      */
      $res->setOk(true);

      return $res;
    }

    function ProcessRequest($requesttype) {
      if (is_a($requesttype, "DirectoryRequest")) {
        return $this->processDirRequest($requesttype);
      } else if (is_a($requesttype, "AcquirerStatusRequest")) {
        return $this->processStatRequest( $requesttype );
      } else if (is_a($requesttype, "AcquirerTrxRequest")) {
        return $this->processTrxRequest($requesttype);
      }
    }

    function PostToHost($host, $port, $path, $data_to_send) {
      $fsp = fsockopen($host, $port, $errno, $errstr, "60");

      $this->log("sending to " . $host . ":" . $port . $path . ": " . $data_to_send);

      if ($fsp) {
        fputs($fsp, "POST $path HTTP/1.0\r\n");
        if (MODULE_PAYMENT_IDEALM_OWN_BANK == 'ABN/AMRO') fputs($fsp, "Host: " . str_replace("ssl://", "", $host) . "\r\n");
        fputs($fsp, "Accept: text/html\r\n");
        fputs($fsp, "Accept: charset=ISO-8859-1\r\n");
        fputs($fsp, "Content-Length:".strlen($data_to_send)."\r\n");
        fputs($fsp, "Content-Type: text/html; charset=ISO-8859-1\r\n\r\n");
        fputs($fsp, $data_to_send, strlen($data_to_send));

        while (!feof($fsp)) {
          $res .= fgets($fsp, 128);
        }
        fclose($fsp);
        $this->log("receiving from " . $host . ":" . $port . $path . ": " . $res);
      } else {
        $this->log("error from " . $host . ":" . $port . $path . ": " . $errstr);
      }
      return $res;
    }
    function strip_message($message) {
      if (MODULE_PAYMENT_IDEALM_OWN_BANK != 'ABN/AMRO') $message = str_replace(' ' , '', $message);
      $message = str_replace('\t' , '', $message);
      $message = str_replace('\n' , '', $message);
      return($message);
    }
  }
  
  /**
  * This is a base class for all Ideal Requests and should not be     instantiated.
  * It contains some fields that are used by all Requests in iDEAL payment.
  */
  class IdealRequest {
    // fields for request xml message
    var $merchantID = "";
    var $subID = "";
    var $authentication = ""; 

    function clear() {
      $this->merchantID = "";
      $this->subID = "";
      $this->authentication = "";
    }

    /**
    * this method checks, wheather all mandatory properties were set.
    * If done so, true is returned, otherwise false.
    * @return If done so, true is returned, otherwise false.
    */
    function checkMandatory () {
      if (!empty($this->merchantID) && strlen(trim($this->merchantID)) > 0 /*&& !empty($this->subID) && strlen(trim($this->subID)) > 0 */&& !empty($this->authentication) && strlen(trim($this->authentication)) > 0 && token != null) {
        if(empty($this->subID)) {
          $this->subID = "0";
        }
        return true;
      } else {
        return false;
      }
    }

    /**
    * @return Returns the authentication.
    */
    function getAuthentication() {
      return $this->authentication;
    }
    
    /**
    * @param authentication The type of authentication to set.
    * Currently only "certificate" is implemented. (mandatory)
    */
    function setAuthentication($authentication) {
      $this->authentication = $authentication;
    }
    
    /**
    * @return Returns the merchantID.
    */
    function getMerchantID() {
      return $this->merchantID;
    }
    
    /**
    * @param merchantID The merchantID to set. (mandatory)
    */
    function setMerchantID($merchantID) {
      $this->merchantID = $merchantID;
    }
    
    /**
    * @return Returns the subID.
    */
    function getSubID() {
      return $this->subID;
    }
    
    /**
    * @param subID The subID to set. (mandatory)
    */
    function setSubID($subID) {
      $this->subID = $subID;
    }
  }
  
  class IdealResponse {
    // fields for xml answer
    var $errorMessage = null;
    var $ok = false;
    var $errorCode = "";
    var $errorDetail = "";
    var $suggestedAction = "";
    var $suggestedExpirationPeriod = "";
    var $consumerMessage = "";

    /**
    * @return If an error has ocurred during the previous Request, this method returns a detailed
    * message about what went wrong. isOk() returnes false in that case.
    */
    function getErrorMessage() {
      return $this->errorMessage;
    }
    
    /**
    * sets the error string
    * @param errorMessage The errorMessage to set.
    */
    function setErrorMessage($errorMessage) {
      $this->errorMessage = $errorMessage;
    }

    function setErrorCode($errorCode) {
      $this->errorCode = $errorCode;
    }

    function getErrorCode() {
      return $this->errorCode;
    }

    function setErrorDetail($errorDetail) {
      $this->errorDetail = $errorDetail;
    }

    function getErrorDetail() {
      return $this->errorDetail;
    }

    function setSuggestedAction($suggestedAction) {
      $this->suggestedAction = $suggestedAction;
    }

    function getSuggestedAction() {
      return $this->suggestedAction;
    }

    function setSuggestedExpirationPeriod($suggestedExpirationPeriod) {
      $this->suggestedExpirationPeriod = $suggestedExpirationPeriod;
    }

    function getSuggestedExpirationPeriod() {
      return $this->suggestedExpirationPeriod;
    }

    function setConsumerMessage($consumerMessage) {
      $this->consumerMessage = $consumerMessage;
    }

    function getConsumerMessage() {
      return $this->consumerMessage;
    }

    /**
    * @return true, if the request was processed successfully, otherwise false. If
    * false, additional information can be received calling getErrorMessage()
    */
    function isOk() {
      return $this->ok;
    }
    
    /**
    * @param ok sets the OK flag
    */
    function setOk($ok) {
      $this->ok = $ok;
    }
  }
  
  class AcquirerStatusRequest extends IdealRequest{
    var $transactionID;
    var $acqStatusURL;
    
    /**
    * rests all input data to empty strings
    */
    function clear() {
      IdealRequest:: clear();
      $this->transactionID = "";
      $this->acqStatusURL = "";
    }
    /**
    * this method checks, wheather all mandatory properties were set.
    * If done so, true is returned, otherwise false.
    * @return If done so, true is returned, otherwise false.
    */

    function checkMandatory () {
      if (IdealRequest:: checkMandatory() && !empty($this->transactionID) && strlen( trim( $this->transactionID ) ) > 0 && !empty($this->acqStatusURL) && strlen( trim( $this->acqStatusURL ) ) > 0) {
        return true;
      } else {
        return false;
      }
    }

    /**
    * @return Returns the acqStatusURL.
    */
    function getAcqStatusURL() {
      return $this->acqStatusURL;
    }
    
    /**
    * @param acqStatusURL The URL of the acquirer, where the status Request is sent to. (mandatory)
    */
    function setAcqStatusURL($acqStatusURL) {
      $this->acqStatusURL = $acqStatusURL;
    }
    
    /**
    * @return Returns the transactionID.
    */
    function getTransactionID() {
      return $this->transactionID;
    }
    
    /**
    * @param transactionID The transactionID of the corresponding transaction. (mandatory)
    */
    function setTransactionID($transactionID) {
      $this->transactionID = $transactionID;
    }
  }
  
  class AcquirerStatusResponse extends IdealResponse {
    var $authenticated = false;
    var $consumerName = "";
    var $consumerAccountNumber = "";
    var $consumerCity = "";
    var $transactionID = "";

    /**
    * @return Returns true, if the transaction was authenticated, otherwise false.
    */
    function isAuthenticated() {
      return $this->authenticated;
    }
    
    /**
    * @param authenticated The authenticated flag to be set.
    */
    function setAuthenticated($authenticated) {
      $this->authenticated = $authenticated;
    }
    
    /**
    * @return Returns the consumerAccountNumber.
    */
    function getConsumerAccountNumber() {
      return $this->consumerAccountNumber;
    }
    
    /**
    * @param consumerAccountNumber The consumerAccountNumber to set.
    */
    function setConsumerAccountNumber($consumerAccountNumber) {
      $this->consumerAccountNumber = $consumerAccountNumber;
    }
    
    /**
    * @return Returns the consumerCity.
    */
    function getConsumerCity() {
      return $this->consumerCity;
    }
    
    /**
    * @param consumerCity The consumerCity to set.
    */
    function setConsumerCity($consumerCity) {
      $this->consumerCity = $consumerCity;
    }
    
    /**
    * @return Returns the consumerName.
    */
    function getConsumerName() {
      return $this->consumerName;
    }
    
    /**
    * @param consumerName The consumerName to set.
    */
    function setConsumerName($consumerName) {
      $this->consumerName = $consumerName;
    }
    
    /**
    * @return Returns the transactionID.
    */
    function getTransactionID() {
      return $this->transactionID;
    }
    
    /**
    * @param transactionID The transactionID to set.
    */
    function setTransactionID($transactionID) {
      $this->transactionID = $transactionID;
    }
  }
  
  class IssuerBean {
    var $issuerID = "";
    var $issuerName = "";
    var $issuerList = "";

    /*
    function IssuerBean($id, $name, $list) {
      $this->issuerID = $id;
      $this->issuerName = $name;
      $this->issuerList = $list;
    }
    */

    /**
    * @return Returns the issuerID.
    */
    function getIssuerID() {
      return $this->issuerID;
    }
    
    /**
    * @returns a readable representation of the IssuerBean
    */
    function toString() {
      return "IssuerBean: issuerID=" . $this->issuerID . " issuerName=" . $this->issuerName . " issuerList=" . $this->issuerList;
    }
    
    /**
    * @param issuerID The issuerID to set.
    */
    function setIssuerID($issuerID) {
      $this->issuerID = $issuerID;
    }
    
    /**
    * @return Returns the issuerList. ("Short", "Long")
    */
    function getIssuerList() {
      return $this->issuerList;
    }
    
    /**
    * @param issuerList The issuerList to set.
    */
    function setIssuerList($issuerList) {
      $this->issuerList = $issuerList;
    }
    
    /**
    * @return Returns the issuerName.
    */
    function getIssuerName() {
      return $this->issuerName;
    }
    
    /**
    * @param issuerName The issuerName to set.
    */
    function setIssuerName($issuerName) {
      $this->issuerName = $issuerName;
    }
  }
  
  class Security {
    /**
    *  reads in a certificate file and creates a fingerprint
    *  @param Filename of the certificate
    *  @return fingerprint
    */
    function createCertFingerprint($filename) {
      $fp = fopen('../' . DIR_WS_INCLUDES . MODULE_PAYMENT_IDEALM_CERT_DIR . $filename, "r");

      if (!$fp) {
        return false;
      }
      $cert = fread($fp, 8192);
      fclose($fp);

      $data = openssl_x509_read($cert);

      if (!openssl_x509_export($data, $data)) {
        return false;
      }

      $data = str_replace("-----BEGIN CERTIFICATE-----", "", $data);
      $data = str_replace("-----END CERTIFICATE-----", "", $data);
      $data = base64_decode($data);

      $fingerprint = sha1($data);
      $fingerprint = strtoupper($fingerprint);

      return $fingerprint;
    }

    /**
    * function to sign a message
    * @param filename of the private key
    * @param message to sign
    * @return signature
    */
    function signMessage($priv_keyfile, $key_pass, $data) {
      $fp = fopen( '../' . DIR_WS_INCLUDES  . MODULE_PAYMENT_IDEALM_CERT_DIR . $priv_keyfile , "r");
      $priv_key = fread($fp, 8192);
      fclose($fp);
      $pkeyid = openssl_get_privatekey($priv_key, $key_pass);

      // compute signature
      openssl_sign($data, $signature, $pkeyid);

      // free the key from memory
      openssl_free_key($pkeyid);

      return $signature;

    }

    /**
    * function to verify a message
    * @param filename of the public key to decrypt the signature
    * @param message to verify
    * @param sent signature
    * @return signature
    */
    function verifyMessage($certfile, $data, $signature) {
      // $data and $signature are assumed to contain the data and the     signature
      $ok = 0;
      // fetch public key from certificate and ready it
      $fp = fopen('../' . DIR_WS_INCLUDES . MODULE_PAYMENT_IDEALM_CERT_DIR . $certfile, "r");

      if (!$fp) {
        return false;
      }

      $cert = fread($fp, 8192);
      fclose($fp);
      $pubkeyid = openssl_get_publickey($cert);

      // state whether signature is okay or not
      $ok = openssl_verify($data, $signature, $pubkeyid);

      // free the key from memory
      openssl_free_key($pubkeyid);

      return $ok;
    }

    /**
    * @param fingerprint that´s been sent
    * @param the configuration file loaded in as an array
    * @return the filename of the certificate with this fingerprint
    */
    function getCertificateName($fingerprint, $config) {
      $count = 0;
      $certFilename = explode(',', MODULE_PAYMENT_IDEALM_TRUSTED_CERT);
      while(list($key, $val) = each($certFilename)) {
        $buff = $this->createCertFingerprint($val);

        if ($fingerprint == $buff) {
          return $val;
        }
      }
      return false;
    }
  }
?>