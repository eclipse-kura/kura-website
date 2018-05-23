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
require_once("webmaster.class.php");

class MailingLists extends Webmaster{

  private $default_search_table_and_name = array();

  private $default_search_options = array();

  private $newsgroups = NULL;

  private $mailing_lists = NULL;

  private $search_results = NULL;

  private $date_range = "5";

  public function __construct(App $App){
    parent::__construct($App);
    if ($this->getFormName() === 'webmaster-mailinglists') {
      switch ($this->getState()) {
        case 'create':
          $this->_createMailingLists();
          break;
        case 'delete':
          $this->_deleteItem();
          break;
        case 'search':
          $this->getSearchResults();
          break;
        case 'date-range':
          $this->_setDateRange();
          break;
      }
    }
  }

  private function _setDateRange() {
    $range = filter_var($this->App->getHTTPParameter('date-range', 'POST'), FILTER_SANITIZE_STRING);
    if (!empty($range)) {
      $this->date_range = $range;
    }
  }

  /**
   * This function returns the word selected wheter or not
   * an option is selected.
   * This helps adding the Selected parameter to <option> tags
   * @return string
   * */
  public function checkSelectedOption($title, $value) {
    if (!empty($title) && !empty($value)) {
      foreach ($this->default_search_options as $option){
        if ($option['title'] == $title && $option['value'] == $value) {
          return "selected";
        }
      }
    }
  }

  /**
   * Get Newsgroups
   */
  public function getNewsgroups() {
    if (is_null($this->newsgroups)) {
      $this->_fetchMailingListsOrNewsgroups('newsgroups', $this->date_range);
    }
    return $this->newsgroups;
  }

  /**
   * Get HTML for mailing list table
   *
   * @param array $list
   * @param string $table
   */
  public function getMailingListTable($list = array(), $table = '') {
    $html = '';
    if ($this->_isValidTable($table) && !empty($list)) {
      ob_start();
      include('tpl/mailinglists/mailinglists-table.tpl.php');
      $html = ob_get_clean();
    }
    return $html;
  }

  /**
   * Get Mailing lists.
   */
  public function getMailingLists(){
    if (is_null($this->mailing_lists)) {
      $this->_fetchMailingListsOrNewsgroups('mailing_lists', $this->date_range);
    }
    return $this->mailing_lists;
  }

  /**
   * This function returns the search results
   *
   * @return array
   * */
  public function getSearchResults() {
    if (is_null($this->search_results)) {
      $this->_searchForItems();
    }
    return $this->search_results;
  }

  /**
   * This function return a list of status
   * @return array
   * */
  public function getStatusList() {
    return array(
        'approve',
        'wait',
        'active',
        'completed',
        'pending'
    );
  }

  /**
   * This function returns the appropriate column title for the specified table
   *
   * @param $table - either mailing_lists or newgroups
   *
   * @return string
   * */
  public function getTableTitleName($table) {
    $name = 'group_name';
    if ($table == 'mailing_lists') {
      $name = 'list_name';
    }
    return $name;
  }

  /**
   * Get default_search_table_and_name value
   */
  public function getDefaultSearchTableAndName(){
    return $this->default_search_table_and_name;
  }

  /**
   * This function adds new mailing lists or newsgroups
   * */
  private function _createMailingLists() {
    $table = filter_var($this->App->getHTTPParameter('create_table', 'POST'), FILTER_SANITIZE_STRING);
    if ($this->_isValidTable($table)) {
      $project = filter_var($this->App->getHTTPParameter('create_project', 'POST'), FILTER_SANITIZE_STRING);
      $name = filter_var($this->App->getHTTPParameter('create_name', 'POST'), FILTER_SANITIZE_STRING);
      $description = filter_var($this->App->getHTTPParameter('create_description', 'POST'), FILTER_SANITIZE_STRING);

      // Default Message type
      $msg_type = "success";

      // Check for any empty values

      if (empty($project) || empty($name) || empty($description) || $this->_itemInTable($table, $name) === TRUE || !in_array(array('ProjectID' => $project), $this->getProjects())) {
        $msg_type = 'danger';
      }
      if (empty($project)) {
        $this->App->setSystemMessage('create_mailinglist','You must select a project.', $msg_type);
      }
      if (!empty($project) && !in_array(array('ProjectID' => $project), $this->getProjects())) {
        $this->App->setSystemMessage('create_mailinglist','The selected project is not part of the projects list.', $msg_type);
      }
      if (empty($name)) {
        $this->App->setSystemMessage('create_mailinglist','You must enter a name.', $msg_type);
      }
      if (empty($description)) {
        $this->App->setSystemMessage('create_mailinglist','You must enter a description.', $msg_type);
      }
      if (!empty($name) && $this->_itemInTable($table, $name) === TRUE) {
        $this->App->setSystemMessage('create_mailinglist',$name.' already exists in the '.$table.' table.', $msg_type);
      }

      if ($msg_type != 'danger') {
        $sql = "";
        switch ($table) {
          case "mailing_lists":
            $sql = "INSERT INTO mailing_lists
                    (list_name,list_description,is_private,project_id,list_short_description,create_date,created_by)
                    VALUES (
                      ".$this->App->returnQuotedString($this->App->sqlSanitize($name)).",
                      ".$this->App->returnQuotedString($this->App->sqlSanitize($description)).",
                      0,
                      ".$this->App->returnQuotedString($this->App->sqlSanitize($project)).",
                      ".$this->App->returnQuotedString($this->App->sqlSanitize($description)).",
                      NOW(),
                      ".$this->App->returnQuotedString("PORTAL")."
                    )";
            break;
          case "newsgroups":
            $sql = "INSERT INTO newsgroups
                    (group_name,project_id,group_description,create_date,created_by)
                    VALUES (
                    ".$this->App->returnQuotedString($this->App->sqlSanitize($name)).",
                    ".$this->App->returnQuotedString($this->App->sqlSanitize($project)).",
                    ".$this->App->returnQuotedString($this->App->sqlSanitize($description)).",
                    NOW(),
                    ".$this->App->returnQuotedString("PORTAL")."
                    )";
            break;
        }

        $result = $this->App->eclipse_sql($sql);

        $msg = "You have successfully created a new <strong>" .
               ($table == 'mailing_lists' ? "Mailing List" : "Newsgroup") .
               "</strong> called <strong>" . $name . "</strong>.";
        $this->App->setSystemMessage('create_mailinglist',$msg, $msg_type);
      }
    }
  }

  /**
   * This function deletes an item from a specific table
   * */
  private function _deleteItem() {
    $item = filter_var($this->App->getHTTPParameter('item_to_delete', 'POST'), FILTER_SANITIZE_STRING);
    $table = filter_var($this->App->getHTTPParameter('item_type', 'POST'), FILTER_SANITIZE_STRING);

    if ($this->_isValidTable($table) && $this->_itemInTable($table, $item) === TRUE) {
      $item_name = $this->getTableTitleName($table);

      $sql = "DELETE FROM " . $table . "
              WHERE ". $item_name ." = " . $this->App->returnQuotedString($this->App->sqlSanitize($item));
      $delete = $this->App->eclipse_sql($sql);
      $msg = 'You have successfully deleted <strong>' .
              $item . '</strong> from the <strong>'. $table .'</strong> table.';
      $this->App->setSystemMessage('delete_item', $msg, 'success');
    }
  }


  /**
   * This function fetches mailing lists of newsgroups
   *
   * @param $table - This is the table name
   * @param $date - Number of days to limit the query
   *
   * @return array
   * */
  private function _fetchMailingListsOrNewsgroups($table, $range) {
    $lists = array();
    if ($this->_isValidTable($table)) {
      $name = $this->getTableTitleName($table);
      $sql = "SELECT ". $this->App->sqlSanitize($name) ." as name, create_date, project_id, provision_status
              FROM " . $this->App->sqlSanitize($table) . "
              WHERE is_deleted = 0
              AND create_date BETWEEN NOW() - INTERVAL ". $this->App->sqlSanitize($range) ." DAY AND NOW()
              ORDER BY create_date DESC LIMIT 2000";

      $result = $this->App->eclipse_sql($sql);
      while ($row = mysql_fetch_array($result)) {
        if (is_null($row['provision_status'])){
          $row['provision_status'] = 'NULL';
        }
        $lists[$row['provision_status']][] = $row;
      }
    }
    $this->{$table} = $lists;
    return $lists;
  }

  /**
   * This function validates a new entry by checking for any duplicates
   *
   * @param $table - string containing the name of the table
   * @param $entry_name - string containing the name of the new group / mailing list
   *
   * @return bool
   * */
  private function _itemInTable($table, $item_name) {
    $sql = "SELECT * FROM " . $table;
    $result = $this->App->eclipse_sql($sql);
    while ($row = mysql_fetch_array($result)) {
      if (in_array($item_name, $row)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * This function searches through a specified table for items
   * @return array
   * */
  private function _searchForItems() {
    $table = filter_var($this->App->getHTTPParameter('search_table', 'POST'), FILTER_SANITIZE_STRING);
    $search_results = array();

    if (!empty($table) && $this->_isValidTable($table)) {

      $name = $this->getTableTitleName($table);
      $search_options = array();
      $array_item = 0;

      // Building the $search_options array using a loop depending on the options inserted in the search
      for ($i = 1; $i <= 3; $i++) {
        $value = filter_var($this->App->getHTTPParameter('search_'.$i, 'POST'), FILTER_SANITIZE_STRING);
        if (!empty($value)) {
          switch ($i) {
            case 1:
              $title = "project_id";
              break;
            case 2:
              $title = "provision_status";
              break;
            case 3:
              $title = $name;
              break;
          }
          $search_options[$array_item] = array(
            "title" => $title,
            "value" => filter_var($this->App->getHTTPParameter('search_'.$i, 'POST'), FILTER_SANITIZE_STRING)
          );
          $array_item++;
        }
      }

      $this->_setDefaultSearchFields($table, $search_options);

      // Build the SQL query depending on what options were inserted in the search
      $sql = "SELECT ". $name ." as name, create_date, project_id, provision_status
              FROM " . $table;
      foreach ($search_options as $key => $option) {
        if ($key == 0) {
          $sql_where = " WHERE ";
        }
        if ($key >= 1) {
          $sql_where = " AND ";
        }
        $sql .= $sql_where . $search_options[$key]['title'] . " = " . $this->App->returnQuotedString($this->App->sqlSanitize($search_options[$key]['value']));
      }
      $sql .= " ORDER BY create_date DESC";
      $result = $this->App->eclipse_sql($sql);

      while ($row = mysql_fetch_array($result)) {
        $row['table'] = $table;
        $search_results[] = $row;
      }

      if (empty($search_results)) {
        $search_results[0]['no_results'] = "No results were found.";
      }
    }
    $this->search_results = $search_results;
    return $search_results;
  }

  /**
   * This function sets the default search fields
   * */
  private function _setDefaultSearchFields($table, $search_options) {
    $this->default_search_table_and_name = array('table' => $table);
    foreach ($search_options as $option) {
      if ($option['title'] == 'list_name' || $option['title'] == 'group_name') {
        $this->default_search_table_and_name['name'] = $option['value'];
      }
    }
    $this->default_search_options = $search_options;
  }

  /**
   * This function verifies if a certain tables is part of the accepted tables
   * @param $table - Specified table name
   * @return bool
   * */
  private function _isValidTable($table) {
    $accepted_tables = array(
        'mailing_lists',
        'newsgroups'
    );
    if (!empty($table) && in_array($table, $accepted_tables)) {
      return TRUE;
    }
    if (empty($table)) {
      $this->App->setSystemMessage('create_mailinglist','You must select a table between Mailing lists or a Newsgroups.', 'danger');
    }
    return FALSE;
  }
}