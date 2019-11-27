<?php
date_default_timezone_set('America/Denver');
define('ENV', 'DEV');
define('DOMAIN', $_SERVER['HTTP_HOST']);
define('SERVER_IP', $_SERVER['SERVER_ADDR']);
define('REMOTE_ADD', $_SERVER['SERVER_ADDR']);
define('KEY', 'test_tahc_erttt009874_sudhir');
define('CURRENT_DATE_TIME', date("Y/m/d G.i:s"));
define('ISRD', 'is_required="1"');
define('NRF', 'No Record Found');
define('DEFAULT_PAGE_LIMIT', 5);
define('MAX_PAGE_LIMIT', 90000);
define('star_mark', '<span class="required">*</span>');

define("SMTP_HOST", ""); //Hostname of the mail server
define("SMTP_PORT", ""); //Port of the SMTP
define("SMTP_UNAME", ""); //Username for SMTP authentication any valid email created in your domain
define("SMTP_PWORD", ""); //Password for SMTP authentication

define('EMAIL_FROM', "");
define('EMAIL_TO', "");
define('EMAIL_BCC', "");

//By Hegel Motokoua