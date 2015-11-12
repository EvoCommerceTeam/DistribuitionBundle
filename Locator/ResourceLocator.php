<?php

namespace Evo\Platform\DistributionBundle\Locator;

interface ResourceLocator
{
    /**
     * @param $target
     * @return string
     */
    public function locate($target);
}