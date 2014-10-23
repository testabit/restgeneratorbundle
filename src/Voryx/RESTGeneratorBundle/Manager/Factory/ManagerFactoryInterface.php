<?php

namespace Voryx\RESTGeneratorBundle\Manager\Factory;

use Voryx\RESTGeneratorBundle\Manager\ManagerInterface;

interface ManagerFactoryInterface
{
    /**
     * Add a new manager service
     *
     * @param ManagerInterface $manager
     * @return void
     */
    public function addManager(ManagerInterface $manager);

    /**
     * Get manager service for the given class
     *
     * @param $class
     * @return ManagerInterface
     */
    public function getManager($class);

} 