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

class Mirrors extends Webmaster{

  private $mirrors = NULL;

  function __construct(App $App) {
    parent::__construct($App);

    if ($this->getFormName() === 'webmaster-mirror-update') {
      if ($this->getState() === 'update_mirrors') {
        $this->_updateMirrors();
      }
    }
  }

  /**
   * This function returns the mirrors for approval
   *
   * @return array
   * */
  public function getMirrors($status = '') {
    if (is_null($this->mirrors)) {
      $this->_fetchMirrors();
    }

    if (!empty($status) && !empty($this->mirrors[$status])) {
      return $this->mirrors[$status];
    }

    return $this->mirrors;
  }

  /**
   * This function returns an array of Mirror statuses
   *
   * @return array
   * */
  public function getMirrorStatuses() {
    return array(
        'approve',
        'active',
        'dropped',
    );
  }

  /**
   * This function fetches the mirrors depending on the status
   * @param $status - string
   * @return array
   * */
  private function _fetchMirrors() {

    $sql = "SELECT
      m.mirror_id,
      m.organization,
      m.is_internal,
      m.create_status,
      mp.protocol,
      mp.base_path

      FROM mirrors as m
      LEFT JOIN mirror_protocols  as mp
      ON m.mirror_id = mp.mirror_id";
    $sql .= ' WHERE m.create_status IN ("approve", "active", "dropped")
              ORDER BY FIELD(m.create_status, "approve", "active", "dropped") ASC';
    $result = $this->App->eclipse_sql($sql);

    $mirrors = array();
    while ($row = mysql_fetch_array($result)) {
      $row['is_internal'] = 'No';
      if ($row['is_internal'] === "1") {
        $row['is_internal'] = 'yes';
      }
      $row['row_context'] = "default";
      switch ($row['create_status']) {
        case 'active':
          $row['row_context'] = "success";
          break;
        case 'approve':
          $row['row_context'] = "warning";
          break;
        case 'dropped':
          $row['row_context'] = "danger";
          break;
      }
      $mirrors[$row['create_status']][] = $row;
    }
    $this->mirrors = $mirrors;
    return $mirrors;
  }

  /**
   * This function updates the status of selected mirrors
   * */
  private function _updateMirrors() {

    $mirror_status = filter_var($this->App->getHTTPParameter('status', 'POST'), FILTER_SANITIZE_STRING);

    if (in_array($mirror_status, $this->getMirrorStatuses())) {
      $mirrors = $this->getMirrors($mirror_status);
      $ids = array();
      foreach ($mirrors as $mirror) {
        $status = filter_var($this->App->getHTTPParameter('status_update_' . $mirror['mirror_id'], 'POST'), FILTER_SANITIZE_STRING);
        if ($status != $mirror['create_status']) {
          $ids[] = array(
            'mirror_id' => $mirror['mirror_id'],
            'create_status' => $status
          );
        }
      }

      if (!empty($ids[0]['mirror_id']) && !empty($ids[0]['create_status'])) {
        $sql = "UPDATE mirrors SET create_status = CASE ";
        $in = array();
        foreach ($ids as $id) {
          $create_status = $this->App->returnQuotedString($this->App->sqlSanitize($id['create_status']));
          $mirror_id = $this->App->returnQuotedString($this->App->sqlSanitize($id['mirror_id']));
          $sql .= " WHEN mirror_id = " . $mirror_id . " THEN " . $create_status;
          $in[] = $mirror_id;
        }
        $in = implode(', ', $in);
        $sql .= " END WHERE mirror_id in (" . $in . ")";

        $result = $this->App->eclipse_sql($sql);
        $this->_fetchMirrors();
        $this->App->setSystemMessage('mirror_updated', 'You have successfully updated ' . count($ids) .' mirror(s).', 'success');
      }
      else {
        $this->App->setSystemMessage('mirror_updated', "The mirrors could not be updated.(#webmaster-mirrors-001)", 'danger');
      }
    }
    else {
      $this->App->setSystemMessage('mirror_updated', "The mirror status is not valid.(#webmaster-mirrors-002)", 'danger');
    }
  }
}