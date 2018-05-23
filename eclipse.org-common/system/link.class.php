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
class Link {

	#*****************************************************************************
	#
	# link.class.php
	#
	# Author: 		Denis Roy
	# Date			2004-09-14
	#
	# Description: Functions and modules related to link objects
	#
	# HISTORY:
	#
	#*****************************************************************************

	private $Text	= "";
	private $URL	= "";
	private $Target	= "";
	private $Level = 0;


	function getText() {
		return $this->Text;
	}
	function getURL() {
		return $this->URL;
	}
	function getTarget() {
		return $this->Target;
	}
	function getLevel() {
		return $this->Level;
	}

	function setText($_Text) {
		$this->Text = $_Text;
	}
	function setURL($_URL) {
		$this->URL = $_URL;
	}
	function setTarget($_Target) {
		$this->Target = $_Target;
	}
	function setLevel($_Level) {
		$this->Level = $_Level;
	}


	# Main constructors
	function __construct($_Text, $_URL, $_Target, $_Level) {
		$this->setText		($_Text);
		$this->setURL		($_URL);
		$this->setTarget	($_Target);
		$this->setLevel		($_Level);
	}

}
?>