<?php
/*******************************************************************************
 * Copyright(c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier(Eclipse Foundation)
 *******************************************************************************/

class Directors {

  private $App = NULL;

  private $directors = array();

  private $path_to_boardbio = "/org/foundation/boardbios";

  public function __construct(App $App) {
    $this->App = $App;
  }

  /**
   * This function returns an array of directors with their bio
   *
   * @return array
   *
   * */
  public function getDirectors() {
    if (empty($this->directors)) {
      $this->directors = $this->_fetchInfo();
    }
    return $this->directors;
  }

  /**
   * This function fetches a directors bio from a php file
   *
   * @return array
   * */
  private function _fetchBioFromFile($info = array()) {
    if (!empty($info)) {
      // Handle special cases where first or last name had multiple words
      $formatted_name = str_replace(' ', '_', $info['FName'] . ' ' . $info['LName']);
      // Replace periods in name
      $formatted_name = str_replace('.', '', $formatted_name);

      $content = "";
      if (file_exists($file = $_SERVER['DOCUMENT_ROOT'] . $this->path_to_boardbio . '/' . $formatted_name . '.php')) {
        $content = file_get_contents($file);
      }

      $img = "";
      if (!empty($info['OrganizationID'])) {
        $img = "<img src='http://www.eclipse.org/membership/scripts/get_image.php?id=". $info['OrganizationID'] ."&size=small'>";
      }
      return array(
          'fname' => $info['FName'],
          'lname' => $info['LName'],
          'param' => $info['Param'],
          'content' => $content,
          'img' => $img
      );
    }
    return array();
  }

  /**
   * This function fetches the directors information
   *
   * @return array
   * */
  private function _fetchInfo() {
    $directors = array();

    // Members with company relationships
    $sql = "SELECT p.FName, p.LName, o.Name1 as Param, o.OrganizationID
            FROM  People as p, OrganizationContacts as oc, Organizations as o
            WHERE p.PersonID = oc.PersonID
            AND oc.Relation = 'BR'
            AND oc.OrganizationID = o.OrganizationID";
    $result = $this->App->foundation_sql($sql);

    while($row = mysql_fetch_assoc($result)) {
      $directors[ucwords($row['LName'] . ', ' . $row['FName'])] = $this->_fetchBioFromFile($row);
    }

    // Elected add-in provider reps
    $sql = "SELECT p.FName, p.LName, pr.Relation as Relation
            FROM  People as p, PeopleRelations as pr
            WHERE p.PersonID = pr.PersonID
            AND (pr.Relation = 'AR' OR pr.Relation = 'CB')";
    $result = $this->App->foundation_sql($sql);

    while($row = mysql_fetch_assoc($result)) {
      if (!isset($directors[ucwords($row['LName'] . ', ' . $row['FName'])])) {
        switch ($row['Relation']) {
          case 'AR':
            $row['Param'] = "Elected Sustaining Member Representative";
            break;
          case 'CB':
            $row['Param'] = "Elected Committer Representative";
            break;
        }
        $directors[ucwords($row['LName'] . ', ' . $row['FName'])] = $this->_fetchBioFromFile($row);
      }
    }

    // Sort the directors by their last name
    ksort($directors);

    return $directors;
  }
}