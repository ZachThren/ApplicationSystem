<?php

/* You need to update http.conf file so the ldap module is loaded */
/* Entry in http.conf: LoadModule ldap_module modules/mod_ldap.so */

$login_nm = $_POST["directoryid"];
$login_passwd = $_POST["password"];

/* Establish a connection to the LDAP server */
$ldapconn=ldap_connect("ldap://ldap.umd.edu/",389) or die('Could not connect<br>');
// $ldapconn=ldap_connect("ldaps://ldap.umd.edu/",389) or die('Could not connect<br>');

/* Set the protocol version to 3 (unless set to 3 by default) */
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

/* Bind user to LDAP with password */
$verify_user=ldap_bind($ldapconn,"uid=$login_nm,ou=people,dc=umd,dc=edu",$login_passwd);

/* Returns 1 on Success */
if ($verify_user != 1) {
  /* Failed */
  echo "Invalid directoryId/password<br>";
} else {
  /* Success */
  echo "You have been authenticated as having a valid UMD directory ID.";
}


// Release connection
ldap_unbind($ldapconn);
?>
