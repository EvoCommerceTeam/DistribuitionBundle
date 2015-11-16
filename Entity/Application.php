<?php

namespace Evo\Platform\DistributionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="evo_app")
 */
class Application extends \Evo\Platform\DistributionBundle\Model\Application
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(name="app_name", type="string")
     */
    protected $name;
    /**
     * @ORM\Column(name="app_type", type="string")
     */
    protected $type;
    /**
     * @ORM\Column(type="string")
     */
    protected $category;
}