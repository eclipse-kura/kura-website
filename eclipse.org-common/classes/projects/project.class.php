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

class Project {

	#*****************************************************************************
	#
	# project.class.php
	#
	# Author: 	Denis Roy
	# Date:		2004-11-16
	#
	# Description: Functions and modules related to the MySQL database connection
	#
	# HISTORY:
	#
	#*****************************************************************************

	var $project_id 		= "";
	var $name				= "";
	var $level				= 0;
	var $parent_project_id 	= "";
	var $description		= "";
	var $url_download		= "";
	var $url_index			= "";
	var $is_topframe		= 0;
	var $sort_order			= 0;
	var $is_active			= 0;
	var $url_newsgroup      = "";
	var $url_mailinglist	= "";
	var $url_wiki			= "";
	var $url_docs			= "";
	var $is_project			= 0;
	var $drop;


	function getProjectID() {
		return $this->project_id;
	}
	function getName() {
		return $this->name;
	}
	function getlevel() {
		return $this->level;
	}
	function getParentProjectID() {
		return $this->parent_project_id;
	}
	function getDescription() {
		return $this->description;
	}
	function getUrlDownload() {
		return $this->url_download;
	}
	function getUrlIndex() {
		return $this->url_index;
	}
	function getIsTopFrame() {
		return $this->is_topframe;
	}
	function getSortOrder() {
		return $this->sort_order;
	}
	function getIsActive() {
		return $this->is_active;
	}
	function getUrlNewsgroup() {
		return $this->url_newsgroup;
	}
	function getUrlMailingList() {
		return $this->url_mailinglist;
	}
	function getUrlWiki() {
		return $this->url_wiki;
	}
	function getUrlDocs() {
		return $this->url_docs;
	}
	function getIsProject() {
		return $this->is_project;
	}
	function getProjectShortName() {
		$rValue = "";

		$intPos = strrpos($this->getProjectID(), ".");

		if($intPos) {
			return substr($this->getProjectID(), $intPos + 1);
		}
		else {
			return $this->getProjectID();
		}
	}


	function getDrop() {
		return $this->drop;
	}

	function setProjectID($_project_id) {
		$this->project_id = $_project_id;
	}
	function setName($_name) {
		$this->name = $_name;
	}
	function setlevel($_level) {
		$this->level = $_level;
	}
	function setParentProjectID($_parent_project_id) {
		$this->parent_project_id = $_parent_project_id;
	}
	function setDescription($_description) {
		$this->description = $_description;
	}
	function setDrop($_drop) {
		$this->drop = $_drop;
	}
	function setUrlDownload($_url_download) {
		$this->url_download = $_url_download;
	}
	function setUrlIndex($_url_index) {
		$this->url_index = $_url_index;
	}
	function setIsTopframe($_is_topframe) {
		$this->is_topframe = $_is_topframe;
	}
	function setSortOrder($_sort_order) {
		$this->sort_order = $_sort_order;
	}
	function setIsActive($_is_active) {
		$this->is_active = $_is_active;
	}
	function setUrlNewsgroup($_url_newsgroup) {
		$this->url_newsgroup = $_url_newsgroup;
	}
	function setUrlMailingList($_url_mailinglist) {
		$this->url_mailinglist = $_url_mailinglist;
	}
	function setUrlWiki($_url_wiki) {
		$this->url_wiki = $_url_wiki;
	}
	function setUrlDocs($_url_docs) {
		$this->url_docs = $_url_docs;
	}
	function setIsProject($_is_project) {
		$this->is_project = $_is_project;
	}



	function selectList($_project_id) {

		$App = new App();
	    $WHERE = "";

	    if($_project_id != "") {

            $WHERE .= " WHERE PRJ.project_id = " . $App->returnQuotedString($_project_id);

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
					. $WHERE;

		    $result = $App->eclipse_sql($sql);

			if($myrow = mysql_fetch_array($result)) {

				$this->setProjectID		($myrow["project_id"]);
				$this->setName			($myrow["name"]);
				$this->setLevel			($myrow["level"]);
				$this->setParentProjectID($myrow["parent_project_id"]);
				$this->setDescription	($myrow["description"]);
				$this->setUrlDownload	($myrow["url_download"]);
				$this->setUrlIndex		($myrow["url_index"]);
				$this->setIsTopframe	($myrow["is_topframe"]);
				$this->setSortOrder		($myrow["sort_order"]);
				$this->setIsActive		($myrow["is_active"]);
				$this->setUrlNewsgroup   ($myrow["url_newsgroup"]);
				$this->setUrlMailingList($myrow["url_mailinglist"]);
				$this->setUrlWiki  		($myrow["url_wiki"]);
				$this->setUrlDocs  		($myrow["url_docs"]);
				$this->setIsProject		($myrow["is_project"]);
		    }

		    $result = null;
		    $myrow	= null;

	    }
	}

}
?>
