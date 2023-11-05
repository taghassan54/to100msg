<?php
/* Servers configuration */
$i = 0;

/* Server: localhost [1] */
$i++;
$cfg['Servers'][$i]['verbose'] = '';
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['port'] = '';
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = '';
$cfg['Servers'][$i]['AllowNoPassword'] = false;

/* End of servers configuration */

$cfg['blowfish_secret'] = 'a8f353602eea81715f92a525477b32a23b3ee3fd';
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
$cfg['DefaultLang'] = 'en';
$cfg['ServerDefault'] = 1;
?>