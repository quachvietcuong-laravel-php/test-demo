<?php
	function liActiveWhenRouteIsSame($route){
		$class = '';
		if(Route::is($route)) {
            $class = 'enu-is-opening menu-open';
        }
        return $class;
	}

	// Get array data permission name
    function permissionNamesByGroupBy ($permission){
        $arrayNames = explode(',', $permission->names);
        $group_name = $permission->group_name;
        $result = replaceGroupName($arrayNames, $group_name);
        return $result;
    }

    // Replace group name form permission
    function replaceGroupName($permission, $group_name) {
    	$result = [];
        for ($i = 0; $i < count($permission) ; $i++) { 
            $result[] = str_replace("$group_name." , '', $permission[$i]);
        } 
        return $result;
    }