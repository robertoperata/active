<?php

namespace PrizUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PrizUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
