<?php

namespace Voryx\RESTGeneratorBundle\Manager\Factory;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Voryx\RESTGeneratorBundle\Manager\ManagerInterface;

class ManagerFactory implements ManagerFactoryInterface
{
    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    protected $doctrine;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var \Voryx\RESTGeneratorBundle\Manager\ManagerInterface[]
     */
    protected $managers =  array();

    public function __construct(RegistryInterface $doctrine, EventDispatcherInterface $eventDispatcher)
    {
        $this->doctrine = $doctrine;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function addManager(ManagerInterface $manager)
    {
        $class = $manager->getClass();
        $em = $this->doctrine->getManagerForClass($class);

        $manager->setObjectManager($em);
        $manager->setRepository($em->getRepository($class));
        $manager->setEventDispatcher($this->eventDispatcher);
        $manager->setFactory($this);

        $this->managers[$class] = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function getManager($class)
    {
        if(!array_key_exists($class, $this->managers)) {
            throw new \Exception(sprintf('Service manager for class "%s" not found.', $class));
        }
        return $this->managers[$class];
    }

} 