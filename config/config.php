<?php
// config/config.php
session_start();

define('DB_HOST','127.0.0.1');
define('DB_NAME','loja_perifericos');
define('DB_PORT','3307');
define('DB_USER','root');
define('DB_PASS',''); // coloque sua senha do MySQL

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
