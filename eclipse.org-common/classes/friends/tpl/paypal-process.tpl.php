<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>

<h1>Processing donation...</h1>
<?php print $this->get_client_message();?>
<p>Please wait while you are being redirected to paypal.com.</p>
<p><a href="<?php print $this->get_gateway_redirect();?>" class="btn btn-primary">Continue to paypal.com</a></p>
