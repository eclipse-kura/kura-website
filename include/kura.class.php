<?php
require_once ($_SERVER['DOCUMENT_ROOT'] .'/eclipse.org-common/classes/themes/quicksilver.class.php');

class KuraTheme extends Quicksilver {

  protected $MoreMenu = NULL;

  public function __construct($App = NULL) {
    parent::__construct($App);
  }

  public function setMoreMenu($MoreMenu) {
    $this->MoreMenu = $MoreMenu;
  }

  protected function _getMoreMenu() {
    return $this->MoreMenu;
  }

  public function getFooterPrexfix() {
  }
}
?>
