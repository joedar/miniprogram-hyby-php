<?php
session_start();
class Session {
  public static function set ($token, $value, $expire) {
    $session_data = array();
    $session_data['value'] = $value;
    $session_data['expire'] = $expire;
    $_SESSION[$token] = $session_data;
  }
  public static function get ($token) {
    if (isset($_SESSION[$token])) {
      if ($_SESSION[$token]['expire'] > time()) {
        return explode('@', $_SESSION[$token]['value'])[1];
      } else {
        self::clear($token);
      }
    }
    return false;
  }
  private static function clear ($token) {
    unset($_SESSION[$token]);
  }
}
?>
