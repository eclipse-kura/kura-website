<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
?>
<hr/>
<h3 id="section-captcha">captcha.class.php</h3>
<p>reCAPTCHA is a free service to protect your website from spam and abuse.
reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep
automated software from engaging in abusive activities on your site.
It does this while letting your valid users pass through with ease.</p>

<h4 id="section-recaptcha">reCAPTCHA</h4>
<form action="" method="post">
  <?php
    $Captcha = $App->useCaptcha($ssl = true);
    if ($Captcha->validate()) {
      echo "You got it!";
    }
    //echo $Captcha->get_error();
    echo $Captcha->get_html();
  ?>
  <br/>
  <input type="submit" value="submit" />
</form>
<h5>Code</h5>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true"></div>
<pre>
&lt;?php
  $Captcha = $App->useCaptcha($ssl = true);
  if ($Captcha->validate()) {
    echo "You got it!";
  }
  echo $Captcha->get_html();
</pre>

<br/>
<h4 id="section-recaptcha-mailhide">Mailhide</h4>
<?php ob_start();?>
<p>The Mailhide version of example@example.com is <?php print $Captcha->get_mailhide_html("example@example.com"); ?>.</p>

<p>The url for the email is:<br/>
<a href="<?php print $Captcha->get_mailhide_url("example@example.com"); ?>" target="_blank"><?php print $Captcha->get_mailhide_url("example@example.com"); ?></a></p>
<?php
  $html = ob_get_clean();
  print $html;
?>

<h5>Code</h5>
<pre>
&lt;?php
  $Captcha = $App->useCaptcha($ssl = true);
?&gt;

&lt;p&gt;The Mailhide version of example@example.com is &lt?php print $Captcha->get_mailhide_html("example@example.com"); ?>.&lt;/p&gt;
&lt;a href="&lt;?php print $Captcha->get_mailhide_url("example@example.com");?&gt;" target="_blank"&gt; &lt;?php print $Captcha->get_mailhide_url("example@example.com"); ?&gt;&lt;/a&gt;&lt;/p&gt;
</pre>
