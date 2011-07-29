<?php  
  // Script: form.php, called by form.html.  
  $count = 0;  
  echo ("<p>Request method: ".$_SERVER['REQUEST_METHOD']."</p>" );  
  echo "<p>The following data has been submitted by the form:</p>";  
  if (count($_GET) != 0){  
    echo "<p>Method: GET</p>";  
    foreach ($_GET as $key=>$value)  {  
      echo "<p>Data".$count." : "."Key=".$key.", "."Value=".$value."</p>";  
      $count ++;  
    }  
  }  
  else if (count($_POST) != 0){  
    echo "<p>Method: POST</p>";  
    foreach ($_POST as $key=>$value)  {  
      echo "<p>Data".$count." : "."Key=".$key.", "."Value=".$value."</p>";  
      $count ++;  
    }  
  }  
  else if (count($_PUT) != 0){  
    echo "<p>Method: PUT</p>";  
    foreach ($_PUT as $key=>$value)  {  
      echo "<p>Data".$count." : "."Key=".$key.", "."Value=".$value."</p>";  
      $count ++;  
    }  
  }  
  else {  
    echo "<p>There is no data.</p>";  
  }  
  
  echo "<p>End of program.</p>";  
    
?>
