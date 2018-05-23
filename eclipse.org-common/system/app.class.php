<?php
/**
 * *****************************************************************************
 * Copyright (c) 2006-2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Denis Roy (Eclipse Foundation)- initial API and implementation
 * Karl Matthias (Eclipse Foundation) - Database access management
 * Christopher Guindon (Eclipse Foundation)
 * *****************************************************************************
 */

class App {

  /**
   * Version of App()
   *
   * @var string
   */
  private $APPVERSION = "1.0";

  /**
   * Name of App()
   *
   * @var string
   */
  private $APPNAME = "Eclipse.org";

  /**
   * Base path of eclipse.org-common
   *
   * @var string
   */
  private $eclipse_org_common_base_path = "";

  /**
   * Default row height
   *
   * @var int
   */
  private $DEFAULT_ROW_HEIGHT = 20;

  /**
   * Max post size
   *
   * @var int
   */
  private $POST_MAX_SIZE = 262144;

  /**
   * Our download URL
   *
   * @var string
   */
  private $OUR_DOWNLOAD_URL = "http://download1.eclipse.org";

  /**
   * Public download URL
   *
   * @var string
   */
  private $PUB_DOWNLOAD_URL = "http://download.eclipse.org";

  /**
   * Download base path
   *
   * @var string
   */
  private $DOWNLOAD_BASE_PATH = "/home/data2/httpd/download.eclipse.org";

  /**
   * Database class path
   * ends with '/'
   *
   * @var unknown
   */
  private $DB_CLASS_PATH = "/home/data/httpd/eclipse-php-classes/system/";

  /**
   * Current domain url
   *
   * @var string
   */
  private $WWW_PREFIX = ""; // default is relative

  /**
   * Http prefix
   *
   * @var string
   */
  private $HTTP_PREFIX = "http"; // default is http

  /**
   * String to include in <head>
   *
   * @var string
   */
  public $ExtraHtmlHeaders = "";

  /**
   * JS to include in footer
   *
   * @var string
   */
  public $ExtraJSFooter = "";

  /**
   * Page RSS feed Url
   *
   * @var string
   */
  public $PageRSS = "";

  /**
   * Page RSS feed TITLE
   *
   * @var string
   */
  public $PageRSSTitle = "";

  /**
   * Promotion flag
   *
   * @var bool
   */
  public $Promotion = FALSE;

  /**
   * Custom Promotion Path
   *
   * @var string
   */
  public $CustomPromotionPath = "";

  /**
   * Valid eclipse.org-common themes
   *
   * @var array
   */
  private $valid_themes = array(
    "polarsys",
    "locationtech",
    "solstice"
  );

  /**
   * Open Graph Protocol title
   *
   * @var string
   */
  private $OGTitle = "";

  /**
   * Open Graph Protocol description
   *
   * @var string
   */
  private $OGDescription = "The Eclipse Foundation - home to a global community, the Eclipse IDE, Jakarta EE and over 350 open source projects, including runtimes, tools and frameworks.";

  /**
   * Open Graph Protocol image
   *
   * @var string
   */
  private $OGImage = "https://www.eclipse.org/eclipse.org-common/themes/solstice/public/images/logo/eclipse-foundation-200x200.png";

  /**
   * Open Graph Protocol image width
   *
   * @var string
   */
  private $OGImageWidth = "200";

  /**
   * Open Graph Protocol image height
   *
   * @var string
   */
  private $OGImageHeight = "200";

  /**
   * Doctype
   *
   * @var string
   */
  private $doctype = '';


  /**
   * Variables to customize solstice
   *
   * @var unknown
   */
  private $theme_variables = array();

  /**
   * Disable all database operations
   *
   * @var unknown
   */
  private $DB_READ_ONLY = FALSE;

  /**
   * Database config and handle cache
   *
   * @var unknown
   */
  private $databases;

  /**
   * "Deprecated" flag
   *
   * @var bool
   */
  private $OutDated = FALSE;

  /**
   * "Deprecated" message
   *
   * @var string
   */
  private $OutDatedMsg = "";

  /**
   * Development mode flag
   *
   * Flag to determine whether this is development mode or not (for databases)
   *
   * @var bool
   */
  public $devmode = FALSE;

  /**
   * Flag to log SQL even on production systems
   *
   * @var bool
   */
  public $logsql = FALSE;

  /**
   * Arbitrary storage hash
   *
   * @var unknown
   */
  private $hash;

  /**
   * SQL Backtrace storage
   *
   * @var unknown
   */
  public $query_btrace = array();

  /**
   * Messages variable
   */
  private $Messages = NULL;

  // Default constructor
  function __construct() {

    $this->databases = array();

    // Figure out if we're in devmode by whether the classes are installed or
    // not
    if (!file_exists($this->DB_CLASS_PATH)) {
      $this->devmode = TRUE;
    }

    // Configure databases (not connected)
    $this->configureDatabases();

    // Make it easy to override database and other settings (don't check
    // app-config.php in to CVS!)
    if ($this->devmode) {
      if (file_exists(getcwd() . '/app-config.php')) {
        include_once (getcwd() . '/app-config.php');
        // We call a function inside app-config.php and pass it a reference to
        // ourselves because
        // this class is still in the constructor and might not be available
        // externally by name.
        // File just contains a function called app_config() which is called.
        // Nothing more is needed.
        app_config($this);
      }
      else
        if (file_exists($this->getBasePath() . '/system/app-config.php')) {
          include_once ($this->getBasePath() . '/system/app-config.php');
          app_config($this);
        }
    }

    // Set server timezone
    date_default_timezone_set("America/Montreal");
  }

  /**
   * Encodes special characters in a plain-text string for display as HTML.
   *
   * @param string $text
   * @return string
   */
  function checkPlain($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
  }

  /**
   * Returns the Webmaster object
   *
   * @return object
   */
  public function getWebmaster($action = '') {
    switch ($action) {
      case "webmaster":
        require_once ($this->getBasePath() . "/classes/webmaster/webmaster.class.php");
        return new Webmaster($this);
        break;
      case "mirrors":
        require_once ($this->getBasePath() . "/classes/webmaster/mirrors.class.php");
        return new Mirrors($this);
        break;
      case "jobs":
        require_once ($this->getBasePath() . "/classes/webmaster/jobs.class.php");
        return new Jobs($this);
        break;
      case "committers":
        require_once ($this->getBasePath() . "/classes/webmaster/committers.class.php");
        return new Committers($this);
        break;
      case "firewall":
        require_once ($this->getBasePath() . "/classes/webmaster/firewall.class.php");
        return new Firewall($this);
        break;
      case "mailinglists":
        require_once ($this->getBasePath() . "/classes/webmaster/mailinglists.class.php");
        return new MailingLists($this);
        break;
    }
  }

  /**
   * Returns the Subscriptions object
   *
   * @return object
   */
  public function getSubscriptions() {
    require_once ($this->getBasePath() . "/classes/subscriptions/subscriptions.class.php");
    return new Subscriptions($this);
  }

  /**
   * Returns the CLA object
   *
   * @return object
   */
  public function getCla() {
    require_once ($this->getBasePath() . "/classes/users/cla.class.php");
    return new Cla($this);
  }

  /**
   * This function sets System Messages
   *
   * @param $name string
   *        containing the name of the message
   * @param $msg string
   *        containing the message itself
   * @param $type string
   *        containing the type of the message
   *
   */
  public function setSystemMessage($name, $msg, $type) {
    if (get_class($this->Messages) !== 'Messages') {
      require_once ($this->getBasePath() . "/system/messages.class.php");
      $this->Messages = new Messages();
    }
    $this->Messages->setMessages($name, $msg, $type);
  }

  /**
   * Return system messages
   *
   * @return array
   */
  public function getSystemMessage() {
    if (get_class($this->Messages) !== 'Messages') {
      require_once ($this->getBasePath() . "/system/messages.class.php");
      $this->Messages = new Messages();
    }
    return $this->Messages->getMessages();
  }

  function getAppVersion() {
    return $this->APPVERSION;
  }

  /**
   * Get eclipse.org-common base path
   *
   * Use this function for includes/requires.
   */
  public function getBasePath() {
    if (empty($this->eclipse_org_common_base_path)) {
      $this->eclipse_org_common_base_path = realpath(dirname(__FILE__) . '/../');
    }

    return $this->eclipse_org_common_base_path;
  }

  /**
   * Get header path
   *
   * @param string $_theme
   * @return string
   */
  function getHeaderPath($_theme) {
    return $this->getBasePath() . "/themes/" . $_theme . "/header.php";
  }

  /**
   * Get menu path
   *
   * @param string $_theme
   *
   * @return string
   */
  function getMenuPath($_theme) {
    return $this->getBasePath() . "/themes/" . $_theme . "/menu.php";
  }

  /**
   * Get template nav path
   *
   * @param string $_theme
   *
   * @return string
   */
  function getNavPath($_theme) {
    return $this->getBasePath() . "/themes/" . $_theme . "/nav.php";
  }

  /**
   * Get template footer path
   *
   * @param string $_theme
   * @return string
   */
  function getFooterPath($_theme) {
    return $this->getBasePath() . "/themes/" . $_theme . "/footer.php";
  }

  /**
   * Get Eclipse promo path
   *
   * @param string $_theme
   * @return string
   */
  function getPromotionPath($_theme) {
    return $_SERVER["DOCUMENT_ROOT"] . "/home/promotions/promotion.php";
  }

  /**
   * Get eclipse.org cookie domain and prefix
   *
   * Based off the current environment.
   *
   * @return array
   */
  public function getEclipseDomain(){
    if (!class_exists('EclipseEnv')) {
      require_once('eclipseenv.class.php');
    }
    $EclipseEnv = new EclipseEnv($this);
    return $EclipseEnv->getEclipseEnv();
  }

  /**
   * Get $APPNAME
   *
   * @return string
   */
  function getAppName() {
    return $this->APPNAME;
  }

  /**
   * Get $POST_MAX_SIZE
   *
   * @return number
   */
  function getPostMaxSize() {
    return $this->POST_MAX_SIZE;
  }

  /**
   * Get $DB_READ_ONLY
   *
   * @return boolean
   */
  function getDBReadOnly() {
    return $this->DB_READ_ONLY;
  }

  /**
   * Set XML header
   *
   * @return string
   */
  function sendXMLHeader() {
    header("Content-type: text/xml");
  }

  /**
   * Get $DEFAULT_ROW_HEIGHT
   *
   * @return number
   */
  function getDefaultRowHeight() {
    return $this->DEFAULT_ROW_HEIGHT;
  }

  /**
   * Get $OUR_DOWNLOAD_URL
   *
   * @return string
   */
  function getOurDownloadServerUrl() {
    return $this->OUR_DOWNLOAD_URL;
  }

  /**
   * Get $DOWNLOAD_BASE_PATH
   *
   * @return string
   */
  function getDownloadBasePath() {
    return $this->DOWNLOAD_BASE_PATH;
  }

  /**
   * Get $PUB_DOWNLOAD_URL
   *
   * @return string
   */
  function getPubDownloadServerUrl() {
    return $this->PUB_DOWNLOAD_URL;
  }

  /**
   * Get $WWW_PREFIX
   *
   * @return string|unknown
   */
  function getWWWPrefix() {
    // Set value for WWW_PREFIX
    if (empty($this->WWW_PREFIX)) {
      $valid_domains = array(
        'www.eclipse.org',
        'eclipse.org',
        'staging.eclipse.org',
      );

      $http_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
      // Force http://www.eclipse.org if the serve_name is not whitelisted.
      if (in_array($_SERVER['SERVER_NAME'], $valid_domains) || strpos($_SERVER['SERVER_NAME'], '.dev.docker')) {
        $this->WWW_PREFIX = $http_protocol;
        $this->WWW_PREFIX .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
      }
      else {
        $this->WWW_PREFIX = $http_protocol . 'www.eclipse.org';
      }
    }
    return $this->WWW_PREFIX;
  }

  /**
   * Get HTTP Prefix
   * Return https if $_SERVER['HTTPS'] exist otherwise this function
   * returns http.
   *
   * @return : a string
   */
  function getHTTPPrefix() {
    $protocol = $this->HTTP_PREFIX;
    if (isset($_SERVER['HTTPS'])) {
      if ($_SERVER['HTTPS']) {
        $protocol = "https";
      }
    }
    $this->HTTP_PREFIX = $protocol;
    return $this->HTTP_PREFIX;
  }

  /**
   * Get the language of the User
   *
   * @return : String
   *         Check the browser's default language and return
   *         2006-06-28: droy
   */
  function getUserLanguage() {

    $validLanguages = array(
      'en',
      'de',
      'fr'
    );

    $defaultLanguage = "en";

    // get the default browser language (first one reported)
    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

    if (array_search($language, $validLanguages)) {
      return $language;
    }
    else {
      return $defaultLanguage;
    }
  }

  /**
   * Get Local Content file
   *
   * Return the content/xx_filename.php filename,
   * according to availability of the file
   *
   * @return : String
   *
   *         2006-06-28: droy
   */
  function getLocalizedContentFilename() {
    $language = $this->getUserLanguage();
    $filename = "content/" . $language . "_" . $this->getScriptName();

    if (!file_exists($filename)) {
      $filename = "content/en_" . $this->getScriptName();
    }

    return $filename;
  }

  /**
   * Get current script name
   *
   * @return string
   */
  function getScriptName() {
    // returns only the filename portion of a script
    return substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
  }

  /**
   * Find the closest _projectCommon.php file
   *
   * Walk up the directory structure to find the closest
   * _projectCommon.php file.
   *
   * @return : String
   */
  function getProjectCommon() {
    $currentScript = $_SERVER['SCRIPT_FILENAME'];
    $strLen = strlen($currentScript);
    $found = false;
    $antiLooper = 0;

    // default to /home/_projectCommon.php
    $rValue = $_SERVER['DOCUMENT_ROOT'] . "/home/_projectCommon.php";

    while ($strLen > 1 && !$found) {
      $currentScript = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($currentScript, "/"));
      $testPath = $currentScript . "/_projectCommon.php";

      if (file_exists($testPath)) {
        $found = true;
        $rValue = $testPath;
      }
      $strLen = strlen($currentScript);

      // break free from endless loops
      $antiLooper++;
      if ($antiLooper > 20) {
        $found = true;
      }
    }
    return $rValue;
  }

  /**
   * Getting the IP address of the user
   *
   * @return string
   */
  function getRemoteIPAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    }
    else
      if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
      }
    return $_SERVER['REMOTE_ADDR'];
  }

  /**
   * Getting the subnet the user is in right now.
   *
   * @return string
   */
  function getSubnet() {
    $ip_address = $this->getRemoteIPAddress();
    $subnet = preg_replace('~(\d+)\.(\d+)\.(\d+)\.(\d+)~', "$1.$2.$3", $ip_address);
    return $subnet;
  }

  /**
   * This function look for the word staging in the URL
   *
   * @return boolean
   */
  function is_staging() {
    if (strpos($_SERVER['HTTP_HOST'], 'staging')) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Sets the headers to prevent caching for the different browsers.
   *
   * @deprecated Please use preventCaching() instead.
   */
  function runStdWebAppCacheable() {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    $this->preventCaching();
  }

  /**
   * Sets the headers to prevent caching for the different browsers.
   */
  function preventCaching() {
    header("Cache-Control: no-store, no-cache, private, must-revalidate, max-age=0, max-stale=0");
    header("Cache-Control: post-check=0, pre-check=0", FALSE);
    header("Pragma: no-cache");
    header("Expires: 0");
  }

  /**
   * Get Alpha code
   *
   * @param unknown $_NumChars
   * @return string
   */
  function getAlphaCode($_NumChars) {
    // Accept: int - number of chars
    // return: string - random alphanumeric code

    // Generate alpha code
    $addstring = "";
    for ($i = 1; $i <= $_NumChars; $i++) {
      if (rand(0, 1) == 1) {
        // generate character
        $addstring = $addstring . chr(rand(0, 5) + 97);
      }
      else {
        $addstring = $addstring . rand(0, 9);
      }
    }
    return $addstring;
  }

  /**
   * Get the current date
   *
   * @return string
   */
  function getCURDATE() {
    return date("Y-m-d");
  }

  /**
   * Add " OR " if string is not empty
   *
   * @param unknown $_String
   *
   * @return string
   */
  function addOrIfNotNull($_String) {
    // Accept: String - String to be AND'ed
    // return: string - AND'ed String

    if ($_String != "") {
      $_String = $_String . " OR ";
    }

    return $_String;
  }

  /**
   * Add " AND " if string is not empty
   *
   * @param unknown $_String
   *
   * @return string
   */
  function addAndIfNotNull($_String) {
    // Accept: String - String to be AND'ed
    // return: string - AND'ed String

    if ($_String != "") {
      $_String = $_String . " AND ";
    }

    return $_String;
  }

  /**
   * Get random numeric Code
   *
   * @param string $_NumChars
   * @return string
   */
  function getNumCode($_NumChars) {
    // Accept: int - number of chars
    // return: int - random numeric code

    // Generate code
    $addstring = "";
    for ($i = 1; $i <= $_NumChars; $i++) {
      if ($i > 1) {
        // generate first digit
        $addstring = $addstring . rand(1, 9);
      }
      else {
        $addstring = $addstring . rand(0, 9);
      }
    }
    return $addstring;
  }

  /**
   * Replace part of string X number of times
   *
   * @param unknown $find
   * @param unknown $replace
   * @param unknown $subject
   * @param unknown $count
   *
   * @return string|unknown
   */
  function str_replace_count($find, $replace, $subject, $count) {
    // Replaces $find with $replace in $subnect $count times only

    $nC = 0;

    $subjectnew = $subject;
    $pos = strpos($subject, $find);
    if ($pos !== FALSE) {
      while ($pos !== FALSE) {
        $nC++;
        $temp = substr($subjectnew, $pos + strlen($find));
        $subjectnew = substr($subjectnew, 0, $pos) . $replace . $temp;
        if ($nC >= $count) {
          break;
        }
        $pos = strpos($subjectnew, $find);
      }
    }
    return $subjectnew;
  }

  /**
   * Return Quoated String
   *
   * @param string $_String
   *
   * @return string
   */
  function returnQuotedString($_String) {
    // Accept: String - String to be quoted
    // return: string - Quoted String

    // replace " with '
    // $_String = str_replace('"', "'", $_String);
    // https://bugs.eclipse.org/bugs/show_bug.cgi?id=299682#c1
    $_String = addslashes($_String);

    return "\"" . $_String . "\"";
  }

  /**
   * Return Safe HTML string
   *
   * @param string $_String
   * @return string
   */
  function returnHTMLSafeString($_String) {
    // Accept: String - String to be HTMLSafified
    // return: string

    // replace " with '
    $_String = str_replace('<', "&lt;", $_String);
    $_String = str_replace('<', "&gt;", $_String);
    $_String = str_replace("\n", "<br />", $_String);

    return $_String;
  }

  /**
   * Return safe JS string
   *
   * @param string $_String
   *
   * @return string
   */
  function returnJSSAfeString($_String) {
    // Accept: String - String to be quoted
    // return: string - Quoted String

    // replace " with '
    $_String = str_replace("'", "\\'", $_String);

    return $_String;
  }

  /**
   * Replace Enter with <br/>
   *
   * @param string $_String
   *
   * @return string
   */
  function replaceEnterWithBR($_String) {
    return str_replace("\n", "<br />", $_String);
  }

  /**
   * Generate HTML page
   *
   * @param unknown $theme
   * @param unknown $Menu
   * @param unknown $Nav
   * @param unknown $pageAuthor
   * @param unknown $pageKeywords
   * @param unknown $pageTitle
   * @param unknown $html
   * @param unknown $Breadcrumb
   */
  function generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html, $Breadcrumb = NULL) {
    $Theme = $this->getThemeClass($theme);
    $Theme->setNav($Nav);
    $Theme->setMenu($Menu);
    $Theme->setPageAuthor($pageAuthor);
    $Theme->setPageKeywords($pageKeywords);
    $Theme->setPageTitle($pageTitle);
    $Theme->setHtml($html);
    $Theme->setBreadcrumb($Breadcrumb);
    $Theme->generatePage();
  }

  /**
   * Add addionnal elements in <head>
   *
   * @param unknown $string
   */
  function AddExtraHtmlHeader($string) {
    $this->ExtraHtmlHeaders .= $string;
  }

  /**
   * Add Extra Javascript in the footer
   *
   * @param string $string
   */
  function AddExtraJSFooter($string) {
    $this->ExtraJSFooter .= $string;
  }

  /**
   * Get Theme URL
   *
   * @param unknown $_theme
   * @return string
   */
  function getThemeURL($_theme) {
    $_theme = strtolower($_theme);
    if (!$this->isValidTheme($_theme)) {
      $_theme = "solstice";
    }
    return "/eclipse.org-common/themes/" . $_theme;
  }

  /**
   * Get Theme Class
   *
   * @param strinb $_theme
   *
   * @return BaseTheme
   */
  function getThemeClass($_theme = "quicksilver") {
    $themes = array(
      'locationtech',
      'eclipse_ide',
      'polarsys',
      'quicksilver',
      'jakarta',
      'nova'
    );

    $_theme = strtolower($_theme);

    if ($_theme === 'solstice' || $_theme === 'nova') {
      $_theme = 'eclipse_ide';
    }

    if (!in_array($_theme, $themes)) {
      $_theme = "quicksilver";
    }

    require_once (realpath(dirname(__FILE__) . '/../classes/themes/' . $_theme . '.class.php'));
    $theme = ucfirst($_theme);
    return new $theme($this);
  }

  /**
   * Get HTTP parameter
   *
   * @author droy
   * @since version - Oct 19, 2006
   * @param String $_param_name
   *        name of the HTTP GET/POST parameter
   * @param string $_method
   *        GET or POST, or the empty string for POST,GET order
   * @return string HTTP GET/POST parameter value, or the empty string
   *         Fetch the HTTP parameter
   */
  function getHTTPParameter($_param_name, $_method = "") {

    $rValue = "";
    $_method = strtoupper($_method);

    // Always fetch the GET VALUE, override with POST unless a GET was
    // specifically requested
    if (isset($_GET[$_param_name])) {
      $rValue = $_GET[$_param_name];
    }
    if (isset($_FILES[$_param_name]) && $_method == "FILES") {
      $rValue = $_FILES[$_param_name];
    }
    if ((isset($_POST[$_param_name])) && ($_method != "GET" || $_method != "FILES")) {
      $rValue = $_POST[$_param_name];
    }
    return $rValue;
  }

  /**
   * Get client OS
   *
   * @return string|unknown
   */
  function getClientOS() {

    $UserAgent = $_SERVER['HTTP_USER_AGENT'];

    $regex_windows = '/([^dar]win[dows]*)[\s]?([0-9a-z]*)[\w\s]?([a-z0-9.]*)/i';
    $regex_mac = '/(68[k0]{1,3})|(mac os x)|(darwin)/i';
    $regex_os2 = '/os\/2|ibm-webexplorer/i';
    $regex_sunos = '/(sun|i86)[os\s]*([0-9]*)/i';
    $regex_irix = '/(irix)[\s]*([0-9]*)/i';
    $regex_hpux = '/(hp-ux)[\s]*([0-9]*)/i';
    $regex_aix = '/aix([0-9]*)/i';
    $regex_dec = '/dec|osfl|alphaserver|ultrix|alphastation/i';
    $regex_vms = '/vax|openvms/i';
    $regex_sco = '/sco|unix_sv/i';
    $regex_linux = '/x11|inux/i';
    $regex_bsd = '/(free)?(bsd)/i';
    $regex_amiga = '/amiga[os]?/i';
    $regex_ppc = '/ppc/i';

    $regex_x86_64 = "/x86_64/i";

    // look for Windows Box
    if (preg_match_all($regex_windows, $UserAgent, $match)) {

      $v = $match[2][count($match[0]) - 1];
      $v2 = $match[3][count($match[0]) - 1];

      // Establish NT 6.0 as Vista
      if (stristr($v, 'NT') && $v2 == 6.0)
        $v = 'win32';

        // Establish NT 5.1 as Windows XP
      elseif (stristr($v, 'NT') && $v2 == 5.1)
        $v = 'win32';

        // Establish NT 5.0 and Windows 2000 as win2k
      elseif ($v == '2000')
        $v = '2k';
      elseif (stristr($v, 'NT') && $v2 == 5.0)
        $v = 'win32';

        // Establish 9x 4.90 as Windows 98
      elseif (stristr($v, '9x') && $v2 == 4.9)
        $v = 'win32';
        // See if we're running windows 3.1
      elseif ($v . $v2 == '16bit')
        $v = 'win16';
        // otherwise display as is (31,95,98,NT,ME,XP)
      else
        $v .= $v2;
        // update browser info container array
      if (empty($v))
        $v = 'win32';
      return (strtolower($v));
    }

    // look for amiga OS
    elseif (preg_match($regex_amiga, $UserAgent, $match)) {
      if (stristr($UserAgent, 'morphos')) {
        // checking for MorphOS
        return ('morphos');
      }
    }
    elseif (stristr($UserAgent, 'mc680x0')) {
      // checking for MC680x0
      return ('mc680x0');
    }
    elseif (preg_match('/(AmigaOS [\.1-9]?)/i', $UserAgent, $match)) {
      // checking for AmigaOS version string
      return ($match[1]);
    }
    // look for OS2
    elseif (preg_match($regex_os2, $UserAgent)) {
      return ('os2');
    }
    // look for mac
    // sets: platform = mac ; os = 68k or ppc
    elseif (preg_match($regex_mac, $UserAgent, $match)) {
      $os = !empty($match[1]) ? 'mac68k' : '';
      $os = !empty($match[2]) ? 'macosx' : $os;
      $os = !empty($match[3]) ? 'macppc' : $os;
      $os = !empty($match[4]) ? 'macosx' : $os;
      return ('macosx');
    }
    // look for *nix boxes
    // sunos sets: platform = *nix ; os = sun|sun4|sun5|suni86
    elseif (preg_match($regex_sunos, $UserAgent, $match)) {
      if (!stristr('sun', $match[1]))
        $match[1] = 'sun' . $match[1];
      return ('solaris');
    }
    // irix sets: platform = *nix ; os = irix|irix5|irix6|...
    elseif (preg_match($regex_irix, $UserAgent, $match)) {
      return ($match[1] . $match[2]);
    }
    // hp-ux sets: platform = *nix ; os = hpux9|hpux10|...
    elseif (preg_match($regex_hpux, $UserAgent, $match)) {
      $match[1] = str_replace('-', '', $match[1]);
      $match[2] = (int) $match[2];
      return ('hpux');
    }
    // aix sets: platform = *nix ; os = aix|aix1|aix2|aix3|...
    elseif (preg_match($regex_aix, $UserAgent, $match)) {
      return ('aix');
    }
    // dec sets: platform = *nix ; os = dec
    elseif (preg_match($regex_dec, $UserAgent, $match)) {
      return ('dec');
    }
    // vms sets: platform = *nix ; os = vms
    elseif (preg_match($regex_vms, $UserAgent, $match)) {
      return ('vms');
    }
    // dec sets: platform = *nix ; os = dec
    elseif (preg_match($regex_dec, $UserAgent, $match)) {
      return ('dec');
    }
    // vms sets: platform = *nix ; os = vms
    elseif (preg_match($regex_vms, $UserAgent, $match)) {
      return ('vms');
    }
    // sco sets: platform = *nix ; os = sco
    elseif (preg_match($regex_sco, $UserAgent, $match)) {
      return ('sco');
    }
    // unixware sets: platform = *nix ; os = unixware
    elseif (stristr($UserAgent, 'unix_system_v')) {
      return ('unixware');
    }
    // mpras sets: platform = *nix ; os = mpras
    elseif (stristr($UserAgent, 'ncr')) {
      return ('mpras');
    }
    // reliant sets: platform = *nix ; os = reliant
    elseif (stristr($UserAgent, 'reliantunix')) {
      return ('reliant');
    }
    // sinix sets: platform = *nix ; os = sinix
    elseif (stristr($UserAgent, 'sinix')) {
      return ('sinix');
    }
    // bsd sets: platform = *nix ; os = bsd|freebsd
    elseif (preg_match($regex_bsd, $UserAgent, $match)) {
      return ($match[1] . $match[2]);
    }
    // last one to look for
    // linux sets: platform = *nix ; os = linux
    elseif (preg_match($regex_linux, $UserAgent, $match)) {

      if (preg_match($regex_x86_64, $UserAgent, $match)) {
        return "linux-x64";
      }
      elseif (preg_match($regex_ppc, $UserAgent, $match)) {
        return "linux-ppc";
      }
      else {
        return ('linux');
      }
    }
  }

  /**
   * Returns true if supplied theme is in the array of valid themes
   *
   * 2005-12-07: droy
   *
   * @return : bool
   */
  function isValidTheme($_theme) {
    return in_array($_theme, $this->valid_themes);
  }

  /**
   * Returns theme name in a browser cookie, or the Empty String
   *
   * @deprecated 2005-12-07: droy
   * @return : String
   */
  function getUserPreferedTheme() {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    return "";
  }

  /**
   * Get Facebook like button
   *
   * @param
   *        layout string Button layout (standard, condensed)
   * @param
   *        showfaces bool
   *
   * @author droy
   * @since 2011-05-18
   * @return : HTML string for facebook like
   *         Generate HTML string for facebook like button
   */
  function getFacebookLikeButtonHTML($_layout = "standard", $_showfaces = false) {

    $width = 450;
    $height = 22;

    if ($_layout == "condensed") {
      $width = 90;
      $_layout = "button_count";
    }
    else {
      $_layout = "standard";
    }

    if ($_showfaces) {
      $height = 82;
    }
    $str = "<iframe src='//www.facebook.com/plugins/like.php?href=" . $this->getCurrentURL() . "&layout=" . $_layout . "&" . ($_showfaces ? "show_faces=true" : "") . "&width=$width&action=like' style='border: medium none; overflow: hidden; width: " . $width . "px; height: " . $height . "px;' frameborder='0' scrolling='no'></iframe>";
    return $str;
  }

  /**
   * Get current URL
   *
   * @author droy
   * @since 2011-05-18
   * @return string URL of the current PHP page
   */
  function getCurrentURL() {
    return "http" . ((empty($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] != 443) ? "" : "s") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  }

  /**
   * Return Poll()
   */
  function usePolls() {
    require_once ($this->getBasePath() . "/classes/polls/poll.php");
  }

  /**
   * Return Services_JSON()
   */
  function useJSON() {
    require_once ($this->getBasePath() . "/json/JSON.php");
  }

  /**
   * Returns a new REST client
   *
   * @return RestClient
   * @since 2015-06-24
   * @author droy
   */
  function RESTClient() {
    require_once ($this->getBasePath() . "/classes/rest/lib/restclient.class.php");
    return new RestClient();
  }

  /**
   * Return projectInfoList()
   */
  function useProjectInfo() {
    require_once ($this->getBasePath() . "/classes/projects/projectInfoList.class.php");
  }

  /**
   * This function applies standard formatting to a date.
   *
   * The first parameter is either a string or a number representing a date.
   * If it's a string, it must be in a format that is parseable by the
   * strtotime() function. If it is a number, it must be an integer representing
   * a UNIX timestamp (number of seconds since January 1 1970 00:00:00 GMT)
   * which, conveniently, is the output of the strtotime() function.
   * The second (optional) parameter is the format for the result. This must
   * one of 'short', or 'long'.
   */
  function getFormattedDate($date, $format = 'long') {
    if (is_string($date))
      $date = strtotime($date);
    switch ($format) {
      case 'long':
        return date("F j, Y", $date);
      case 'short':
        return date("d M y", $date);
    }
  }

  /**
   * Standard formatting to a date range
   *
   * This function applies standard formatting to a date range.
   * See the comments for getFormattedDate($date, $format) for information
   * concerning what's expected in the parameters of this method).
   */
  function getFormattedDateRange($start_date, $end_date, $format) {
    if (is_string($start_date))
      $start_date = strtotime($start_date);
    if (is_string($end_date))
      $end_date = strtotime($end_date);
    switch ($format) {
      case 'long':
        if ($this->same_year($start_date, $end_date)) {
          if ($this->same_month($start_date, $end_date)) {
            return date("F", $start_date) . date(" d", $start_date) . date("-d, Y", $end_date);
          }
          else {
            return date("F d", $start_date) . date("-F d, Y", $end_date);
          }
        }
        else {
          return date("F d, Y", $start_date) . date("-F d, Y", $end_date);
        }
      case 'short':
        if ($this->same_year($start_date, $end_date)) {
          if ($this->same_month($start_date, $end_date)) {
            return date("d", $start_date) . date("-d", $end_date) . date(" M", $start_date) . date(" y", $end_date);
          }
          else {
            return date("d M", $start_date) . date("-d M y", $end_date);
          }
        }
        else {
          return date("d M y", $start_date) . date("-d M y", $end_date);
        }
    }
  }

  /**
   * Validate if dates are the same year
   *
   * This method answers true if the two provided values represent
   * dates that occur in the same year.
   */
  function same_year($a, $b) {
    return date("Y", $a) == date("Y", $b);
  }

  /**
   * Validate if dates are the same month
   *
   * This method answers true if the two provided values represent
   * dates that occur in the same month.
   */
  function same_month($a, $b) {
    return date("F", $a) == date("F", $b);
  }

  /**
   * Returns a string representing the size of a file in the downloads area
   *
   * @author droy
   * @since Jun 7, 2007
   *
   * @param string $_file
   *        File name relative to http://download.eclipse.org (the
   *        &file= parameter used)
   *
   * @return string Returns a string in the format of XX MB
   */
  function getDownloadFileSizeString($_file) {
    $fileSize = "N/A";
    $filesizebytes = filesize($this->getDownloadBasePath() . $_file);
    if ($filesizebytes > 0) {
      $fileSize = floor($filesizebytes / 1048576) . " MB";
    }
    return $fileSize;
  }

  /**
   * useSession(String) - use auth sessions
   *
   * @author droy
   * @since Jun 7, 2007
   *
   * @param string $required
   *        optional' or 'required'
   *
   * @return Session object
   */
  function useSession($required = "") {
    require_once ($this->getBasePath() . "/system/session.class.php");
    $ssn = new Session(); // constructor calls validate
    if ($ssn->getGID() == "" && $required == "required") {
      $ssn->redirectToLogin();
    }
    return $ssn;
  }

  /**
   * Validate if valid caller
   *
   * @param unknown $_pathArray
   *
   * @return boolean
   */
  function isValidCaller($_pathArray) {
    $a = debug_backtrace();
    $caller = $a[1]['file']; // Caller 0 is the class that called App();
    $validCaller = false;
    for ($i = 0; $i < count($_pathArray); $i++) {
      // TODO: use regexp's to match the leftmost portion for better security
      if (strstr($caller, $_pathArray[$i])) {
        $validCaller = true;
        break;
      }
    }
    return $validCaller;
  }

  /**
   * Sanitize incoming value to prevent SQL injections
   *
   * @param
   *        string
   *        Value to sanitize
   * @param
   *        dbh database
   *        Resource to use
   *
   * @return string santized string
   */
  function sqlSanitize($_value, $_dbh = NULL) {
    if ($_dbh == NULL) {
      $_dbh = $this->database("eclipse", "");
    }
    $_value = mysql_real_escape_string($_value, $_dbh);
    return $_value;
  }

  /**
   * Get $OGTitle output
   *
   * @return string
   */
  function getOGTitle() {
    return $this->OGTitle;
  }

  /**
   * Set $OGTitle
   *
   * @param unknown $title
   */
  function setOGTitle($title) {
    $this->OGTitle = $title;
  }

  /**
   * Get $OGDescription output
   *
   * @return string
   */
  function getOGDescription() {
    return $this->OGDescription;
  }

  /**
   * Set $OGDescription
   *
   * @param unknown $description
   */
  function setOGDescription($description) {
    $this->OGDescription = $description;
  }

  /**
   * Get $OGImage output
   *
   * @return string
   */
  function getOGImage() {
    return $this->OGImage;
  }

  /**
   * Set $OGImage
   *
   * @param unknown $image
   */
  function setOGImage($image) {
    $this->OGImage = $image;
  }

  /**
   * Get $OGImageWidth output
   */
  function getOGImageWidth() {
    return $this->OGImageWidth;
  }

  /**
   * Set $OGImageWidth
   *
   * @param string $width
   */
  function setOGImageWidth($width) {
    if (is_numeric($width)) {
      $this->OGImageWidth = $width;
    }
  }

  /**
   * Get $OGImageHeight output
   */
  function getOGImageHeight() {
    return $this->OGImageHeight;
  }

  /**
   * Set $OGImageHeight
   *
   * @param string $height
   */
  function setOGImageHeight($height) {
  if (is_numeric($height)) {
      $this->OGImageHeight = $height;
    }
  }

  /**
   * Set Doctype
   *
   * @param unknown $doctype
   */
  function setDoctype($doctype) {
    $accepted = array(
      'html5',
      'xhtml'
    );
    if (in_array($doctype, $accepted)) {
      $this->doctype = $doctype;
    }
    return;
  }

  /**
   * Get HTML document doctype
   *
   * @return string
   */
  function getDoctype() {
    $doc = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">';
    switch ($this->doctype) {
      case 'html5':
        $doc = '<!DOCTYPE html>
<html>';
        break;
    }
    return $doc;
  }

  /**
   * Get theme Variables
   *
   * Fetch solstice custom variables
   *
   * @return array
   */
  public function getThemeVariables() {
    $v = $this->theme_variables;
    // Set default variables for all themes.
    if (empty($v)) {
      $v['body_classes'] = '';
      $v['breadcrumbs_html'] = "";
      $v['hide_breadcrumbs'] = FALSE;
      $v['leftnav_html'] = '';
      $v['main_container_classes'] = 'container';
      $v['main_container_html'] = '';
      $v['header_nav'] = array();
      $v['btn_cfa'] = array();
      $this->theme_variables = $v;
    }

    return $this->theme_variables;
  }

  /**
   * Set theme Variables
   *
   * This function allow pages to pass extra
   * parameters to the solstice theme.
   */
  public function setThemeVariables($variables) {
    $current_variables = $this->getThemeVariables();
    if (is_array($variables)) {
      $this->theme_variables = array_merge($current_variables, $variables);
    }
  }

  /**
   * Function to validate a date
   *
   * @param string $date
   * @return boolean
   */
  private function validateDateFormat($date) {
    if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $d)) {
      // date validation
      if (checkdate($d[2], $d[3], $d[1])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Function to set the OutDated flag
   *
   * @param string $when.
   *        Accepted formats 'YYYY-MM-DD' or 'now'.
   *
   * @return boolean
   */
  function setOutDated($when = 'now', $msg = "") {
    if (is_string($msg)) {
      $this->OutDatedMsg = $msg;
    }

    if (strtolower($when) == 'now' || ($this->validateDateFormat($when) && time() >= strtotime($when))) {
      $this->OutDated = TRUE;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Return value of the private property OutDatedMsg.
   */
  function getOutDatedMessage() {
    if (empty($this->OutDatedMsg)) {
      $this->OutDatedMsg = "This page is deprecated and may contain some information that is no longer relevant or accurate.";
    }
    return $this->OutDatedMsg;
  }

  /**
   * Return value of the private property OutDated.
   */
  function getOutDated() {
    return $this->OutDated;
  }

  /**
   * Function to set the version of jQuery
   *
   * @param string $version
   *
   * @return boolean
   * @deprecated
   */
  function setjQueryVersion($version = FALSE) {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    return FALSE;
  }

  /**
   * Return markup needed to load jQuery
   *
   * @return string|boolean
   * @deprecated
   */
  function getjQuery() {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    return FALSE;
  }

  /**
   * Return The Eclipse Foundation Twitter and Facebook badge
   *
   * @return string
   *
   * @deprecated
   */
  function getSocialBadge() {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    return '';
  }

  /**
   * Get Twitter Follow widget
   *
   * @param unknown $_twitterhandle
   */
  function getTwitterFollowWidget($_twitterhandle) {
    $output = '<a href="https://twitter.com/' . $_twitterhandle . '" class="twitter-follow-button btn btn-info btn-sm"><i class="fa fa-twitter"></i> Follow @' . $_twitterhandle . '</a>';
    return $output;

  }

  /**
   * Get Google Search HTML
   * @deprecated
   *
   * @return string
   */
  function getGoogleSearchHTML() {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    return '';
  }

  /**
   * Set Google Analytics Tracking code
   *
   * Setting $gaUniqueID to NULL will remove Google Analytics
   * from the page.
   *
   * @param string/NULL $gaUniqueID
   *
   * @deprecated
   */
  function setGoogleAnalyticsTrackingCode($code = 'UA-910670-2') {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
  }

  /**
   * Get Google Analytics Tracking code
   *
   * @param string/NULL $gaUniqueID
   * @deprecated
   */
  function getGoogleAnalyticsTrackingCode() {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    return 'UA-910670-2';
  }

  /**
   * Set Promotion Path
   *
   * @param unknown $_path
   */
  function setPromotionPath($_path) {
    $this->CustomPromotionPath = $_SERVER['DOCUMENT_ROOT'] . $_path;
  }

  /**
   * Use reCAPTCHA service
   *
   * @param bool $ssl
   *
   * @return Captcha
   */
  public function useCaptcha($ssl = true) {
    include_once ($this->getBasePath() . '/classes/captcha/captcha.class.php');
    return new Captcha($ssl);
  }

  /**
   * Record a database record
   *
   * @param unknown $key
   * @param unknown $host
   * @param unknown $user
   * @param unknown $pwd
   * @param unknown $db
   */
  public function setDatabase($key, $host, $user, $pwd, $db) {
    $rec = array();
    $rec['HOST'] = $host;
    $rec['USERNAME'] = $user;
    $rec['PASSWORD'] = $pwd;
    $rec['DATABASE'] = $db;
    $rec['CONNECTION'] = null;
    $this->databases[$key] = $rec;
  }

  /**
   * Setup the handling of database connections.
   *
   * On production systems, reference the database connection
   * classes, but on development systems, use the standardized local database
   * distribution.
   */
  private function configureDatabases() {
    // -----------------------------------------------------------------------------------------------------
    // Dev Mode Databases
    $this->setDatabase("myfoundation", "localhost", "dashboard", "draobhsad", "myfoundation_demo");
    $this->setDatabase("foundation", "localhost", "dashboard", "draobhsad", "myfoundation_demo");
    $this->setDatabase("eclipse", "localhost", "dashboard", "draobhsad", "myfoundation_demo");
    $this->setDatabase("bugzilla", "localhost", "dashboard", "draobhsad", "myfoundation_demo");
    $this->setDatabase("downloads", "localhost", "dashboard", "draobhsad", "myfoundation_demo");
    $this->setDatabase("polls", "localhost", "dashboard", "draobhsad", "myfoundation_demo");
    $this->setDatabase("projectinfo", "localhost", "dashboard", "draobhsad", "myfoundation_demo");
    $this->setDatabase("packaging", "localhost", "dashboard", "draobhsad", "packaging_demo");
    $this->setDatabase("infra", "localhost", "dashboard", "draobhsad", "infra_demo");
    $this->setDatabase("ipzilla", "localhost", "dashboard", "draobhsad", "ipzilla_demo");
    $this->setDatabase("ipzillatest", "localhost", "dashboard", "draobhsad", "ipzilla_demo");
    $this->setDatabase("live", "localhost", "dashboard", "draobhsad", "live_demo");
    $this->setDatabase("epic", "localhost", "dashboard", "draobhsad", "epic_demo");
    $this->setDatabase("conferences", "localhost", "dashboard", "draobhsad", "conferences_demo");
    $this->setDatabase("marketplace", "localhost", "dashboard", "draobhsad", "marketplace_demo");

    // Production Databases
    $this->set("bugzilla_db_classfile_ro", 'dbconnection_bugs_ro.class.php');
    $this->set("bugzilla_db_class_ro", 'DBConnectionBugs');
    $this->set("bugzilla_db_classfile", 'dbconnection_bugs_rw.class.php');
    $this->set("bugzilla_db_class", 'DBConnectionBugsRW');
    $this->set("dashboard_db_classfile", 'dbconnection_dashboard_rw.class.php');
    $this->set("dashboard_db_class", 'DBConnectionDashboard');
    $this->set("downloads_db_classfile_ro", 'dbconnection_downloads_ro.class.php');
    $this->set("downloads_db_class_ro", 'DBConnectionDownloads');
    $this->set("epic_db_classfile_ro", 'dbconnection_epic_ro.class.php');
    $this->set("epic_db_class_ro", 'DBConnectionEPIC');
    $this->set("packaging_db_classfile_ro", 'dbconnection_packaging_ro.class.php');
    $this->set("packaging_db_class_ro", 'DBConnectionPackaging');
    $this->set("foundation_db_classfile", 'dbconnection_workaround.class.php');
    $this->set("foundation_db_class", 'FoundationDBConnectionRW');
    $this->set("foundation_db_classfile_ro", 'dbconnection_foundation_ro.class.php');
    $this->set("foundation_db_class_ro", 'DBConnectionFoundation');
    $this->set("gerrit_db_classfile_ro", 'dbconnection_gerrit_ro.class.php');
    $this->set("gerrit_db_class_ro", 'DBConnectionGerrit');
    $this->set("infra_db_classfile", 'dbconnection_infra_ro.class.php');
    $this->set("infra_db_class", 'DBConnectionInfraRO');
    $this->set("ipzilla_db_classfile_ro", 'dbconnection_ipzilla_ro.class.php');
    $this->set("ipzilla_db_class_ro", 'DBConnectionIPZillaRO');
    $this->set("ipzilla_db_classfile", 'dbconnection_ipzilla_rw.class.php');
    $this->set("ipzilla_db_class", 'DBConnectionIPZillaRW');
    $this->set("ipzillatest_db_classfile", 'dbconnection_ipzillatest_rw.class.php');
    $this->set("ipzillatest_db_class", 'DBConnectionIPZillaRW');
    $this->set("live_db_classfile", 'dbconnection_live_rw.class.php');
    $this->set("live_db_class", 'DBConnectionLIVE');
    $this->set("polls_db_classfile", 'dbconnection_polls_rw.class.php');
    $this->set("polls_db_class", 'DBConnectionPollsRW');
    $this->set("myfoundation_db_classfile", 'dbconnection_portal_rw.class.php');
    $this->set("myfoundation_db_class", 'DBConnectionPortalRW');
    $this->set("projectinfo_db_classfile_ro", 'dbconnection_projectinfo_ro.class.php');
    $this->set("projectinfo_db_class_ro", 'DBConnectionProjectInfo');
    $this->set("eclipse_db_classfile", 'dbconnection_rw.class.php');
    $this->set("eclipse_db_class", 'DBConnectionRW');
    $this->set("eclipse_db_classfile_ro", 'dbconnection.class.php');
    $this->set("eclipse_db_class_ro", 'DBConnection');
    $this->set("conferences_db_classfile", 'dbconnection.conferences_rw.class.php');
    $this->set("conferences_db_class", 'DBConnectionConferencesRW');
    $this->set("marketplace_db_classfile_ro", 'dbconnection_marketplace_ro.class.php');
    $this->set("marketplace_db_class_ro", 'DBConnectionMarket');
  }

  /**
   * Open a database and store the record
   */
  public function database($key, $query) {
    $rec = $this->databases[$key];
    $dbh = null;
    if ($this->devmode) { // For DEV machines
      $dbh = $rec['CONNECTION'];
      if ($dbh == null) {
        $dbh = mysql_connect($rec['HOST'], $rec['USERNAME'], $rec['PASSWORD']);
        mysql_select_db($rec['DATABASE'], $dbh);
      }
      if (get_magic_quotes_gpc()) {
        trigger_error("magic_quotes_gpc is currently set to ON in your php.ini. This is highly DISCOURAGED.  Please change your setting or comment out this line.");
      }
    }
    else {
      // For PRODUCTION machines
      $class = null;
      // Try to use read-only when possible. Blank queries occur when we call
      // sqlSanitize
      if ((strtoupper(substr(trim($query), 0, 6)) == 'SELECT' && strtoupper(substr(trim($query), 0, 23)) != "SELECT /* USE MASTER */") || $query == "") {

        $classfile = $this->get($key . '_db_classfile_ro');
        $class = $this->get($key . '_db_class_ro');
      }

      if ($class == null) {
        $classfile = $this->get($key . '_db_classfile');
        $class = $this->get($key . '_db_class');
      }

      require_once ($this->DB_CLASS_PATH . $classfile);
      $dbc = new $class();
      $dbh = $dbc->connect();
    }
    $this->databases[$key]['CONNECTION'] = $dbh;
    $this->set('DBHANDLEMAP ' . $dbh, $key);
    return $dbh;
  }

  /**
   * Return a record for a database by name
   */
  public function databaseName($key) {
    $rec = $this->databases[$key];
    return $rec['DATABASE'];
  }

  /**
   * Return the name of a database by database handle
   *
   * @param unknown $dbh
   */
  public function databaseNameForHandle($dbh) {
    return $this->get('DBHANDLEMAP ' . $dbh);
  }

  /**
   * Storage functions for arbitraray hash
   *
   * @param unknown $key
   */
  public function get($key) {
    if (isset($this->hash[$key])) {
      return $this->hash[$key];
    }
  }

  /**
   * Storage functions for arbitraray hash
   *
   * @param unknown $key
   * @param unknown $value
   */
  public function set($key, $value) {
    $this->hash[$key] = $value;
  }

  /**
   * Storage functions for arbitraray hash
   */
  public function ifEmptyThenSet($key, $value) {
    if (!isset($this->hash[$key])) {
      $this->hash[$key] = $value;
    }
  }

  /**
   * Display a backtrace of all the SQL queries run in this session
   *
   * Only available when devmode == true or logsql == true.
   */
  function SQLBacktrace() {
    if (($this->devmode && (count($this->query_btrace) > 0)) || $this->logsql) {
      $row = 1;
      echo "<p><table cellpadding=10 width=800 bgcolor=#ffcccc><tr><td>";
      echo "<p><font size=\"+2\">Query Trace: </font> In ascending order from oldest to newest";
      echo "<div style=\"font-family: courier;\">";
      foreach ($this->query_btrace as $query) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>$row.) " . $query{0} . " (" . $query{2} . ")rows:</b> " . $query{1} . "<br>\n";
        $row++;
      }
      echo "</div>";
      echo "</p>\n";
      echo "</table></p>\n";
    }
  }

  /**
   * Check if a MySQL error occurred and display it
   *
   * If $this->devmode then an SQL backtrace is shown as well.
   */
  function mysqlErrorCheck() {
    $error = mysql_error();
    if ($error) {
      echo "<p><table cellpadding=10 width=400 bgcolor=#ffcccc><tr><td><font size=+2>SQL Trouble: </font>";
      echo "<font color=red>";
      echo htmlspecialchars($error);
      echo "</font>\n";
      if ($this->devmode || $this->logsql) {
        $backtrace = debug_backtrace();
        $file = $backtrace[2]['file'];
        $line = $backtrace[2]['line'];
        $function = $backtrace[2]['function'];
        echo "<br/>file: $file<br/>line: $line<br/>function: $function<br/>";
      }
      echo "</table></p>\n";
      if ($this->devmode || $this->logsql) {
        $this->SQLBacktrace();
      }
      exit();
    }
  }

  // All in one query function
  function sql($statement, $dbname, $logstring = null) {
    $dbh = $this->database($dbname, $statement);

    $result = mysql_query($statement, $dbh);
    $rowcount = 0;

    // Only keep information in devmode so we don't waste RAM
    if ($this->devmode || $this->logsql) {
      // Report on the number of rows affected by the query
      if (($result !== TRUE) && ($result !== FALSE)) {
        $rowcount = mysql_num_rows($result);
      }
      else {
        $rowcount = mysql_affected_rows($dbh);
      }

      if ($logstring) {
        // This is used when inserting binary blobs so that the blob does not
        // appear in the log
        $this->query_btrace[] = array(
          $this->databaseNameForHandle($dbh),
          $logstring,
          $rowcount
        );
      }
      else {
        $this->query_btrace[] = array(
          $this->databaseNameForHandle($dbh),
          $statement,
          $rowcount
        );
      }
    }

    $this->mysqlErrorCheck();
    return $result;
  }

  /**
   * These don't match the naming convention in $App but are used in the portal
   * and submissions systems like this
   * so we'll leave them alone for consistency.
   *
   * Bugzilla
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function bugzilla_sql($statement) {
    return $this->sql($statement, "bugzilla");
  }

  /**
   * Conferences
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function conference_sql($statement) {
    return $this->sql($statement, "conferences");
  }

  /**
   * Dash
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function dashboard_sql($statement) {
    return $this->sql($statement, "dashboard");
  }

  /**
   * Downloads
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function downloads_sql($statement) {
    return $this->sql($statement, "downloads");
  }

  /**
   * Whole Eclipse database
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function eclipse_sql($statement) {
    return $this->sql($statement, "eclipse");
  }

  /**
   * EPIC (read-only!)
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function epic_sql($statement) {
    return $this->sql($statement, "epic");
  }

  /**
   * Foundation (internal database)
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function foundation_sql($statement) {
    return $this->sql($statement, "foundation");
  }

  /**
   * Gerrit
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function gerrit_sql($statement) {
    return $this->sql($statement, "gerrit");
  }

  /**
   * IPZilla
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function ipzilla_sql($statement) {
    return $this->sql($statement, "ipzilla");
  }

  /**
   * Infra
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function infra_sql($statement) {
    return $this->sql($statement, "infra");
  }

  /**
   * IPZilla (test database)
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function ipzillatest_sql($statement) {
    return $this->sql($statement, "ipzillatest");
  }

  /**
   * Eclipse Live (read-only!)
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function live_sql($statement) {
    return $this->sql($statement, "live");
  }

  /**
   * Polls
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function polls_sql($statement) {
    return $this->sql($statement, "polls");
  }

  /**
   * MyFoundation Portal
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function portal_sql($statement) {
    return $this->sql($statement, "myfoundation");
  }

  /**
   * ProjectInfo tables only (read-only!)
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function projectinfo_sql($statement) {
    return $this->sql($statement, "projectinfo");
  }

  /**
   * Packaging Database
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function packaging_sql($statement) {
    return $this->sql($statement, "packaging");
  }

  /**
   * Marketplace (read-only)
   *
   * @param string $statement
   *
   * @return mysql_query()
   */
  function marketplace_sql($statement) {
    return $this->sql($statement, "marketplace");
  }

}
