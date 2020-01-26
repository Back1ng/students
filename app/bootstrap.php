<?php

session_start();

$config = json_decode(file_get_contents('../config.json'));

define("DB_DRIVER", $config->db->driver);
define("DB_HOST", $config->db->host);
define("DB_NAME", $config->db->name);
define("DB_USERNAME", $config->db->username);
define("DB_PASSWORD", $config->db->password);
