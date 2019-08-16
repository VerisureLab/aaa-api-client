<?php

namespace VerisureLab\Library\AAAApiClient;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use VerisureLab\Library\AAAApiClient\DependencyInjection\AAAApiClientExtension;

class AAAApiClientBundle extends Bundle
{
    public function getContainerExtension(): AAAApiClientExtension
    {
        return new AAAApiClientExtension();
    }
}