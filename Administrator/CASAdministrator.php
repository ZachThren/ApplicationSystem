<?php
require_once("support.php");

$topPart = <<<EOPAGE

  <form class="mycontainer" action="{$_SERVER['PHP_SELF']}" method="post">

  <strong>Directory ID: </strong><input type="text" name = "directoryid" class="form-control" /><br /><br />
  <strong>Password: </strong><input type="password" name="password" class="form-control" /><br /><br>



  <input type="reset" name="clear" class="btn btn-info"/>
  <input type="submit" name="submit" class="btn btn-info"/>

  </form>
EOPAGE;

$bottomPart = "";


if (isset($_POST["submit"])){
/* You need to update http.conf file so the ldap module is loaded */
/* Entry in http.conf: LoadModule ldap_module modules/mod_ldap.so */

$login_nm = trim($_POST["directoryid"]);
$login_passwd = trim($_POST["password"]);

/* Establish a connection to the LDAP server */
$ldapconn=ldap_connect("ldap://ldap.umd.edu/",389) or die('Could not connect<br>');
// $ldapconn=ldap_connect("ldaps://ldap.umd.edu/",389) or die('Could not connect<br>');

/* Set the protocol version to 3 (unless set to 3 by default) */
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

error_reporting(E_ALL ^ E_WARNING); 
/* Bind user to LDAP with password */
$verify_user=ldap_bind($ldapconn,"uid=$login_nm,ou=people,dc=umd,dc=edu",$login_passwd);

/* Returns 1 on Success */
if ($verify_user != 1) {
  /* Failed */

  $bottomPart .= "<p align='center' style='color:red; font-size:18px'>*Invalid Directory Id/Password</p>";

} else {
  /* Success */
  header("location: newAdministrative.php");

}


// Release connection
ldap_unbind($ldapconn);
}

$body = $topPart.$bottomPart;
echo generatePage($body);

?>
