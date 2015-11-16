<?php

namespace Evo\Platform\DistributionBundle\Model;

class Application
{
    protected $id;
    protected $name;
    protected $type;
    protected $category;

    public function __construct($name, $type, $category)
    {
        $this->name         = $name;
        $this->type         = $type;
        $this->category     = $category;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }
}