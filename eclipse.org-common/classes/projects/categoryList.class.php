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

require_once("category.class.php");
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class CategoryList {

	#*****************************************************************************
	#
	# CategoryList.class.php
	#
	# Author: 	Denis Roy
	# Date:		2005-10-25
	#
	# Description: Functions and modules related Lists of categories (for projects)
	#
	# HISTORY:
	#
	#*****************************************************************************

	var $list = array();


	function getList() {
		return $this->$list;
	}

	function setList($_list) {
		$this->list = $_list;
	}


    function add($_project) {
            $this->list[count($this->list)] = $_project;
    }


    function getCount() {
            return count($this->list);
    }

    function getItemAt($_pos) {
            if($_pos < $this->getCount()) {
                    return $this->list[$_pos];
            }
    }

	function selectCategoryList() {

		$App = new App();
	    $WHERE = "";

	    $sql = "SELECT
					CAT.category_id,
					CAT.description,
					CAT.image_name,
					CAT.category_shortname
	        	FROM
					categories AS CAT
				ORDER BY CAT.description ";

	    $result = $App->eclipse_sql($sql);

	    while($myrow = mysql_fetch_array($result)) {

	            $Category = new Category();
	            $Category->setCategoryID	($myrow["category_id"]);
	            $Category->setDescription	($myrow["description"]);
	            $Category->setImageName		($myrow["image_name"]);
	            $Category->setCategoryShortname ($myrow["category_shortname"]);
	            $this->add($Category);
	    }

	    $result = null;
	    $myrow	= null;
	}
}
?>
