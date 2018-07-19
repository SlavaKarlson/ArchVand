<?php
define('ROOT',dirname(__FILE__));

$db = new mysqli("localhost", "root", "usbw", "archvand");
if ($db->connect_error) {
  echo 'error connect to DB';
  exit;
}

// /archvand/handler.php?page=
if (isset($_GET['page'])){
  $result=false;

  switch ($_GET['page']) {
    case 'main':
      //$result='Главная(пхп)';
      $result=file_get_contents('content'.DIRECTORY_SEPARATOR.'main.html');
      break;
    case 'develop':
      include ROOT.'\php\develop.php';
      $result = GetDevelopContent($db);
      break;
    case 'media':
      //$result='Медиа(пхп)';
      $result=file_get_contents('content'.DIRECTORY_SEPARATOR.'media.html'); //определение разделителя (slash it) папок
      break;
    case 'contacts':
      $result=file_get_contents('content'.DIRECTORY_SEPARATOR.'contacts.html'); //определение разделителя (slash it) папок
      break;
  }

  echo json_encode($result);
}

// /archvand/handler.php?act=
if (isset($_GET['act'])){
  $result=false;

  switch ($_GET['act']) {
    case 'check_auth':
      include ROOT.'\php\auth.php';
      $result=CheckAuth($db);
      break;

    case 'exit_auth':
      include ROOT.'\php\auth.php';
      $result=ExitAuth($db);
      break;
  }
  $db->close();
  echo json_encode($result);
  exit;
}
//-----------
if (isset($_POST['act'])){
  $result=false;

  switch ($_POST['act']) {
    case 'auth':
      include ROOT.'\php\auth.php';
      $result=Auth($db);
      break;
  }
  $db->close();
  echo json_encode($result);
  exit;
}

$db->close();
