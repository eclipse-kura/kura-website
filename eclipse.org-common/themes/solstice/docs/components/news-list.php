<?php
/**
 * Copyright (c) 2014, 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * Eric Poirier (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<?php ob_start(); ?>
<div class="news-list news-list-match-height">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-lg-offset-2 col-md-12 news-list-col padding-bottom-50">
        <div class="news-list-icon text-center">
          <i data-feather="activity" stroke-width="1"></i>
        </div>
        <h2 class="text-center">Announcements</h2>
        <ul class="news-list-media list-unstyled">
          <li>
            <a href="#" class="media media-link">
            <p class="media-date">2018/04/17</p>
            <h4 class="media-heading">Title</h4>
            <p class="media-text">This is some text.</p></a>
          </li>
        </ul>
        <ul class="list-inline news-list-links">
          <li class="news-list-links-view-all"><a
            href="/community/news/eclipsenews.php"
          >View all</a></li>
          <li class="news-list-links-rss"><a
            href="http://feeds.feedburner.com/eclipse/fnews"
            title="Subscribe to our RSS-feed"
          >Subscribe to our RSS-feed <i class="fa fa-rss"></i></a></li>
        </ul>
      </div>
      <div class="col-lg-10 col-md-12 news-list-col padding-bottom-50">
        <div class="news-list-icon text-center">
          <i data-feather="activity" stroke-width="1"></i>
        </div>
        <h2 class="text-center">Community News</h2>
        <ul class="news-list-media list-unstyled">
          <li>
            <a href="#" class="media media-link">
            <p class="media-date">2018/04/17</p>
            <h4 class="media-heading">Title</h4>
            <p class="media-text">This is some text.</p></a>
          </li>
        </ul>
        <ul class="list-inline news-list-links">
          <li class="news-list-links-view-all"><a
            href="/community/news/eclipseinthenews.php"
          >View all</a></li>
          <li class="news-list-links-rss"><a
            href="http://feeds.feedburner.com/eclipse/cnews"
            title="Subscribe to our RSS-feed"
          >Subscribe to our RSS-feed <i class="fa fa-rss"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php $html = ob_get_clean();?>
<h3 id="section-news-list">News list</h3>
<?php print $html; ?>
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>