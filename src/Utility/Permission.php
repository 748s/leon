<?php

namespace Leon\Utility;

class Permission
{
    public function getIsLoggedIn()
    {
        return isset($_SESSION['userId']);
    }
}
