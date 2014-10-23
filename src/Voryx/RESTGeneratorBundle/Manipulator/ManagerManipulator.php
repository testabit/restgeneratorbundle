<?php

namespace Voryx\RESTGeneratorBundle\Manipulator;

use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Manipulator\Manipulator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\Yaml\Yaml;

class ManagerManipulator extends Manipulator
{
    private $file;

    /**
     * Constructor.
     *
     * @param string $file The YAML admin file path
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Adds a Manager service within the managers config file.
     *
     * @param BundleInterface $bundle
     * @param string $entity
     *
     * @return Boolean true if it worked, false otherwise
     *
     * @throws \RuntimeException If bundle is already imported
     */
    public function addResource(BundleInterface $bundle, $entity)
    {
        $managerService = sprintf('%s.manager.%s', Container::underscore(substr($bundle->getName(), 0, -6)), strtolower($entity));
        $managerClass = sprintf('%s\Manager\%sManager', $bundle->getNamespace(), ucfirst($entity));
        $entityClass = sprintf('%s\Entity\%s', $bundle->getNamespace(), ucfirst($entity));

        $content = Yaml::parse(file_get_contents($this->file));

        $content['parameters'][$managerService . '.class'] = $managerClass;
        $content['services'][$managerService] = array(
            'class' => "%$managerService.class%",
            'tags' => array(
                array(
                    'name' => 'voryx.manager'
                )
            ),
            'arguments' => array($entityClass)
        );

        $yaml = Yaml::dump($content, 4);
        if (false === file_put_contents($this->file, $yaml)) {
            return false;
        }

        return true;
    }
}
