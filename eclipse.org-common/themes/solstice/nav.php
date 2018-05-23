<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *    gbarbier mia-software com - bug 284239
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation of solstice
 *******************************************************************************/
$navigation = $this->getNav();
if (!empty($navigation['#items'])) :
?>
  <!-- nav -->
  <aside<?php print $this->getAttributes('main-sidebar');?>>
    <?php print $this->getThemeVariables('leftnav_html');?>
    <ul id="leftnav" class="ul-left-nav fa-ul hidden-print">
      <?php foreach ($navigation['#items'] as $link) :?>

        <?php if ($link->getURL() == "") :?>
          <?php if ($link->getTarget() == "__SEPARATOR") : ?>
            <li class="separator">
              <a class="separator">
                <?php print $link->getText() ?>
              </a>
            </li>
          <?php else: ?>
            <li>
              <i class="fa fa-caret-right fa-fw"></i>
              <a class="nolink" href="#"><?php print $link->getText() ?></a>
            </li>
          <?php endif; ?>
        <?php else: // if $link->getURL() is not empty. ?>

          <?php if($link->getTarget() == "__SEPARATOR") :?>
            <li class="separator">
              <a class="separator" href="<?php print $link->getURL() ?>">
                <?php print $link->getText() ?>
              </a>
            </li>
          <?php else:?>
            <li>
              <i class="fa fa-caret-right fa-fw"></i>
              <a href="<?php print $link->getURL() ?>" target="<?php print ($link->getTarget() == "_blank") ? "_blank" : "_self" ?>">
                <?php print $link->getText() ?>
              </a>
            </li>
          <?php endif;?>

        <?php endif;?>
      <?php endforeach; ?>
    </ul>
    <?php if (!empty( $navigation['html_block'])) :?>
      <div<?php print $this->getAttributes('main-sidebar-html-block');?>>
        <?php print $navigation['html_block']; ?>
      </div>
    <?php endif;?>
  </aside>
<?php endif;?>