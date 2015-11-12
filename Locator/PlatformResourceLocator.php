<?php

namespace Evo\Platform\DistributionBundle\Locator;

class PlatformResourceLocator implements ResourceLocator
{
    const RESOURCE_SUFFIX = 'config/platform';

    protected $locations;

    /**
     * PlatformResourceLocator constructor.
     * @param array $locations
     */
    public function __construct($locations = [])
    {
        $this->locations = $locations;
    }

    /**
     * @param $target
     * @return array
     */
    public function locate($target)
    {
        $paths = [];
        $target = sprintf("%s/%s", self::RESOURCE_SUFFIX, $target);

        foreach ($this->locations as $location) {
            if (!is_dir($location)) {
                continue;
            }

            $location = realpath($location);
            $dir      = new \RecursiveDirectoryIterator($location, \FilesystemIterator::FOLLOW_SYMLINKS);
            $filter   = new \RecursiveCallbackFilterIterator($dir,
                function (\SplFileInfo $current) use (&$paths, $target) {
                    $fileName = strtolower($current->getFilename());
                    if ($this->isExcluded($fileName) || $current->isFile()) {
                        return false;
                    }
                    if (!is_dir($current->getPathname() . '/Resources')) {
                        return true;
                    } else {
                        $file = $current->getPathname() . sprintf("/Resources/%s", $target);
                        if (is_file($file)) {
                            $paths[] = $file;
                        }

                        return false;
                    }
                }
            );

            $iterator = new \RecursiveIteratorIterator($filter);
            $iterator->rewind();
        }

        return $paths;
    }

    protected function isExcluded($file)
    {
        return in_array(strtolower($file), ['.','..','tests']);
    }
}