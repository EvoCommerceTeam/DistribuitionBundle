<?php

namespace Evo\Platform\DistributionBundle\Application;

use Doctrine\ORM\Mapping as ORM;

trait ApplicationSpecificTrait
{
    protected $applicationId;

    public function setApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    public function getApplicationId()
    {
        return $this->applicationId;
    }
}