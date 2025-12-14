<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	/*
		| -------------------------------------------------------------------------
		| URI ROUTING
		| -------------------------------------------------------------------------
		| This file lets you re-map URI requests to specific controller functions.
		|
		| Typically there is a one-to-one relationship between a URL string
		| and its corresponding controller class/method. The segments in a
		| URL normally follow this pattern:
		|
		|	example.com/class/method/id/
		|
		| In some instances, however, you may want to remap this relationship
		| so that a different class/function is called than the one
		| corresponding to the URL.
		|
		| Please see the user guide for complete details:
		|
		|	https://codeigniter.com/userguide3/general/routing.html
		|
		| -------------------------------------------------------------------------
		| RESERVED ROUTES
		| -------------------------------------------------------------------------
		|
		| There are three reserved routes:
		|
		|	$route['default_controller'] = 'welcome';
		|
		| This route indicates which controller class should be loaded if the
		| URI contains no data. In the above example, the "welcome" class
		| would be loaded.
		|
		|	$route['404_override'] = 'errors/page_missing';
		|
		| This route will tell the Router which controller/method to use if those
		| provided in the URL cannot be matched to a valid route.
		|
		|	$route['translate_uri_dashes'] = FALSE;
		|
		| This is not exactly a route, but allows you to automatically route
		| controller and method names that contain dashes. '-' isn't a valid
		| class or method name character, so it requires translation.
		| When you set this option to TRUE, it will replace ALL dashes in the
		| controller and method URI segments.
		|
		| Examples:	my-controller/index	-> my_controller/index
		|		my-controller/my-method	-> my_controller/my_method
	*/
	$route['default_controller'] = 'home';
	$route['login'] = 'auth/login';
	$route['logout'] = 'auth/logout';
	
	$route['admin'] = 'admin/dashboard';
	$route['admin/categories'] = 'admin/categories';
	$route['admin/categories/create'] = 'admin/categories/create';
	$route['admin/categories/store'] = 'admin/categories/store';
	$route['admin/categories/edit/(:num)'] = 'admin/categories/edit/$1';
	$route['admin/categories/update/(:num)'] = 'admin/categories/update/$1';
	$route['admin/categories/delete/(:num)'] = 'admin/categories/delete/$1';
	
	$route['admin/products'] = 'admin/products';
	$route['admin/products/create'] = 'admin/products/create';
	$route['admin/products/store'] = 'admin/products/store';
	$route['admin/products/edit/(:num)'] = 'admin/products/edit/$1';
	$route['admin/products/update/(:num)'] = 'admin/products/update/$1';
	$route['admin/products/delete/(:num)'] = 'admin/products/delete/$1';
	
	$route['admin/settings'] = 'admin/settings';
	$route['admin/settings/update'] = 'admin/settings/update';
	
	# API (token-based simple)
	$route['api/products'] = 'api/products/index';
	$route['api/categories'] = 'api/categories/index';
	$route['api/settings'] = 'api/settings/index';
	
