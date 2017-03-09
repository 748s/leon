<?php

namespace Leon;

class Permission
{
    public function getIsLoggedIn()
    {
        return isset($_SESSION['userId']);
    }
}
