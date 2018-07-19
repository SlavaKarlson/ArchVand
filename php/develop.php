<?php
function GetDevelopContent(&$db){
  $result = false;
  $result = file_get_contents('content'.DIRECTORY_SEPARATOR.'develop.html'); //определение разделителя (slash it) папок
  $result = str_replace('%PROJECTS%', GetProjects($db), $result);
  return $result;
}

function GetProjects(&$db){
  $result = '';
  $template = file_get_contents('templates'.DIRECTORY_SEPARATOR.'project.inc.html');

  $request=$db->prepare(
    'SELECT c.id,c.title,c.date,c.content,u.name
     FROM content c
     LEFT JOIN user u ON u.id = c.user_id
     ORDER BY date DESC
     LIMIT 5'
  );

  $request->execute();
  $data = $request->get_result();
  while($rows=$data->fetch_assoc()){
    
    if (!isset($rows)){
      continue;
    }
    $project = '';
    $project = str_replace('%ID%', $rows['id'], $template);
    $project = str_replace('%TITLE%', $rows['title'], $project);
    $project = str_replace('%DATE%', $rows['date'], $project);
    $project = str_replace('%USER%', $rows['name'], $project);
    $project = str_replace('%CONTENT%', $rows['content'], $project);
    $result .= $project.PHP_EOL;
  }
  $request->close();

  return $result;
}
