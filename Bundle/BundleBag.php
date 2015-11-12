<?php

namespace Evo\Platform\DistributionBundle\Bundle;


class BundleBag
{
    /**
     * The singleton instance
     *
     * @var BundleBag
     */
    private static $instance = null;

    /**
     * @var string
     */
    private $appRootDir;

    /**
     * @var array
     */
    private $bundles = [];

    /**
     * Returns the singleton instance
     *
     * @return BundleBag
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * A private constructor to prevent create an instance of this class explicitly
     */
    private function __construct()
    {
    }

    /**
     * Clears state of this manager
     *
     * @return BundleBag
     */
    public function clear()
    {
        $this->bundles = [];

        return $this;
    }

    /**
     * Gets list of available bundles
     *
     * @return array
     */
    public function getBundles()
    {
        return $this->bundles;
    }

    /**
     * Sets list of available bundles
     *
     * @param array $bundles
     * @return BundleBag
     */
    public function setBundles($bundles)
    {
        $this->bundles = $bundles;

        return $this;
    }

    /**
     * Gets application root directory
     *
     * @return string
     */
    public function getAppRootDir()
    {
        return $this->appRootDir;
    }

    /**
     * Sets application root directory
     *
     * @param string $appRootDir
     * @return BundleBag
     */
    public function setAppRootDir($appRootDir)
    {
        $this->appRootDir = $appRootDir;

        return $this;
    }
}