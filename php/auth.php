<?php
//__Функции авторизации
function CheckAuth(&$db){
  $result=false;
  $userID=0;

  $request=$db->prepare(
    'SELECT id,login,email
     FROM user
     WHERE hash=?'
  );
  $request->bind_param('s',$_COOKIE['hash']);
  $request->execute();
  $request->bind_result($userID,$login,$email);
  $request->fetch();
  $request->close();
  if ($userID){
    $content=file_get_contents('content'.DIRECTORY_SEPARATOR.'user_panel.html'); //определение разделителя (slash it) папок
    $content=str_replace('%LOGIN%',$login,$content);
    $content=str_replace('%EMAIL%',$email,$content);
    $result=$content;
  }
  else{
    $result=file_get_contents('content'.DIRECTORY_SEPARATOR.'auth_form.html'); //определение разделителя (slash it) папок
  }
  return $result;
}
//-||
function Auth(&$db){
  $result=false;
  if (isset($_POST['login']) && strlen($_POST['login'])>0 && isset($_POST['pass'])>0) {
    $login=$_POST['login'];
    $pass=$_POST['pass'];

    $request=$db->prepare(
      'SELECT id user_id
       FROM user
       WHERE login=? AND password=?'
    );
    $request->bind_param('ss',$login,$pass);
    $request->execute();
    $request->bind_result($userID);
    $request->fetch();
    $request->close();
  //хэшик
    if ($userID){
      $hash=md5($login.$pass.time());
      $request=$db->prepare(
        'UPDATE user
         SET hash=?
         WHERE id=?'
      );
      $request->bind_param('si',$hash,$userID);
      $res=$request->execute();
      $request->close();
      if ($res){
        setcookie('hash',$hash);
        $userID=0;
        $request=$db->prepare(
          'SELECT id,login,email
           FROM user
           WHERE hash=?'
        );
        $request->bind_param('s',$hash);
        $request->execute();
        $request->bind_result($userID,$login,$email);
        $request->fetch();
        $request->close();
        if ($userID){
          $content=file_get_contents('content'.DIRECTORY_SEPARATOR.'user_panel.html'); //определение разделителя (slash it) папок
          $content=str_replace('%LOGIN%',$login,$content);
          $content=str_replace('%EMAIL%',$email,$content);
          $result=$content;
        }
      };
    };
  }
  return $result;
}

function ExitAuth(&$db){
  $result=false;
  $userID=0;

  $request=$db->prepare(
    'SELECT id,login,email
     FROM user
     WHERE hash=?'
  );
  $request->bind_param('s',$_COOKIE['hash']);
  $request->execute();
  $request->bind_result($userID,$login,$email);
  $request->fetch();
  $request->close();
  if ($userID){
    $request=$db->prepare(
      'UPDATE user
       SET hash=NULL
       WHERE id=?'
    );
    $request->bind_param('i',$userID);
    $res=$request->execute();
    $request->close();
    if ($res){
      setcookie('hash','');
      $result=file_get_contents('content'.DIRECTORY_SEPARATOR.'auth_form.html'); //определение разделителя (slash it) папок
    }
  }
  return $result;
}
