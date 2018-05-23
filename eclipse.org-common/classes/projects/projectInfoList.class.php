<?php
/*******************************************************************************
 * Copyright (c) 2007 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *    Karl Matthias (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
require_once("projectInfoData.class.php");
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class projectInfoList {


	var $list = array();

   	function alphaSortList(){

   		if (!function_exists("cmp_obj"))
   		{
	   		function cmp_obj($a, $b)
		    {
		        $al = trim(strtolower($a->projectname));
		        $bl = trim(strtolower($b->projectname));
		        if ($al == $bl) {
		            return 0;
		        }
		        return ($al > $bl) ? +1 : -1;
		   	}
   		}
		usort($this->list, "cmp_obj");
	}


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

	function selectProjectInfoList($_projectID = NULL, $_mainKey = NULL, $_subKey = NULL, $_value = NULL, $_projectInfoID = NULL , $_order_by = NULL) {

		   $App = new App();

		   $sql = "SELECT DISTINCT ProjectID
						FROM ProjectInfo, ProjectInfoValues";

		   if ($_projectID) {

		   		$wheresql .= " ProjectID like '$_projectID%'";
		   }
		   if ($_mainKey) {
		   		$wheresql = $this->addAndIfNotNull($wheresql);
		   		$wheresql .= " MainKey = '$_mainKey'";
		   }
		   if ($_subKey) {
		   		$wheresql = $this->addAndIfNotNull($wheresql);
		   		$wheresql .= " SubKey = '$_subKey'";
		   }
		   if ($_value) {
		   		$wheresql = $this->addAndIfNotNull($wheresql);
		   		$wheresql .= " Value = '$_value'";
		   }
		   if ($_projectInfoID) {
		   		$wheresql = $this->addAndIfNotNull($wheresql);
		   		$wheresql .= " ProjectInfo.ProjectInfoID = '$_projectInfoID'";
		   }


		if($wheresql != "") {
	            $wheresql = " WHERE " . $wheresql. " AND ProjectInfo.ProjectInfoID = ProjectInfoValues.ProjectInfoID";
	    }

	    if($_order_by == "") {
	            $_order_by = "MainKey";
	    }
	    $_order_by = " ORDER BY " . $_order_by;

	    $sql = $sql . $wheresql . $_order_by;


	    $result = $App->eclipse_sql($sql) or die ("ProjectInfoList.selectProjectInfoList: ". mysql_error());

	    while ($sqlIterator = mysql_fetch_array($result))
	    {
	    	$projectID = $sqlIterator['ProjectID'];
	    	$ProjectInfoData = new ProjectInfoData($projectID);
	    	$this->add($ProjectInfoData);
	    }
    }

	function addAndIfNotNull($_String) {
		# Accept: String - String to be AND'ed
		# return: string - AND'ed String

		if($_String != "") {
			$_String = $_String . " AND ";
		}

		return $_String;
	}
}
?>