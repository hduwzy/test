<?php

namespace sysext\net;

use flight\net\Router as F_Router;
use flight\net\Request as F_Request;

class Router extends F_Router {

	public function route(F_Request $request) {
        $app = $this->app;
    	if ($route_str = $request->getRouteString()) {
            $module = $route_str['module'];
    		$controller = $route_str['controller'];
            $action = $route_str['action'];
            
            $app_name = $app->get('app.name');
            $controller_class = "apps\\$app_name\\$module\\$controller";

            if (class_exists($controller_class)) {
                $controller = new $controller_class($app);
                if (method_exists($controller, $action)) {
                    $app->map('_route_action_', array($controller, $action));
                    $app->before('_route_action_', array($controller, 'before'));
                    $app->after('_route_action_', array($controller, 'after'));
                    $callback = function () use ($app) {
                        return $app->_route_action_();
                    };
                    return $callback;
                }
            }
    	}
        return false;
	}

}
