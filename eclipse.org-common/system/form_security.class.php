<?php
/*
 * Name: form_security.class.php
 * Function: contains routines created to stop people from using 'bots' to auto complete the forms with spam info.
 * I/O: functions take various parmaters, Some return values.
 * By: M. Ward
 * 
 */
 
class FormSecurity {

  protected $Crypted;
 
  /*******************************************
  * name: getStoredCrypt
  * function: returns a (presumably) encrypted value from the class variable $Crypted
  * I/O: returns the contents of $Crypted 
  * Date: 04/24/08
  * By: M. Ward
  *******************************************/
  function getStoredCrypt() {
    return $this->Crypted;
  }
  /*******************************************
  * name: setStoredCrypt
  * function: stores a (presumably) encrypted value in the class variable $Crypted
  * I/O: stores the passed value in $Crypted 
  * Date: 04/24/08
  * By: M. Ward
  *******************************************/
  function setStoredCrypt($_Crypted) {
    $this->Crypted = $_Crypted;
  }
 
  /**************************************
  * 
  * Name: HardSecureQuestion
  * Function: This function generates a relatively hard(2 operations and 3 values) random mathematical question and prints it to the bottom 
  * of the calling page.
  * I/O: takes the following inputs:
  *		random salt string(will error if not provided), limit on the random values(1..X) defaults to 100
  *	      produces the following output
  *		the text for the question, or an error if no salt was given
  * hidden input type.
  * Date: Nov 21/05
  * Updated: Apr 23/08
  * By: M. Ward
  * 
  ***************************************/
  function HardSecureQuestion( $salt = "", $limit = 100 ) {
    //get the  3 values and 2 functions
    $Security_Values = array(mt_rand(1,$limit), mt_rand(1,$limit), mt_rand(1,$limit), mt_rand(0,1),mt_rand(0,1) );
    //sort out the operators
    $Operators = array('+','-');
    
    //check to see if salt is blank
    if( $salt == "" ) {
      print("<p>Error in SecureQuestion:  You didn't specify a salt value to encrypt with.</p>");
      return;
    }
              
    //compute the result the hard way.
    if ( $Security_Values[3] == 0)
      $Result = $Security_Values[0] + $Security_Values[1];
    else
      $Result = $Security_Values[0] - $Security_Values[1];
    if ( $Security_Values[4] == 0)
      $Result = $Result + $Security_Values[2];
    else
      $Result = $Result - $Security_Values[2];
   
    $this->setStoredCrypt($this->Crypt($Result,$salt) );
    
    //return the computation string
    return ($Security_Values[0]." ".$Operators[$Security_Values[3]]." ".$Security_Values[1]." ".$Operators[$Security_Values[4]]." ".$Security_Values[2]);
	
  }

  /**************************************
  * 
  * Name: EasySecureQuestion
  * Function: This function generates a relatively easy(1 operation and 2 values) random mathematical question and prints it to the bottom 
  * of the calling page.
  * I/O: takes the following inputs:
  *		random salt string(will error if not provided), limit on the random values(1..X) defaults to 10
  *	      produces the following output
  *		the text for the question, or an error if no salt was given
  * hidden input type.
  * Date: Apr 24/08
  * By: M. Ward
  * 
  ***************************************/
  function EasySecureQuestion( $salt = "", $limit = 10 ) {
    //get the  2 values and 1 function
    $Security_Values = array(mt_rand(1,$limit), mt_rand(1,$limit), mt_rand(0,1) );
    //sort out the operators
    $Operators = array('+','-');
    
    //check to see if salt is blank
    if( $salt == "" ) {
      print("<p>Error in SecureQuestion:  You didn't specify a salt value to encrypt with.</p>");
      return;
    }
                  
    //compute the result .
    if ( $Security_Values[2] == 0)
      $Result = $Security_Values[0] + $Security_Values[1];
    else
      $Result = $Security_Values[0] - $Security_Values[1];
    
    $this->setStoredCrypt($this->Crypt($Result,$salt) );
        
    //return the computation string
    return ($Security_Values[0]." ".$Operators[$Security_Values[2]]." ".$Security_Values[1]);
	
  }


  /*********************************************
  *
  * Name: Crypt
  * Function:  encrypts the passed key and returns the binary safe result
  * I/O: Takes the value to be encrypted, and it's salt and returns the encrypted data
  * Date: Nov 23/05
  * Updated: Apr 24/08
  * By: M. Ward
  * 
  * ********************************************/
  function Crypt( $key,$salt ) {
    if( $salt == "" ) {
      print("<p>Error in Crypt:  You didn't specify a password to encrypt with.</p>");
      return -1;
    }
    if( !isset($key) ) {
      print("<p>Error in Crypt:  You didn't specify a value to encrypt.</p>");
      return -1;
    }  
    $crypt = crypt($key,$salt);   
    //now serialise the data
    $crypt = serialize($crypt);
    //binary safe encode
    $crypt = base64_encode($crypt);    
    //return value
    return $crypt;
  } 

  /*********************************************
  *
  * Name: DeCrypt
  * Function: de-packs the binary safe result of the Cryp function above into the plain crypto text
  * I/O: Takes the value to be unpacked
  * Date: Nov 23/05
  * Updated: Apr 24/08
  * By: M. Ward
  * 
  *********************************************/
  function DeCrypt ( $crypto ) {
    if( $crypto == "" ) {
      print("<p>Error in DeCrypt:  You didn't specify a value to decrypt.</p>");
      return -1;
    }
    //decode
    $crypt = base64_decode($crypto);
    //unserialize
    $crypt = unserialize($crypt);
    return $crypt;
  }

  /********************************************
  * Name: Verify
  * Function: given a key, a salt and the results from the Crypt function above, it determines if the encrypted 
  * result of key and salt equals the unpacked crypto value. 
  * I/O: Takes the key and it's encryption salt(which must match what was used to generate $crypto), and the binary safe results of the Crypt function.
  * Returns 1 if they are equal, 0 otherwise.
  * Date: Apr 24/08
  * By: M. Ward
  *******************************************/
  function Verify( $key, $salt, $crypto ) {
    //check to see if salt is blank
    if( $salt == "" ) {
      print("<p>Error in Verify:  You didn't specify a password to encrypt with.</p>");
      return -1;
    }
    if( !isset($key) ) {
      print("<p>Error in Verify:  You didn't specify a value to encrypt.</p>");
      return -1;
    }
    if( $crypto == "" ) {
      print("<p>Error in Verify:  You didn't specify a crypto value to compare.</p>");
      return -1;
    }
    
    $crypt = $this->DeCrypt( $crypto );  
    if( crypt( $key, $salt ) == $crypt ) return 1; else return 0;
  }
}
?>
