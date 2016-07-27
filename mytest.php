<?php
/*
$bcrypt = new Bcrypt(15);
$hash = $bcrypt->hash('password');
$isGood = $bcrypt->verify('password', $hash);
*/
  $pass = '123456789';
  
  
  function better_crypt($input, $rounds = 7)
  {
    $salt = "";
    $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
    for($i=0; $i < 22; $i++) {
      $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
  }
  
  $password_hash = better_crypt($pass);;
   
  echo $password_hash;
  echo "<br/>";
  
  $password_entered = '123456789';
  
  if(crypt($password_entered, $password_hash) == $password_hash) {
    // password is correct
	echo "ok";
  }else{
	  echo "nok";
  }
  
?>

