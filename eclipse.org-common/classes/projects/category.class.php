<?php

/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *    Nathan Gervais (Eclipse Foundation) - Expanded new fields being added
 *******************************************************************************/
 
class Category {

	#*****************************************************************************
	#
	# category.class.php
	#
	# Author: 	Denis Roy
	# Date:		2005-10-25
	#
	# Description: Functions and modules related to Category objects (for projects)
	#
	# HISTORY:
	#
	#*****************************************************************************

	var $category_id 		= 0;
	var $description		= "";
	var $image_name			= "";
	var $category_shortname 	= "";
	
	
	
	function getCategoryID() {
		return $this->category_id;
	}
	function getDescription() {
		return $this->description;
	}
	function getImageName() {
		return $this->image_name;
	}
	function getCategoryShortname() {
		return $this->category_shortname;
	}
	
	function setCategoryID($_category_id) {
		$this->category_id = $_category_id;
	}
	function setDescription($_description) {
		$this->description = $_description;
	}
	function setImageName($_image_name) {
		$this->image_name = $_image_name;
	}
	function setCategoryShortname ($_category_shortname) {
		$this->category_shortname = $_category_shortname;
	}

}
?>
