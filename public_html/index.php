<?php
namespace MyOrg\Application;
require_once('../mp/mp.php');

$config->paths['appPath'] = dirname(__FILE__).'/../application';

$mp->run(__NAMESPACE__);
