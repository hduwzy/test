<?php

return array(
	'js_angular' 	=> Flight::conf()->get('sqldoc.urls.domain') . '/public/js/angularjs/angular.min.js',
	'js_jquery'		=> Flight::conf()->get('sqldoc.urls.domain') . '/public/js/jquery.min.js',
	'js_bs' 		=> Flight::conf()->get('sqldoc.urls.domain') . '/public/bootstrap/js/bootstrap.min.js',

	'css_bs' 		=> Flight::conf()->get('sqldoc.urls.domain') . '/public/bootstrap/css/bootstrap.min.css',
	'css_bs_theme' 	=> Flight::conf()->get('sqldoc.urls.domain') . '/public/bootstrap/css/bootstrap-theme.min.css'
);