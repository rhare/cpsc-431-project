<?php
  function get_user_or_redirect_login(){
    require_once($_SERVER['DOCUMENT_ROOT']  . '/Auth/User.php');
    session_start();
    if(empty($_SESSION['user'])) {
      $uri = 'http://' . $_SERVER['HTTP_HOST'];
      $newURL = $url . '/Auth/login.php';
      header('Location: '.$newURL);
      exit(0);
    }
    return unserialize($_SESSION['user']);
  }

  function redirect_root_if_auth(){
    require_once($_SERVER['DOCUMENT_ROOT']  . '/Auth/User.php');
    session_start();
    if(!empty($_SESSION['user'])) {
      $uri = 'http://' . $_SERVER['HTTP_HOST'];
      $newURL = $url . '/';
      header('Location: '.$newURL);
      exit(0);
    }
    return True;
  }
?>
