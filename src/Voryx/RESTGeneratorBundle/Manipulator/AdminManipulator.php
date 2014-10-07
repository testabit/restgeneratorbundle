<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Voryx\RESTGeneratorBundle\Manipulator;

use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Manipulator\Manipulator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Changes the PHP code of a YAML routing file.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class AdminManipulator extends Manipulator
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
     * Adds an Admin service within the admin config file.
     *
     * @param BundleInterface $bundle
     * @param string $entity
     * @param string $group
     * @param string $label
     * @param string $translationDomain
     *
     * @return Boolean true if it worked, false otherwise
     *
     * @throws \RuntimeException If bundle is already imported
     */
    public function addResource(BundleInterface $bundle, $entity, $group, $label, $translationDomain = 'Sonata')
    {
        $adminService = sprintf('%s.admin.%s', Container::underscore(substr($bundle->getName(), 0, -6)), strtolower($entity));
        $adminClass = sprintf('%s\Admin\%sAdmin', $bundle->getNamespace(), ucfirst($entity));
        $entityClass = sprintf('%s\Entity\%s', $bundle->getNamespace(), ucfirst($entity));

        $content = Yaml::parse(file_get_contents($this->file));

        $content['parameters'][$adminService . '.class'] = $adminClass;
        $content['services'][$adminService] = array(
            'class' => "%$adminService.class%",
            'tags' => array(
                array(
                    'name' => 'sonata.admin',
                    'manager_type' => 'orm',
                    'group' => $group,
                    'label' => $label
                )
            ),
            'arguments' => array(null, $entityClass, null),
            'calls' => array(
                array(
                    'setTranslationDomain',
                    array(
                        $translationDomain
                    )
                )
            )
        );

        $yaml = Yaml::dump($content, 4);
        if (false === file_put_contents($this->file, $yaml)) {
            return false;
        }

        return true;
    }
}
