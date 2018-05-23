<?php

/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Matt Ward (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
 
/*********************************************************
 *
 * Name: project_details_parsing
 * function: contains the functions that read  the files created by the individual projects to describe their
 * newsgroups and mailing lists, and inserts that data into the web stream
 * 
 * By: M. Ward
 * Date: Dec 13/05
 *
*********************************************************/

/*****************************
 * Name: getfile
 * Function: given a project id builds a location path
 * I/O: the project id and the name of the file, and the location of the document root of the server. returns the path
 * 
 * By: M. Ward
 * Date: Dec 21/05
*****************************/
function GetFile( $name, $filename, $docroot ) {

  //remove any index.html 
  $name = str_replace("index.html","", $name);
  //same thing for main.html
  $name = str_replace("main.html","", $name);
  //same thing for index.php
  $name = str_replace("index.php","", $name);
  //if there isn't a trailing / then insert one'
  if( substr($name,-1,1) != "/" )
    $name .= "/";
  //replace the www path with the internal document path
  $name = str_replace("http://www.eclipse.org/", $docroot . "/", $name);
  $localname = str_replace("http://eclipse.org/", $docroot . "/", $name);
  //build up the name of hte file on the local filesystem
  $group_file = $localname . "project-info/" . $filename;
  
  //echo "!! $group_file ??\n\r";
  
  return $group_file;
	
}

/***************************************
 * Name: NewsParse
 * function: Parses the newsgroup array object and output specific html
 * 
 * By: M. Ward
 * Date: Dec 13/05
 * 
 * Modified By: N. Gervais
 * Modified On : Sep 21/07
 * ChangeLog : Changed this to use the new project info database objects
****************************************/
function NewsParse( $newsgroupArray, $id ) {

	if (count($newsgroupArray)){
	    $news_name = trim($newsgroupArray->name);
	    if ($news_name != ""){
	    $news_html = "<a href=\"http://www.eclipse.org/newsportal/thread.php?group=" . $news_name . "\""  . ">$news_name</a>";
	    $webnews_html = "<a href=\"news://news.eclipse.org/" . $news_name . "\" alt='News server' title=\"News server\"/><img src='/images/newsgroup.png' alt='Web interface' title=\"Web interface\" /></a>";
		$newsarch_html = "<a href=\"http://dev.eclipse.org/newslists/news." . $news_name . "/\""  . " alt='Archive' title=\"Archive\" />Newsgroup Archive</a>";
		$newsrss_html = "<a href=\"http://dev.eclipse.org/newslists/news." . $news_name . "/maillist.rss\"></a><a href=\"http://dev.eclipse.org/newslists/news." . $news_name . "/maillist.rss\"><img src='/eclipse.org-common/themes/Phoenix/images/rss_btn.gif' alt='RSS Feed' title=\"RSS Feed\" /></a>";
		$newssearch = "<FORM NAME=\"$news_name\" METHOD=GET ACTION=\"/search/search.cgi\" onsubmit=\"fnSetAction();\">
				<table bgcolor=#EEEEEE border=0>
	  			<TR><TD>Search $news_name 
	      			<INPUT TYPE=\"text\" NAME=\"q\" value=\"\" SIZE=\"18\" class=\"groupsearch\">
	      			<INPUT TYPE=\"submit\" NAME=\"cmd\" value=\"Search\" class=\"groupsearch\">
	      			<INPUT TYPE=\"hidden\" NAME=\"form\" value=\"extended\">
	      	        <input type=\"hidden\" name=\"wf\" value=\"574a74\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"ul\" value=\"/newslists/news.$news_name\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"t\" value=\"5\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"t\" value=\"News\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"t\" value=\"Mail\"></td></tr></Table></FORM>";
		$description = $newsgroupArray->description;
		$html = "<p><a href=\"javascript:switchMenu('$news_name.$id');\" title=\"Description\"><img src='images/plus.gif' alt='Description' title=\"Description\"></a>  $news_html $webnews_html $newsrss_html </p><div id=\"$news_name.$id\" class=\"switchcontent\"> <p>$description </p> <p>$newsarch_html</p></div>";
	    }
	}     
	return $html;     
}

/***************************************
 * Name: MailParse
 * function: Parses the mail array object and output specific html
 * 
 * By: M. Ward
 * Date: Dec 21/05
 * 
 * Modified By: N. Gervais
 * Modified On : Sep 21/07
 * ChangeLog : Changed this to use the new project info database objects * 
****************************************/
function MailParse( $mailingListArray, $id ) {
	
if (count($mailingListArray)) {
	$mail_name = trim($mailingListArray->name);
    if ($mail_name != ""){
	    $mail_html = "<a href=\"http://dev.eclipse.org/mailman/listinfo/" . $mail_name . "\" alt=\"Subscribe\" title=\"Subscribe\" />$mail_name</a>";
		$mailarch_html = "<a href=\"http://dev.eclipse.org/mhonarc/lists/" . $mail_name . "/\""  . "alt='Archive' title=\"Archive\" />Mailing list archive</a>";
		$mailrss_html = "<a href=\"http://dev.eclipse.org/mhonarc/lists/" . $mail_name . "/maillist.rss\">RSS Feed  </a><a href=\"http://dev.eclipse.org/mhonarc/lists/" . $mail_name . "/maillist.rss\" ><img src='/eclipse.org-common/themes/Phoenix/images/rss_btn.gif' alt='RSS Feed' title=\"RSS Feed\" /></a>";
		$mailsearch = "<FORM name=\"$mail_name\" METHOD=GET ACTION=\"/search/search.cgi\" onsubmit=\"fnSetAction();\">
				<table bgcolor=#EEEEEE border=0>
	  			<TR><TD>Search $mail_name 
	      			<INPUT TYPE=\"text\" NAME=\"q\" value=\"\" SIZE=\"18\" class=\"groupsearch\">
	      			<INPUT TYPE=\"submit\" NAME=\"cmd\" value=\"Search\" class=\"groupsearch\">
	      			<INPUT TYPE=\"hidden\" NAME=\"form\" value=\"extended\">
	      	        <input type=\"hidden\" name=\"wf\" value=\"574a74\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"ul\" value=\"/mhonarc/lists/$mail_name\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"t\" value=\"5\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"t\" value=\"News\">
	      	        <INPUT TYPE=\"hidden\" NAME=\"t\" value=\"Mail\"></td></tr></Table></FORM>";
		$description = $mailingListArray->description;
	    $html = "<p><a href=\"javascript:switchMenu('$mail_name.$id');\" title=\"Description\"><img src='images/plus.gif' alt='Description' title=\"Description\"></a> $mail_html </p> <div id=\"$mail_name.$id\" class=\"switchcontent\"> <p> $description </p><p>$mailarch_html</p><p>$mailrss_html</p></div>";
    }
  }
  return $html;      
}



?>