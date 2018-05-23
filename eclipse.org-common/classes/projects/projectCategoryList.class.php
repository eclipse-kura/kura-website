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
require_once("projectCategory.class.php");
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class ProjectCategoryList {

	#*****************************************************************************
	#
	# projectCategoryList.class.php
	#
	# Author: 	Denis Roy
	# Date:		2005-10-25
	#
	# Description: Functions and modules related Lists of projectCategories
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

	function selectProjectCategoryList($_project_id, $_category_id, $_order_by) {

		$App = new App();
	    $WHERE = "";

	    if($_project_id != "") {
	            $WHERE = $App->addAndIfNotNull($WHERE);
	            $WHERE .= " PRC.project_id = " . $App->returnQuotedString($_project_id);
	    }
	    if($_category_id != "") {
	            $WHERE = $App->addAndIfNotNull($WHERE);
	            $WHERE .= " PRC.category_id = " . $App->returnQuotedString($_category_id);
	    }

	    if($WHERE != "") {
	            $WHERE = " WHERE " . $WHERE;
	    }

	    if($_order_by == "") {
	            $_order_by = "PRC.description";
	    }

	    $_order_by = " ORDER BY " . $_order_by;

	    $sql = "SELECT
					PRC.project_id,
					PRC.category_id,
					PRC.description AS ProjectCategoryDescription ,
					PRC.long_description AS ProjectCategoryLongDescription,
					PRJ.name,
					PRJ.level,
					PRJ.parent_project_id,
					PRJ.description,
					PRJ.url_download,
					PRJ.url_newsgroup,
					PRJ.url_mailinglist,
					PRJ.url_wiki,
					PRJ.url_docs,
					PRJ.url_index,
					PRJ.is_topframe,
					CAT.description AS CategoryDescription,
					CAT.category_shortname,
					CAT.image_name
	        	FROM
					project_categories 		AS PRC
					INNER JOIN projects 	AS PRJ ON PRJ.project_id = PRC.project_id
					INNER JOIN categories 	AS CAT ON CAT.category_id = PRC.category_id "
				. $WHERE
				. $_order_by;

	    $result = $App->eclipse_sql($sql);

	    while($myrow = mysql_fetch_array($result))
	    {
	            $Project 	= new Project();
	            $Project->setProjectID		($myrow["project_id"]);
	            $Project->setName			($myrow["name"]);
	            $Project->setLevel			($myrow["level"]);
	            $Project->setParentProjectID($myrow["parent_project_id"]);
	            $Project->setDescription	($myrow["description"]);
	    		$Project->setUrlDownload	($myrow["url_download"]);
	    		$Project->setUrlNewsgroup   ($myrow["url_newsgroup"]);
	    		$Project->setUrlMailingList ($myrow["url_mailinglist"]);
	    		$Project->setUrlWiki		($myrow["url_wiki"]);
	    		$Project->setUrlDocs		($myrow["url_docs"]);
	    		$Project->setUrlIndex		($myrow["url_index"]);
				$Project->setIsTopframe		($myrow["is_topframe"]);

				$Category = new Category();
				$Category->setCategoryID	($myrow["category_id"]);
				$Category->setDescription	($myrow["CategoryDescription"]);
				$Category->setCategoryShortname	($myrow["category_shortname"]);
				$Category->setImageName		($myrow["image_name"]);

				$ProjectCategory = new ProjectCategory();
				$ProjectCategory->setProjectID	($myrow["project_id"]);
				$ProjectCategory->setCategoryID	($myrow["category_id"]);
				$ProjectCategory->setDescription($myrow["ProjectCategoryDescription"]);
				$ProjectCategory->setLongDescription($myrow["ProjectCategoryLongDescription"]);
				$ProjectCategory->setProjectObject($Project);
				$ProjectCategory->setCategoryObject($Category);


	            $this->add($ProjectCategory);
	    }


	    $result = null;
	    $myrow	= null;
	}
}
?>
