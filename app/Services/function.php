<?php
	function liActiveWhenRouteIsSame($route)
	{
		$class = '';
		if(Route::is($route)) {
            $class = 'enu-is-opening menu-open';
        }
        return $class;
	}