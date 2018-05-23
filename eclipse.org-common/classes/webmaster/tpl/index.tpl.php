<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
if(basename(__FILE__) == basename($_SERVER['PHP_SELF']) || !$this->Friend->checkUserIsWebmaster()){
  exit();
}
?>

<ul>
  <li><a href="/webmaster/mirrors.php">Manage mirrors</a></li>
  <li><a href="/webmaster/jobs.php">Work with jobs</a></li>
  <li><a href="/webmaster/firewall.php">Manage firewall rules</a></li>
  <li><a href="/webmaster/mailinglists.php">Manage mailing lists and newsgroups</a></li>
</ul>
