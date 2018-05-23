<?php
/**
 * Copyright (c) 2016, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<div class="downloads-bar-ad">
  <div class="container">
    <div class="row">
      <div class="col-lg-20 col-md-18 downloads-bar-ad-white-shape">
        <p><?php print $variables['body']; ?></p>
      </div>
      <div class="col-lg-4 col-md-6 downloads-bar-ad-white-content">
        <a class="btn btn-primary" href="/go/<?php print $variables['button_url']; ?>"><?php print $variables['button_text']; ?></a>
      </div>
    </div>
  </div>
</div>