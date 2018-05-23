<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>

<?php foreach ($results as $r) :?>
  <div class="col-md-6 col-sm-12">
    <?php foreach ($r as $col) :?>
    <p><?php print $col['name'];?></p>
    <?php endforeach;?>
  </div>
<?php endforeach;?>
