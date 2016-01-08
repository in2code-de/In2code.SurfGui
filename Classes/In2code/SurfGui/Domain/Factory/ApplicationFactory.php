<?php
namespace In2code\SurfGui\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use TYPO3\Surf\Domain\Model\Application;

/**
 * Class ApplicationFactory
 *
 * @package In2code\SurfGui\Domain\Factory
 */
class ApplicationFactory
{
    /**
     * @param Application $sourceObject
     * @param string $destination
     * @return mixed
     */
    public function convert(Application $sourceObject, $destination)
    {
        if (get_class($sourceObject) == trim('\\', $destination)) {
            return $sourceObject;
        }
        $destinationObject = new $destination();
        $sourceReflectionObject = new \ReflectionObject($sourceObject);
        $destinationReflectionObject = new \ReflectionObject($destinationObject);

        foreach ($sourceReflectionObject->getProperties() as $sourceReflectionProperty) {
            $sourceReflectionProperty->setAccessible(true);
            $name = $sourceReflectionProperty->getName();
            if ($destinationReflectionObject->hasProperty($name)) {
                $reflectionProperty = $destinationReflectionObject->getProperty($name);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($destinationObject, $sourceReflectionProperty->getValue($sourceObject));
            }
        }
        return $destinationObject;
    }
}
