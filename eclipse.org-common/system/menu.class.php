<?php
/*******************************************************************************
 * Copyright (c) 2006-2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)  - created getMenuArray()
 *******************************************************************************/
if (!class_exists('MenuItem')) {
  require_once("menuitem.class.php");
}

class Menu {

	#*****************************************************************************
	#
	# menu.class.php
	#
	# Author: 		Denis Roy
	# Date			2004-09-11
	#
	# Description: Functions and modules related to menu objects
	#
	# HISTORY:
	#
	#*****************************************************************************

	private $MenuItemList = array();

	private $projectBranding = "";

	function getProjectBranding() {
	  return $this->projectBranding;
	}

	function setProjectBranding($_projectBranding) {
	  $this->projectBranding = $_projectBranding;
	}

	function getMenuItemList() {
		return $this->MenuItemList;
	}

	function setMenuItemList($_MenuItemList) {
		$this->MenuItemList = $_MenuItemList;
	}

	# Main constructor
	function __construct() {

		$www_prefix = "";

		global $App;

		if(!isset($App)) {
			$App = new App();
		}
		$www_prefix = $App->getWWWPrefix();

		$MenuText = "Home";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

		$MenuText = "Downloads";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/downloads/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

		$MenuText = "Users";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/users/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

		$MenuText = "Members";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/membership/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

		$MenuText = "Committers";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/committers/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

		$MenuText = "Resources";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/resources/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

		$MenuText = "Projects";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/projects/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

		$MenuText = "About Us";
		$MenuItem = new MenuItem($MenuText, $www_prefix . "/org/", "_self", 0);
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;

	}

	function addMenuItem($_Text, $_URL, $_Target) {
		# Menu Items must be added at position 1 .. position 0 is dashboard, last position is Signout
		$MenuItem = new MenuItem($_Text, $_URL, $_Target, 0);

		# Add incoming menuitem
		$this->MenuItemList[count($this->MenuItemList)] = $MenuItem;
	}

	function getMenuItemCount() {
		return count($this->MenuItemList);
	}

	function getMenuItemAt($_Pos) {
		if($_Pos < $this->getMenuItemCount()) {
			return $this->MenuItemList[$_Pos];
		}
	}

	function getMenuArray() {
		$return = array();
		foreach($this->MenuItemList as $menu){
			$return[] = $menu;
		}
		return $return;
	}

}
