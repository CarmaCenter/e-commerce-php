<?php
function lang ($phrease){
    static $lang = array(
//dashboard Page
'HOME_ADMIN' => 'Home',
'CATEGORIES' => 'Categories',
'ITEMS' => 'Items',
'MEMBERS' => 'Members',
'STATISTICS' => 'Statistics',
'LOGS' => 'Logs',
'EDITPROFILE' => 'Edit Profile',
'SETTINGS' => 'Settings',
'LOGOUT' => 'Logout',
'CARMACENTER' => 'Carmacenter'
    );
    return $lang[$phrease];
}
