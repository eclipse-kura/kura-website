<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
?>
<p id="back-to-top">
  <a class="visible-xs" href="#top">Back to the top</a>
</p>
<?php print $this->getFooterPrexfix();?>
<footer<?php print $this->getAttributes('footer');?>>
  <div<?php print $this->getAttributes('footer-container');?>>
    <div class="row">
      <section<?php print $this->getAttributes('footer1');?>>
        <?php print $this->getFooterRegion1();?>
      </section>
      <section<?php print $this->getAttributes('footer2');?>>
        <?php print $this->getFooterRegion2();?>
      </section>
      <section<?php print $this->getAttributes('footer3');?>>
        <?php print $this->getFooterRegion3();?>
      </section>
      <section<?php print $this->getAttributes('footer4');?>>
        <?php print $this->getFooterRegion4();?>
      </section>
      <?php print $this->getFooterRegion5();?>
      <a href="#" class="scrollup">Back to the top</a>
    </div>
  </div>
</footer>
<!-- Placed at the end of the document so the pages load faster -->
<script<?php print $this->getAttributes('script-theme-main-js');?>></script>
<?php print $this->getExtraJsFooter();?>
</body>
</html>
