<?php

$scripts = array();
$i		 = 0;

$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/jquery.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/bootstrap.min.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/default.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/bootbox.min.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/sb-admin.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/jquery.metisMenu.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/jquery.dataTables.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/dataTables.bootstrap.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/bootstrap-switch.min.js';
$i++;
$scripts[$i]['type'] = 'text/javascript';
$scripts[$i]['src']	 = '/js/jquery.datetimepicker.js';
$i++;
$links				 = array();
$i					 = 0;

$links[$i]['href']	 = '/css/bootstrap.css';
$links[$i]['rel']	 = 'stylesheet';
$links[$i]['type']	 = 'text/css';
$i++;
$links[$i]['href']	 = '/css/font-awesome.min.css';
$links[$i]['rel']	 = 'stylesheet';
$links[$i]['type']	 = 'text/css';
$i++;
$links[$i]['href']	 = '/css/sb-admin.css';
$links[$i]['rel']	 = 'stylesheet';
$links[$i]['type']	 = 'text/css';
$i++;
$links[$i]['href']	 = '/css/default.css';
$links[$i]['rel']	 = 'stylesheet';
$links[$i]['type']	 = 'text/css';
$i++;
$links[$i]['href']	 = '/css/dataTables.bootstrap.css';
$links[$i]['rel']	 = 'stylesheet';
$links[$i]['type']	 = 'text/css';
$i++;
$links[$i]['href']	 = '/css/bootstrap-switch.min.css';
$links[$i]['rel']	 = 'stylesheet';
$links[$i]['type']	 = 'text/css';
$i++;
$links[$i]['href']	 = '/img/favicon.ico';
$links[$i]['rel']	 = 'shortcut icon';
$links[$i]['type']	 = 'image/x-icon';
$i++;
$links[$i]['href']	 = '/css/jquery.datetimepicker.css';
$links[$i]['rel']	 = 'stylesheet';
$links[$i]['type']	 = 'text/css';
$i++;

$config = array(
	'scripts'	 => $scripts,
	'links'		 => $links
);
?>