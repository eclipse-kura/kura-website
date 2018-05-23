<?php
/*******************************************************************************
 * Copyright (c) 2010 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *    Nathan Gervais (Eclipse Foundation) - Expanded new fields being added
 *******************************************************************************/

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class MemberImage {

	#*****************************************************************************
	#
	# membershipimage.class.php
	#
	# Author:   M. Ward
	# Date: 06/01/10
	# Based on work by: 	Denis Roy 2005-10-25
	#
	# Description: functions around membership image details
	#
	# HISTORY:
	#
	#*****************************************************************************

	var $DEBUG;
	var $details = array();


	# default constructor
	function __construct() {
		$this->DEBUG = 0;
	    $this->details[0] = -1;
	    $this->details[1] = -1;
	}

	function getsmall_image($member_id) {

	  if ($this->getMetaImage($member_id,"small") == -1 ) {
	    if ($this->DEBUG) {echo "Error retrieving image details for member $_member_id.";}
	  }
	  return $this->details;
	}

	function getlarge_image($member_id) {

	  if ($this->getMetaImage($member_id,"large") == -1 ) {
	    if ($this->DEBUG){echo "Error retrieving image details for member $_member_id.";}
	  }
	  return $this->details;

	}

	function setdebug($val){
	  $this->DEBUG = $val;
	}


	private function getMetaImage($_member_id, $_size) {

		if ( $_member_id <= 0 || !is_numeric($_member_id)){return -1;}


		$image = "";

		$App = new App();

	    $sql = "SELECT ".$_size."_height as height, ".$_size."_width as width , ".$_size."_logo as image from OrganizationInformation where OrganizationID = ".$_member_id." LIMIT 1";
		$sql = $App->sqlSanitize($sql);
    	$result = $App->eclipse_sql($sql);
    	while($myrow = mysql_fetch_array($result))
	    {
        	$this->details[0]=$myrow["width"];
        	$this->details[1]=$myrow["height"];
        	$image=$myrow["image"];
		}
        if ($this->details[0] == 0 && $this->details[1] == 0  ) {
          if ( $image != "" ) {
            $img = imagecreatefromstring($image);
            $this->details[1] = imagesy($img);
            $this->details[0] = imagesx($img);
            imagedestroy($img);
            if ( $this->details[0] == 0 && $this->details[1] == 0 ) {
              //try and get the data the old fashined way
              $image_url = 'http://eclipse.org/membership/scripts/get_image.php?size=small&id=';
	          list ($this->details[0], $this->details[1], $type, $attr) = getimagesize($image_url . $array['memberID']);
	        }
          }
          if ( $this->details[1] == 0 && $this->details[0] == 0 ) {  //error!
            return -1;
          }
          $sql = "UPDATE OrganizationInformation SET ".$_size."_height = ".$this->details[1].",".$_size."_width = ".$this->details[0]." where OrganizationID = ".$_member_id;
  		  //insert data
  		  $sql = $App->sqlSanitize($sql);
  		  $result = $App->eclipse_sql($sql);
        }
		return 1;
	}

}
?>