<?php
/*******************************************************************************
 * Copyright (c) 2007-2009 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Bjorn Freeman-Benson - Initial API, meta-data inheritance
 *    Nathan Gervais - Fixed __get function to return correct values for multirow records
 *    Karl Matthias - Implemented Countable Extension to the class. And Plural __get retrieval
 * 					Added fields() and ProjectInfoID() functions, fixed bug in multi-row sets
 * 					Meta-data inheritance
 * 					Fixed meta-data inheritance not to override name/short name
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class ProjectInfoData implements Countable
{
	private $rows; // raw query results for database, ProjectID, MainKey, SubKey, Value, ProjectInfoID
	private $mainkeys; // [main key] -> # of rows
	private $subkeys;  // [main key] -> true if has subkeys, false otherwise
	public $original_projectid;
	public $effective_projectid;
	private $projectname; // Stored separately because even with inheritance we don't inherit this
	private $projectshortname; // Same reason as $projectname
	private $inherited = false;

	function __construct( $projectid )
	{
		$App = new App();
		$this->original_projectid = $projectid;
		while(1) { // While loop allows infinite levels of inheritance
			$result = $App->eclipse_sql("
						SELECT COUNT(1) AS count FROM ProjectInfo, ProjectInfoValues
							WHERE ProjectID = '$projectid'
							  AND ProjectInfo.ProjectInfoID = ProjectInfoValues.ProjectInfoID
							  AND MainKey = 'inherit'
							  AND Value = 'true'");
			$row = mysql_fetch_object( $result );
			if( $row && $row->count > 0 ) {
				// Ok, we inherited so store the original name
				$result2 = $App->eclipse_sql("
						SELECT Value AS projectname FROM ProjectInfo, ProjectInfoValues
							WHERE ProjectID = '$projectid'
							  AND ProjectInfo.ProjectInfoID = ProjectInfoValues.ProjectInfoID
							  AND MainKey = 'projectname'"
				);
				$row = mysql_fetch_object($result2);
				$this->projectname = $row->projectname;

				// Store shortname, too
				$result2 = $App->eclipse_sql("
						SELECT Value AS projectshortname FROM ProjectInfo, ProjectInfoValues
							WHERE ProjectID = '$projectid'
							  AND ProjectInfo.ProjectInfoID = ProjectInfoValues.ProjectInfoID
							  AND MainKey = 'projectshortname'"
				);
				$row = mysql_fetch_object($result2);
				$this->projectshortname = $row->projectshortname;

				// Set inherited flag
				$this->inherited = true;

				// Set the new projectid
				$words = explode( '.', $projectid );
				array_pop( $words);
				$projectid = implode( '.', $words );

			} else {
				break;
			}
		}
		$this->effective_projectid = $projectid;
		$result = $App->eclipse_sql("
					SELECT * FROM ProjectInfo, ProjectInfoValues
						WHERE ProjectID = '$projectid'
						  AND ProjectInfo.ProjectInfoID = ProjectInfoValues.ProjectInfoID");
		$this->rows = array();
		$this->mainkeys = array();
		$this->subkeys = array();
		while($row = mysql_fetch_assoc($result)) {
			$this->rows[] = $row;
			$mainkey = $row['MainKey'];
			if( isset($this->mainkeys[$mainkey]))
				$this->mainkeys[$mainkey]++;
			else
				$this->mainkeys[$mainkey] = 1;
			if( !isset($this->subkeys[$mainkey]))
				$this->subkeys[$mainkey] = false;
			if( $row['SubKey'] != null )
				$this->subkeys[$mainkey] = true;
		}
	}

	function __get( $varname ) {
		// When inheriting data, don't inherit the project name/short name
		if(($varname == 'projectname') && $this->inherited) {
			return $this->projectname;
		} elseif(($varname == 'projectshortname') && $this->inherited) {
			return $this->projectshortname;
		} elseif(($varname == 'projectnames') && $this->inherited) {
			return array($this->projectname);
		} elseif(($varname == 'projectshortnames') && $this->inherited) {
			return array($this->projectshortname);
		}

		$check_multiples = false;
		if(preg_match('/s$/', $varname)) // see if we need to look for "newsgroups" instead of just "newsgroup" for example
			$check_multiples = true;
		foreach( $this->rows as $row ) {
			if (preg_match('/projectid/i', $varname)) {	return $row['ProjectID'];}
			$mainkey = $row['MainKey'];
			$exactmatch = ($mainkey == $varname);
			$pluralmatch = ($mainkey . 's' == $varname);
			if( $exactmatch || ($check_multiples  && $pluralmatch) ) {
				if( $this->mainkeys[$mainkey] == 1 ) {
					if( $this->subkeys[$mainkey] == false ) {
						if($check_multiples && !$exactmatch) {
							$rtrn = array();
							$rtrn[] = $row['Value'];
							return $rtrn;
						} else {
							return $row['Value'];
						}
					} else {
						$subrows = array();
						foreach( $this->rows as $rr ) {
							if( $row['ProjectInfoID'] == $rr['ProjectInfoID']) {
								$subrows[] = $rr;
							}
						}
						if($check_multiples && !$exactmatch) {
							$rtrn = array();
							$rtrn[] = new ProjectInfoValues( $this, $subrows );
							return $rtrn;
						} else {
							return new ProjectInfoValues( $this, $subrows );
						}
					}
				} else {
					if( $this->subkeys[$mainkey] == false ) {
						$result = array();
						foreach( $this->rows as $rr ) {
							if( $rr['MainKey'] == $mainkey) {
								$result[] = $rr['Value'];
							}
						}
						return $result;
					} else {
						$result = array();
						$checked = array();
						foreach( $this->rows as $rr ) {
							if(isset($checked[$rr['ProjectInfoID']])) {
								continue;
							}
							$checked[$rr['ProjectInfoID']] = true;
							if( $rr['MainKey'] == $mainkey) {
								$subrows = array();
								foreach( $this->rows as $rrr ) {
									if( $rr['ProjectInfoID'] == $rrr['ProjectInfoID']) {
										$subrows[] = $rrr;
									}
								}
								$result[] = new ProjectInfoValues( $this, $subrows );
							}
						}
						return $result;
					}
				}
			}
		}
		return null;
	}

	function count() {
		return count($this->mainkeys);
	}
}

class ProjectInfoValues implements Countable {
	private $rows;
	function __construct( $projectinfo, $subrows ) {
		$this->rows = $subrows;
	}
	function __get( $varname )
	{
		foreach( $this->rows as $row ) {
			$subkey = $row['SubKey'];
			if( $subkey == $varname ) {
				return $row['Value'];
			}
		}
		return null;
	}

	function count() {
		return count($this->rows);
	}

	function fields() {
		$fields = array();
		foreach($this->rows as $row) {
			$fields[] = $row['SubKey'];
		}
		arsort($fields, SORT_STRING);
		return $fields;
	}

	function ProjectInfoID() {
		$row = $this->rows[0];
		return $row['ProjectInfoID'];
	}
}
?>