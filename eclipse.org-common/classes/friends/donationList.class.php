<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../../system/smartconnection.class.php"));
require_once("friendsContributionsList.class.php");

/**
 * Helper class to display donation information
 *
 * @todo: Clean up the code
 *  This code was mostly written by Eddie and I created a class to scope his work.
 *  The code is hard to understand and needs a bit of love.
 *
 * @author chrisguindon
 *
 */
class DonationList {

  private $App = NULL;

  private $table_prefix = "";

  private $test_mode = FALSE;

  public function __construct($test_mode = FALSE){
    $this->App = new App();
    if ($test_mode == TRUE){
      $this->test_mode = TRUE;
      $this->table_prefix = 'testing_';
    }
  }

  /**
   * Donor list for the sidebar
   *
   * @param number $_numrows
   * @param number $offset
   */
  public function sideDonorList($_numrows, $offset) {

    $friendsContributionsList = new FriendsContributionsList($this->test_mode);
    $friendsContributionsList->selectFriendsContributionsList($offset, $_numrows);

    $friend = new Friend($this->test_mode);
    $contribution = new Contribution($this->test_mode);
    $fcObject = new FriendsContributions($this->test_mode);

    $count = $friendsContributionsList->getCount();
    for ($i=0; $i < $count; $i++) {
      $fcObject = $friendsContributionsList->getItemAt($i);
      $friend = $fcObject->getFriendObject();
      $contribution = $fcObject->getContributionObject();
      $date = $contribution->getDateExpired();
      $date = strtotime($date);
      $date = strtotime("-1 year", $date);
      $now = strtotime("now");
      if ($date <= $now) {
        $anonymous = $friend->getIsAnonymous();
        if ($anonymous != 1) {
          $name = $friend->getFirstName() . " " . $friend->getLastName();
          $name = htmlentities($name);
        }
        else
          $name = "Anonymous";
          $benefit = $friend->getIsBenefit();
          $star = '<i class="fa fa-li fa-angle-double-right"></i> ';
          if ($benefit) {
            $star = '<i class="fa fa-li fa-star"></i> ';
          }
          $amount = $contribution->getAmount();
          echo "<li>" . $star .  $name . "-" . "$" . $amount . "</li>";
        }
    }
    echo "<div class=\"more\"><a href=\"donorlist.php\">Donor List</a></div>";
  }

  /**
   * Get HTML to display donor list table
   *
   * @param number $_numrows
   * @param number $offset
   * @param string $chevron
   * @param string $striped
   */
  function donorListTable($_numrows, $offset = 0, $chevron = TRUE, $striped = TRUE) {
    $return_html = "";
    if ($striped) $return_html .= "<table class='table table-striped'>";
    else $return_html .= "<table class='table'>";
    $chevron_html = '<i class="fa fa-li fa-chevron-circle-right orange" style="position: relative; left: 0px; width: 0px;"></i> ';

    $friendsContributionsList = new FriendsContributionsList($this->test_mode);
    $friendsContributionsList->selectFriendsContributionsList($offset, $_numrows);

    $friend = new Friend($this->test_mode);
    $contribution = new Contribution($this->test_mode);
    $fcObject = new FriendsContributions($this->test_mode);

    $count = $friendsContributionsList->getCount();
    for ($i=0; $i < $count; $i++) {
      $fcObject = $friendsContributionsList->getItemAt($i);
      $friend = $fcObject->getFriendObject();
      $contribution = $fcObject->getContributionObject();
      $anonymous = $friend->getIsAnonymous();
      $date = $contribution->getDateExpired();
      $date = strtotime($date);
      $date = strtotime("-1 year", $date);
      $now = strtotime("now");
      if ($date <= $now) {
        if ($anonymous != 1 && ($friend->getFirstName() != '' || $friend->getLastName() != '')) {
          $name = $friend->getFirstName() . " " . $friend->getLastName();
          $name = htmlentities($name);
        } else {
          $name = "Anonymous";
        }
        $amount = $contribution->getAmount();
        if ($chevron) $return_html .= "<tr><td>$chevron_html</td><td>$name-\$$amount</td></tr>";
        else $return_html .= "<tr><td>$name-\$$amount</td></tr>";
      }
    }
    $return_html .= "</table>";
    return $return_html;
  }

  /**
   * Get HTML for a display pager
   *
   * @param number $_start
   * @param number $_pageValue
   * @param number $_pageCount
   * @param string $_startParam
   * @param string $_anchor
   */
  public function displayPager($_start, $_pageValue, $_pageCount, $_startParam = 'start', $_anchor = '') {
      // Build URL
      $next = $_SERVER['PHP_SELF'] . '?' . $_startParam . '=' . ($_start + $_pageValue) . '' . $_anchor;
      $previous = $_SERVER['PHP_SELF'] . '?' . $_startParam . '=' . ($_start - $_pageValue) . '' . $_anchor;
    ob_start();
    ?>
    <table class="pager">
        <tr>
          <td style="text-align:left">
        <?php
          if ($_start >= $_pageValue) {
                      ?><a href="<?php print $previous;?>">&lt;&lt; Previous Page</a><?php
          }
        ?>&nbsp;</td>
          <td style="text-align:right">
        <?php
          if (($_start + $_pageValue) < $_pageCount) {
                      ?><a href="<?php print $next;?>">Next Page &gt;&gt;</a><?php
          }
        ?>
          </td>
        </tr>
      </table>
    <?php
    return ob_get_clean();
  }

  /**
   * Get HTML for donation table list
   *
   * @param array $contributions
   */
  public function displayTable($contributions) {
      echo '<table class="table table-hover table-condensed" cellspacing=0>' .
             '<tr class="donorHeader">' .
               '<td colspan="2" width="60%">Name and Message</td>' .
               '<td width="20%">Date</td>' .
               '<td width="20%" align="right">Amount</td>' .
             '</tr>';
      // Get total number of items so we can know whether to page or not.
      $friend = new Friend($this->test_mode);
      $contribution = new Contribution($this->test_mode);
      $fcObject = new FriendsContributions($this->test_mode);
      foreach ($contributions as $contribution) {
          $contrib = $contribution->getContributionObject();
          $friend = $contribution->getFriendObject();
          $anonymous = $friend->getIsAnonymous();
          if ($anonymous != 1 && ($friend->getFirstName() != '' || $friend->getLastName() != ''))
              $name = $friend->getFirstName() . " " . $friend->getLastName();
          else $name = "Anonymous";
          $benefit = $friend->getIsBenefit();
          if ($benefit != 0) $benefit = " <img width='25' src=\"images/stars.png\">";
          else $benefit = "";
          $amount = $contrib->getAmount();
          if (strpos($amount, ".") == 0) {
              $amount = $amount . ".00";
          }
          $currency = $contrib->getCurrency();
          $comment =  stripslashes(strip_tags($contrib->getMessage()));
          if (strlen($comment) > 80) {
              if (strpos($comment, ' ') == 0) {
                  $commentArray = str_split($comment, 80);
                  $comment = 0;
                  foreach ($commentArray as $value) {
                      $comment .= $value . " ";
                  }
              }
          }
          $currency = ($currency === 'USD') ? 'USD' : 'BTC';
          $date = strtotime("-1 year", strtotime($contrib->getDateExpired()));
          $now = strtotime("now");
          if ($date <= $now) {
              $date = date("Y-m-d", $date);
              echo '<tr class="donorRecord">' .
                   '<td width="25">' . $benefit . '</td>' .
                   '<td width="59%"><b>' . $name . '</b><br/>' . $comment . '</td>' .
                   '<td>' . $date . '</td>' .
                   '<td align="right">$' . $amount . '</td>' .
                   '</tr>';
          }
      }
      echo '</table>';
  }

  /**
   * Get gravatar URL
   *
   * @param string $email
   * @param number $size
   * @param string $default
   */
  public function getGravatarURL($email, $size = 80, $default = 'mm') {
    return "https://secure.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . $default . "&s=" . $size;
  }

  /**
   * Get html for displaying a Friend of Eclipse
   *
   * @param stdClass $friendsContributions
   * @param string $template
   */
  public function displayFriends($friendsContributions, $template = 'default') {
      $results = array();

      foreach ($friendsContributions as $contributions) {
        $result = array();
        $result['Friend'] = $contributions->getFriendObject();
        $name = ucwords(strtolower($result['Friend']->getFirstName())) . ' ' . ucwords(strtolower($result['Friend']->getLastName()));
        if ($name == " ") {
          $name = 'Anonymous';
        }
        $result['name'] = $name;
        $results[] = $result;
      }
      return $this->_get_donationlist_template($results, $template);
  }

  /**
   * Get Friend count
   *
   * @param string $get_anonymous
   * @param string $get_expired
   * @param number $donation_minimum
   * @return Ambigous <>
   */
  public function getFriendsCount($get_anonymous = TRUE, $get_expired = TRUE, $donation_minimum = 35) {
      $friends = array();
      $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . "friends_contributions as FC
              LEFT JOIN " . $this->table_prefix . "friends AS F ON FC.friend_id = F.friend_id WHERE FC.amount >= " . $this->App->sqlSanitize($donation_minimum);
      if (!$get_anonymous) $sql .= " AND F.is_anonymous = 0";
      if (!$get_expired) $sql .= " AND FC.date_expired > NOW()";
      $result = $this->App->eclipse_sql($sql);
      $row = mysql_fetch_row($result);
      return $row[0];
  }

  /**
   * Example for importing t-shirt code in the Eclipse database
   */
  public function importCode() {
    $codes_file = 'codes.csv';
    $con = mysqli_connect("dbmaster","user_here","password_here", "eclipse");

    $f = fopen($codes_file, 'r');
    while ($line = fgets($f)) {
      $line = trim($line);
      $line = trim($line, ',');
      $data = explode(',', $line);
      $data[2] = $this->_donationlist_fix_date($data[2]);
      $sql = 'INSERT INTO " . $this->table_prefix . "tshirts VALUES ("' . $data[0] . '", "' . $data[1] . '", \'' . $data[2] . '\', null, null, null)';
      mysqli_query($con,$sql);
    }
  }

  /**
   * Alter date for tshirts table
   *
   * @param string $date_str
   * @return string
   */
  private function _donationlist_fix_date($date_str) {
    $d = explode('/', $date_str);
    return $d[2] . "-" . $d[0] . "-" . $d[1];
  }

  /**
   * Get template for displaying a friend
   *
   * @param unknown $results
   * @param string $template
   */
  private function _get_donationlist_template($results, $template = 'default'){
    if ($template === 'four-columns') {
     $results = array_chunk($results, 10);
    }
    else {
      $rebuild = array();
      foreach($results as $r){
        $rebuild[] = array_merge($r, $this->_getLDAPinfo($r['Friend']));
      }
      $results = $rebuild;
    }

    ob_start();
    include('tpl/displayfriend-'. $template . '.tpl.php');
    return  ob_get_clean();
  }

  /**
   * Get LDAP info for a Friend
   * @param unknown $Friend
   */
  private function _getLDAPinfo($Friend){
   require_once("/home/data/httpd/eclipse-php-classes/system/ldapconnection.class.php");
   $ldap = new LDAPConnection();
   $result = array();
   $result['uid'] = $Friend->getLDAPUID();
   $result['mail'] = $Friend->getEmail();
   if ($result['uid'] && $result['mail'] != "") {
     $dn = $ldap->getDNFromUID($result['uid']);
     $result['mail'] = $ldap->getLDAPAttribute($dn, 'mail');
   }
   return $result;
  }
}