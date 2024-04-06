<?php

/* Local configuration for Roundcube Webmail */

// ----------------------------------
// SQL DATABASE
// ----------------------------------
// Database connection string (DSN) for read+write operations
// Format (compatible with PEAR MDB2): db_provider://user:password@host/database
// Currently supported db_providers: mysql, pgsql, sqlite, mssql, sqlsrv, oracle
// For examples see http://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php
// Note: for SQLite use absolute path (Linux): 'sqlite:////full/path/to/sqlite.db?mode=0646'
//       or (Windows): 'sqlite:///C:/full/path/to/sqlite.db'
// Note: Various drivers support various additional arguments for connection,
//       for Mysql: key, cipher, cert, capath, ca, verify_server_cert,
//       for Postgres: application_name, sslmode, sslcert, sslkey, sslrootcert, sslcrl, sslcompression, service.
//       e.g. 'mysql://roundcube:@localhost/roundcubemail?verify_server_cert=false'
$config['db_dsnw'] = 'mysql://ichtqlsn_Zero To 100:ichtqlsn_Zero To 100@localhost/roundcube';

// Log sent messages to <log_dir>/sendmail.log or to syslog
$config['smtp_log'] = false;

// ----------------------------------
// IMAP
// ----------------------------------
// The IMAP host (and optionally port number) chosen to perform the log-in.
// Leave blank to show a textbox at login, give a list of hosts
// to display a pulldown menu or set one host as string.
// Enter hostname with prefix ssl:// to use Implicit TLS, or use
// prefix tls:// to use STARTTLS.
// If port number is omitted it will be set to 993 (for ssl://) or 143 otherwise.
// Supported replacement variables:
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)
// %s - domain name after the '@' from e-mail address provided at login screen
// For example %n = mail.domain.tld, %t = domain.tld
// WARNING: After hostname change update of mail_host column in users table is
//          required to match old user data records with the new host.
$config['imap_host'] = 'localhost:143';

// provide an URL where a user can get support for this Roundcube installation
// PLEASE DO NOT LINK TO THE ROUNDCUBE.NET WEBSITE HERE!
$config['support_url'] = '';

// Automatically register user in Roundcube database on successful (IMAP) logon.
// Set to false if only registered users should be allowed to the webmail.
// Note: If disabled you have to create records in Roundcube users table by yourself.
// Note: Roundcube does not manage/create users on a mail server.
$config['auto_create_user'] = false;

// This key is used for encrypting purposes, like storing of imap password
// in the session. For historical reasons it's called DES_key, but it's used
// with any configured cipher_method (see below).
// For the default cipher_method a required key length is 24 characters.
$config['des_key'] = 'HobstBCONFJkCjOynb67VkFP';

// ----------------------------------
// PLUGINS
// ----------------------------------
// List of active plugins (in plugins/ directory)
$config['plugins'] = ['acl', 'archive', 'emoticons', 'filesystem_attachments', 'help', 'jqueryui', 'managesieve', 'markasjunk', 'vcard_attachments', 'zipdownload'];

// show up to X items in messages list view
$config['mail_pagesize'] = 100;

// prefer displaying HTML messages
$config['prefer_html'] = false;

// compose html formatted messages by default
//  0 - never,
//  1 - always,
//  2 - on reply to HTML message,
//  3 - on forward or reply to HTML message
//  4 - always, except when replying to plain text message
$config['htmleditor'] = 3;

// save compose message every 300 seconds (5min)
$config['draft_autosave'] = 180;

$config['default_host'] = 'localhost';

$config['default_port'] = 143;

$config['smtp_server'] = 'localhost';

$config['smtp_port'] = 587;

