<?php
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Wayne Beaton (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

#*****************************************************************************
#
# news.php
#
# Author: 		Wayne Beaton
# Date:			2006-02-02 (Happy Groundhog Day)
#
# Description: Use the rss_to_html($newsfile) function in this file to generate
# the html equivalent of the provided RSS file.
#
#****************************************************************************

require_once("xml_sax_parsing.php");

/*
 * This function parses the file with the provided name and
 * returns an instance of the Feed class with the information
 * contained within.
 */
function & get_news($file_name) {
	$handler = new RssFileHandler();
	parse_xml_file($file_name, $handler);
	return $handler->feed;
}

/*
 * This function returns a String containing the HTML representing
 * the contents of the file provided. Only the first parameter
 * (the name of the file containing the RSS content) is required.
 *
 * $file_name - name of the file containing the RSS data. This
 *   file is specified in terms the operating system will understand
 *   (i.e. a file name, not a URL)
 *
 * $rss_url - If provided, an "RSS" link will be rendered. When clicked
 *   the user will be sent to this URL. Use this to provide the user with
 *   a way to access the RSS file directly (so they can use it in their
 *   favourite news reader. Defaults to false meaning that no "RSS" link
 *   be rendered.
 *
 * $more_url - If provided, a "More" link will be rendered. When clicked
 *   the user will be sent to this URL. This is intended to be used to send
 *   the user to more information about the news feed. If you're displaying
 *   a 'short' format of the news, you might use this to provide a link to
 *   a 'long' format version of the same information. Defaults to "false"
 *   meaning that no "More" link will be rendered.
 *
 * $format - either 'long' or 'short'. Currently toggles whether
 *   or not the description is shown (shown only if set to 'long'). Defaults
 *   to 'long'.
 *
 * $count - the maximum number of entries displayed. Defaults to 1000.
 *   Note that no sorting is done; items are displayed as they occur in the
 *   file.
 *
 * Examples
 *
 * $file_name = $_SERVER['DOCUMENT_ROOT'] . "/webtools/wtpnews.rss";
 *
 * To get the HTML representation of the RSS file located at $file_name
 * with no links to the RSS file, or to more information, displaying a
 * long format (including the description):
 *
 * $news = rss_to_html($file_name);
 *
 * This one includes a link to the RSS file. Note that the URL is provided
 * relative to the root of eclipse.org. Whatever value you provide is
 * substituted as-is, so you can put pretty much anything here. In general,
 * the same file should be provided for both $file_name and $rss_url (the
 * file is referenced differently from within and without the server).
 *
 * $news = rss_to_html($file_name, '/webtools/wtpnews.rss');
 *
 * Same as above, but in 'short' format (no description). By sending false
 * as the value for the 'more' URL, the more link is not rendered.
 *
 * $news = rss_to_html($file_name, '/webtools/wtpnews.rss', false, 'short');
 *
 * Only display the top seven entries in the file.
 *
 * $news = rss_to_html($file_name, '/webtools/wtpnews.rss', false, 'short', 7);
 */

function rss_to_html($file_name, $rss_url=false, $more_url=false, $format='long', $count=1000) {
	$rss = get_news($file_name);

	if (!in_array($format, array ('short', 'long'))) {
		$format = 'short';
	}

	foreach ($rss->channel as $channel) {
		$html = "<h3>";

		// Add the RSS image on the right
		if ($rss_url) {
			$html .= "<a href=\"$rss_url\"><img src=\"/images/rss2.gif\" align=\"right\" title=\"RSS Feed\" alt=\"[RSS]\" /></a>";
		}

		// Add the title of the channel
		$html .= "$channel->title";
		// If we're displaying short format, provide a link to
		// show news in long format.
		if ($more_url)
			$html .= "&nbsp;<a href=\"$more_url\"><img src=\"/images/more.gif\" title=\"More...\" alt=\"[More]\" /></a>";

		$html .= "</h3>";

		$html .= "<ul class=\"midlist\">";

		foreach ($channel->item as $item) {
			if ($count == 0) break;
			$html .= "<li>";
			// The date is formatted day-month-year using numbers
			// The &#8209 is a non-breaking en dash.
			$html .= "<a href=\"$item->link\" target=\"_blank\">$item->title</a>";

			// If the pubDate was not specified correctly or was not
			// specified at all, don't try to print it.
			if ($item->pubDate > 0) {
				$date = date("M d, Y", $item->pubDate);
				$date = str_replace(" ", "&nbsp;", $date);
				$html .= " posted&nbsp;$date";
			}

			if ($format == 'long') {
				$html .= "<blockquote>$item->description</blockquote>";
			}

			$html .= "</li>";
			$count--;
		}

		$html .= "</ul>";
	}

	return $html;
}

/*
 * Gorry implementation details.
 */


/*
 * Instances of the Feed class represent an RSS file.
 */
class Feed {
	var $channel;

	function Feed() {
		$this->channel = array();
	}

	function add_channel(&$channel) {
		array_push($this->channel, $channel);
	}
}

/*
 * Instances of the Channel class represent a channel in the RSS file.
 */
class Channel {
	var $title;
	var $link;
	var $description;
	var $image;
	var $item;

	function Channel() {
		$this->item = array();
	}

	function add_item(&$item) {
		array_push($this->item, $item);
	}
}

/*
 * Instances of the Image class represent an image (presumably) on an
 * image. We don't currently use this information.
 */
class Image {
	var $url;
	var $title;
	var $link;
}

/*
 * Instances of the Item class represent an item in a channel.
 */
class Item {
	var $title;
	var $link;
	var $description;
	var $pubDate;
}


/*
 * The rest of the code in this file is concerned with reading XML
 * into an object format. Once we update to PHP 5, we can get rid of
 * all of this junk and just use the simpleXML apis.
 */

/*
 * The RssFileHandler represents the file being parsed. It does
 * only one thing: provides a handler for the contents of the
 * the file.
 */
class RssFileHandler extends XmlFileHandler {
	var $feed;
	/*
	 * This method returns the root handler for a RSS file
	 * The root handler essentially represents the file itself
	 * rather than any actual element in the file. The returned
	 * element handler will deal with any elements that may occur
	 * in the root of the XML file.
	 */
	function get_root_element_handler() {
		return new RssRootHandler();
	}

	function end_root_element_handler($handler) {
		$this->feed = & $handler->feed;
	}
}

/*
 * The RssRootHandler class takes care of the root element
 * in the file. This handler doesn't correspond to any particular
 * element that may occur in the XML file. It represents the file
 * itself and must deal with any elements that occur at the root
 * level in that file.
 */
class RssRootHandler extends XmlElementHandler {
	var $feed;

	/*
	 * This method handles the <rss>...</rss> element.
	 */
	function & get_rss_handler($attributes) {
		return new RssHandler();
	}

	function end_rss_handler($handler) {
		$this->feed = & $handler->feed;
	}
}

/*
 * The FeedHandler class takes care of the root element in the file.
 */
class RssHandler extends XmlElementHandler {
	var $feed;

	function RssHandler() {
		$this->feed = new Feed();
	}

	/*
	 * This method handles the <channel>...</channel> element.
	 */
	function & get_channel_handler($attributes) {
		return new ChannelHandler();
	}

	function end_channel_handler($handler) {
		$this->feed->add_channel($handler->channel);
	}
}

class ChannelHandler extends XmlElementHandler {
	var $channel;

	function ChannelHandler() {
		$this->channel = new Channel();
	}

	/*
	 * This method handles the <title>...</title> element.
	 */
	function & get_title_handler($attributes) {
		return new SimplePropertyHandler($this->channel, "title");
	}
	/*
	 * This method handles the <link>...</link> element.
	 */
	function & get_link_handler($attributes) {
		return new SimplePropertyHandler($this->channel, "link");
	}
	/*
	 * This method handles the <description>...</description> element.
	 */
	function & get_description_handler($attributes) {
		return new SimplePropertyHandler($this->channel, "description");
	}

	/*
	 * This method handles the <title>...</title> element.
	 */
	function & get_item_handler($attributes) {
		return new ItemHandler();
	}

	function end_item_handler($handler) {
		$this->channel->add_item($handler->item);
	}

	/*
	 * This method handles the <image>...</image> element.
	 */
	function & get_image_handler($attributes) {
		return new ImageHandler();
	}

	function end_image_handler($handler) {
		$this->channel->image = $handler->image;
	}
}

class ItemHandler extends XmlElementHandler {
	var $item;

	function ItemHandler() {
		$this->item = new Item();
	}

	/*
	 * This method handles the <title>...</title> element.
	 */
	function & get_title_handler($attributes) {
		return new SimplePropertyHandler($this->item, "title");
	}
	/*
	 * This method handles the <link>...</link> element.
	 */
	function & get_link_handler($attributes) {
		return new SimplePropertyHandler($this->item, "link");
	}
	/*
	 * This method handles the <description>...</description> element.
	 */
	function & get_description_handler($attributes) {
		return new SimplePropertyHandler($this->item, "description");
	}

	/*
	 * This method handles the <pubDate>...</pubDate> element.
	 */
	function & get_pubdate_handler($attributes) {
		return new SimpleTextHandler();
	}

	function end_pubdate_handler($handler) {
		$value = trim($handler->text);
		if (strlen($value)>0) {
			$this->item->pubDate = strtotime($value);
		}
	}
}

class ImageHandler extends XmlElementHandler {
	var $image;

	function ImageHander() {
		$this->image = new Image();
	}

	/*
	 * This method handles the <title>...</title> element.
	 */
	function & get_title_handler($attributes) {
		return new SimplePropertyHandler($this->image, "title");
	}
	/*
	 * This method handles the <url>...</url> element.
	 */
	function & get_url_handler($attributes) {
		return new SimplePropertyHandler($this->image, "url");
	}
	/*
	 * This method handles the <link>...</link> element.
	 */
	function & get_link_handler($attributes) {
		return new SimplePropertyHandler($this->image, "link");
	}
}

?>