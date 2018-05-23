<?php
/*******************************************************************************
 * Copyright (c) 2006-2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *    Nathan Gervais (Eclipse Foundation) - Expanded new fields being added
 *******************************************************************************/
require_once("project.class.php");
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class ProjectList {

	#*****************************************************************************
	#
	# projectList.class.php
	#
	# Author: 	Denis Roy
	# Date:		2004-11-16
	#
	# Description: Functions and modules related Lists of projects
	#
	# HISTORY:
	#
	#*****************************************************************************

	var $list = array();


	function getList() {
		return $this->list;
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

	function selectProjectList($_project_id, $_name, $_level, $_parent_project_id, $_description, $_order_by, $_is_project = 0) {

		$App = new App();
	    $WHERE = " PRJ.is_active = 1";

	    if($_project_id != "") {
	            $WHERE = $App->addAndIfNotNull($WHERE);
	            $WHERE .= " PRJ.project_id = " . $App->returnQuotedString($_project_id);
	    }
	    if($_name != "") {
	            $WHERE = $App->addAndIfNotNull($WHERE);
	            $WHERE .= " PRJ.name LIKE " . $App->returnQuotedString("%" . $_name . "%");

	    }
	    if($_level > 0) {
	            $WHERE = $App->addAndIfNotNull($WHERE);
	            $WHERE .= " PRJ.level = " . $_level;
	    }
	    if($_parent_project_id != "") {
	            $WHERE = $App->addAndIfNotNull($WHERE);
	            $WHERE .= " PRJ.parent_project_id = " . $App->returnQuotedString($_parent_project_id);
	    }
	    if($_description != "") {
	            $WHERE = $App->addAndIfNotNull($WHERE);
	            $WHERE .= " PRJ.description LIKE " . $App->returnQuotedString("%" . $_description . "%");

	    }
	    if ($_is_project != 0)
	    {
	    		$WHERE = $App->addAndIfNotNull($WHERE);
	    		$WHERE .= " PRJ.is_project = " . $_is_project;
	    }

	    if($WHERE != "") {
	            $WHERE = " WHERE " . $WHERE;
	    }

	    if($_order_by == "") {
	            $_order_by = "PRJ.name";
	    }

	    $_order_by = " ORDER BY " . $_order_by;


	    $sql = "SELECT
					PRJ.project_id,
					PRJ.name,
					PRJ.level,
					PRJ.parent_project_id,
					PRJ.description,
					PRJ.url_download,
					PRJ.url_index,
					PRJ.is_topframe,
					PRJ.sort_order,
					PRJ.is_active,
					PRJ.url_newsgroup,
					PRJ.url_mailinglist,
					PRJ.url_wiki,
					PRJ.url_docs,
					PRJ.is_project
	        	FROM
					projects AS PRJ "
				. $WHERE
				. $_order_by;
# echo $sql;
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
	    		$Project->setUrlIndex		($myrow["url_index"]);
				$Project->setIsTopframe		($myrow["is_topframe"]);
				$Project->setSortOrder		($myrow["sort_order"]);
				$Project->setIsActive		($myrow["is_active"]);
				$Project->setUrlNewsgroup  ($myrow["url_newsgroup"]);
				$Project->setUrlMailingList($myrow["url_mailinglist"]);
				$Project->setUrlWiki  		($myrow["url_wiki"]);
				$Project->setUrlDocs  		($myrow["url_docs"]);
				$Project->setIsProject		($myrow["is_project"]);
	            $this->add($Project);
	    }

	    $result = null;
	    $myrow	= null;
	}


	/**
	 * @param string $_uid UID (committer ID) of the committer
	 * @since 2013-11-07
	 * @author droy
	 *
	 */
	function selectCommitterProjectList($_uid) {
		if($_uid != "") {
			$App = new App();
			$sql = "SELECT DISTINCT /* projectList.class.php */
						PRJ.ProjectID,
						PRJ.Name,
						PRJ.Level,
						PRJ.ParentProjectID,
						PRJ.Description,
						PRJ.UrlDownload,
						PRJ.UrlIndex,
						PRJ.IsActive
		        	FROM
						Projects AS PRJ
					INNER JOIN PeopleProjects AS PPL ON PRJ.ProjectID = PPL.ProjectID
					WHERE
						PRJ.IsActive
						AND PPL.PersonID = " . $App->returnQuotedString($App->sqlSanitize($_uid)) . "
						AND PPL.Relation IN ('CM', 'PL', 'PD', 'PM')
						AND (PPL.InactiveDate IS NULL OR PPL.InactiveDate > NOW())";
			$result = $App->foundation_sql($sql);

			while($myrow = mysql_fetch_array($result))
			{
				$Project 	= new Project();
				$Project->setProjectID		($myrow["ProjectID"]);
				$Project->setName			($myrow["Name"]);
				$Project->setLevel			($myrow["Level"]);
				$Project->setParentProjectID($myrow["ParentProjectID"]);
				$Project->setDescription	($myrow["Description"]);
				$Project->setUrlDownload	($myrow["UrlDownload"]);
				$Project->setUrlIndex		($myrow["UrlIndex"]);
				$Project->setIsActive		($myrow["IsActive"]);
				$this->add($Project);
			}
			$result = null;
			$myrow	= null;
		}
	}
}
?>