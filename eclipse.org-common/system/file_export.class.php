<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

Class FileExport {

  /**
   * This function builds the CSV
   *
   * @param $file_name - Name of the file
   * @param $column_titles - Title of all the columns
   * @param $rows - All the rows to go in the csv file
   *
   **/
  public function buildCsv($file_name = "data", $rows = array(), $column_titles = array()) {

    if (empty($rows)) {
      return array();
    }

    // output headers so that the file is downloaded rather than displayed
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$file_name.'.csv');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // output the column headings
    if (!empty($column_titles)) {
      fputcsv($output, $column_titles);
    }

    // loop over the rows, outputting them
    foreach($rows as $row) {
      fputcsv($output, $row);
    }

    ob_clean();
    ob_end_flush();
  }

}