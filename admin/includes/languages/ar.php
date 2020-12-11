<?php
function lang ($phrease){
    static $lang =  array(
        //HomePage
'MESSAGE' => 'مرحبا',
'ADMIN' => 'المسؤل',
    );
    return $lang[$phrease];
}
