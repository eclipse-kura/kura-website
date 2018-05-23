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
 *******************************************************************************/
require_once("link.class.php");

class Nav {

	#*****************************************************************************
	#
	# nav.class.php
	#
	# Author: 		Denis Roy
	# Date			2004-09-11
	#
	# Description: Functions and modules related to Navbar objects
	#
	# HISTORY:
	#
	#*****************************************************************************

	private $LinkList = array();

	private $HTMLBlock = "";


	function getLinkList() {
		return $this->LinkList;
	}

	function setLinkList($_LinkList) {
		$this->LinkList = $_LinkList;
	}

	function getHTMLBlock () {
		return $this->HTMLBlock;
	}

	function setHTMLBlock ($html) {
		$this->HTMLBlock = $html;
	}



	# Main constructor
	function __construct() {

		$www_prefix = "";

		global $App;

		if(isset($App)) {
			$www_prefix = $App->getWWWPrefix();
		}

	}

	function addCustomNav($_Text, $_URL, $_Target, $_Level, $_CSS=NULL) {
		if($_Level == "") {
			$_Level = 0;
		}
		$Link = new Link($_Text, $_URL, $_Target, $_Level, $_CSS);

		# Add incoming Nav Item
		$this->LinkList[count($this->LinkList)] = $Link;
	}

	function addNavSeparator($_Text, $_URL) {
		$Link = new Link($_Text, $_URL, "__SEPARATOR", 1);

		# Add incoming Nav Item
		$this->LinkList[count($this->LinkList)] = $Link;
	}

	function addMetaNav($_Project){
      if (!class_exists("projectInfoList")) {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/projects/projectInfoList.class.php");
      }

      #set the default depth
      $depth = 1;
      #New info data instance
      $MetaNav = new projectInfoList;
      #get the data
      $MetaNav->selectProjectInfoList($_Project,"projectleftnav");
      #sort the data & extract the data, which in this case should always be at 0
      $MetaLeftNav = $this->orderNavList($MetaNav->list[0]->projectleftnav);
      #is there something there?
      if ( $MetaLeftNav != NULL ) {
        #loop through the leftnav items and add them to the output array
        foreach( $MetaLeftNav as $item ) {
          #the url has to start with http(s):// and be an eclipse.org site, or we'll ignore it
          if ( preg_match("/^http[s]{0,1}:\/\/(\S.+eclipse.org(\/|$)|eclipseplugincentral.com)/i",$item->url) >= 1 ) {
            if ( $item->separator == 1 ){
              $this->addNavSeparator($item->title, $item->url);
            } else {
              $this->addCustomNav($item->title, $item->url, "", $depth+$item->indent);
              $depth = 1;
            }
          }
        }
      }
    }

    #remove list function from projectInfoList class, and put it here where it really belongs
	function orderNavList ($_InfoList) {
      if (!function_exists("cmp_navobj")) {
        function cmp_navobj($a, $b) {
          $al = $a->order;
          $bl = $b->order;
          if ($al == $bl) {
            return 0;
          }
          return ($al > $bl) ? +1 : -1;
        }
      }
      usort($_InfoList, "cmp_navobj");
      return $_InfoList;
    }

	function getLinkCount() {
		return count($this->LinkList);
	}

	function getLinkAt($_Pos) {
		if($_Pos < $this->getLinkCount()) {
			return $this->LinkList[$_Pos];
		}
	}



}
?>