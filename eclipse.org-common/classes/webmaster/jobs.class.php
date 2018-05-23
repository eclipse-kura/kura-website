<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once("webmaster.class.php");

class Jobs extends Webmaster{

  private $job_status = NULL;

  private $pending_jobs = NULL;

  private $rsync_config_job = "/home/admin/rsync.configs.sh";

  public function __construct(App $App){
    parent::__construct($App);

    if ($this->getFormName() === 'webmaster-jobs') {
      if ($this->getState() === 'add_job') {
        $this->_addScheduledJobs();
      }
    }
  }

  /**
   * This function gets the Jobs status
   *
   * @return array
   * */
  public function getJobStatus() {
    if (is_null($this->job_status)) {
      $this->_fetchJobStatus();
    }
    return $this->job_status;
  }

  /**
   * This function gets the Pending Jobs
   *
   * @return array
   * */
  public function getPendingJobs() {
    if (is_null($this->pending_jobs)) {
      $this->_fetchPendingJobs();
    }
    return $this->pending_jobs;
  }

  /**
   * This function fetches the 5 latest Jobs status
   *
   * @return array
   * */
  private function _fetchJobStatus() {
    $sql = "SELECT
            sj.job_id as job_id,
            sj.job as job,
            sj.options as options,
            sjs.job_status as job_status,
            sjs.node as node,
            sjs.run_when as run_when
            FROM scheduled_jobs as sj
            LEFT JOIN scheduled_jobs_status as sjs
            ON sj.job_id = sjs.job_id
            WHERE sj.date_code < " . time() . "
            AND sj.job_id IN
              (
                select DISTINCT job_id
                FROM scheduled_jobs_status
                ORDER BY run_when DESC
              )
            ORDER BY sj.job_id DESC
            LIMIT 5";
    $result = $this->App->eclipse_sql($sql);
    $status = array();
    while ($row = mysql_fetch_array($result)) {
      $status[] = $row;
    }
    $this->job_status = $status;
    return $status;
  }

  /**
   * This function fetches the 25 latest pending jobs
   *
   * @return array
   * */
  private function _fetchPendingJobs() {
    $sql = "SELECT
              job_id,
              job,
              options,
              date_code
            FROM scheduled_jobs
            WHERE job_id NOT IN (SELECT DISTINCT job_id FROM scheduled_jobs_status)
            ORDER BY job_id
            DESC LIMIT 25";
    $result = $this->App->eclipse_sql($sql);
    $pending_jobs = array();
    while ($row = mysql_fetch_array($result)) {
      $row['date_code'] = date("F j, Y, g:i a", $row['date_code']);
      $pending_jobs[] = $row;
    }
    $this->pending_jobs = $pending_jobs;
    return $pending_jobs;
  }

  /**
   * This function adds new scheduled jobs to the scheduled_jobs table
   * */
  private function _addScheduledJobs() {

    $rsync = filter_var($this->App->getHTTPParameter('rsync', 'POST'), FILTER_SANITIZE_STRING);
    $postfix = filter_var($this->App->getHTTPParameter('postfix', 'POST'), FILTER_SANITIZE_STRING);
    $nscd = filter_var($this->App->getHTTPParameter('nscd', 'POST'), FILTER_SANITIZE_STRING);
    $newaliases = filter_var($this->App->getHTTPParameter('newaliases', 'POST'), FILTER_SANITIZE_STRING);

    $options = '';
    if ($rsync == 'on') {
      $options .= "rsync,";
    }
    if ($postfix == 'on') {
      $options .= "postfix,";
    }
    if ($nscd == 'on') {
      $options .= "nscd,";
    }
    if ($newaliases == 'on') {
      $options .= "newaliases,";
    }

    // trim any trailing commas
    $options = rtrim($options, ",");

    // Default status message
    $msg = "The new job couldn't be created";
    $msg_type = 'danger';

    if (empty($options)) {
      $msg .= "<br>- You need at least one rsync configs job option";
    }

    if (empty($this->rsync_config_job)) {
      $msg .= "<br>- You need to enter a valid path for the rsync config job";
    }

    if (!empty($this->rsync_config_job) && !empty($options)) {
      $sql = "INSERT INTO scheduled_jobs
          (job,options,date_code)
        VALUES (
          '" . $this->App->sqlSanitize($this->rsync_config_job) . "',
          " . $this->App->returnQuotedString($options) . ",
          UNIX_TIMESTAMP()
        )";
      $result = $this->App->eclipse_sql($sql);

      // Set Success System Message
      $msg = 'You have successfully added a new job.';
      $msg_type = 'success';
    }

    $this->App->setSystemMessage('add_job', $msg, $msg_type);
  }
}