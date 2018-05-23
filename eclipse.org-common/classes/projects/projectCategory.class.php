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

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once("project.class.php");
require_once("category.class.php");

class ProjectCategory {

	#*****************************************************************************
	#
	# projectCategory.class.php
	#
	# Author: 	Denis Roy
	# Date:		2005-10-25
	#
	# Description: Functions and modules related to associations between a project and a category
	#
	# HISTORY:
	#
	#*****************************************************************************

	var $project_id;
	var $category_id;
	var $description;
	var $long_description;
	var $projectObject;
	var $categoryObject;


	# default constructor
	function __construct() {
		$this->project_id 		= "";
		$this->category_id		= "";
		$this->description 		= "";
		$this->long_description = "";
		$this->projectObject	= new Project();
		$this->categoryObject	= new Category();
	}

	function getProjectID() {
		return $this->project_id;
	}
	function getCategoryID() {
		return $this->category_id;
	}
	function getDescription() {
		return $this->description;
	}
	function getLongDescription() {
		return $this->long_description;
	}
	function getProjectObject() {
		return $this->projectObject;
	}
	function getCategoryObject() {
		return $this->categoryObject;
	}


	function setProjectID($_project_id) {
		$this->project_id = $_project_id;
	}
	function setCategoryID($_category_id) {
		$this->category_id = $_category_id;
	}
	function setDescription($_description) {
		$this->description = $_description;
	}
	function setLongDescription($_long_description) {
		$this->long_description = $_long_description;
	}
	function setProjectObject($_Project) {
		$this->projectObject = $_Project;
	}
	function setCategoryObject($_Category) {
		$this->categoryObject = $_Category;
	}


	function deleteProjectCategory($_project_id, $_category_id) {

		$App = new App();

	    if($_project_id != "" && $_category_id != "") {
			$WHERE .= " WHERE project_id = " . $App->returnQuotedString($_project_id);
			$WHERE .= " AND category_id = " . $App->returnQuotedString($_category_id);


		    $sql = "DELETE
	        	FROM
					project_categories "
				. $WHERE;

		    $result = $App->eclipse_sql($sql);

		    $result = null;
		    $myrow	= null;
	    }
	}

	function insertUpdateProjectCategory($_project_id, $_category_id, $_description) {

		$App = new App();

	    if($_project_id != "" && $_category_id != "") {

		    $sql = "INSERT INTO project_categories (
						project_id,
						category_id,
						description,
						long_description
						)
	        		VALUES (
					" . $App->returnQuotedString($_project_id) . ",
					" . $App->returnQuotedString($_category_id) . ",
					" . $App->returnQuotedString($_description) . ",
					" . $App->returnQuotedString($this->getLongDescription()). "
						)";

		    $result = $App->eclipse_sql($sql);

		    $result = null;
		    $myrow	= null;
	    }
	}

}
?>