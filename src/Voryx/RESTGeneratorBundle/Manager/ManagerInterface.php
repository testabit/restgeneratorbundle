<?php

namespace Voryx\RESTGeneratorBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Voryx\RESTGeneratorBundle\Manager\Factory\ManagerFactoryInterface;

interface ManagerInterface
{
    /**
     * Get entity class managed by this manager
     *
     * @return string
     */
    public function getClass();

    /**
     * Set repository for the class managed by this manager
     *
     * @param ObjectRepository $repository
     * @return void
     */
    public function setRepository(ObjectRepository $repository);

    /**
     * Set the doctrine entity manager for the class managed by this manager
     *
     * @param ObjectManager $em
     * @return void
     */
    public function setObjectManager(ObjectManager $em);

    /**
     * Set the factory of all manager services
     *
     * @param ManagerFactoryInterface $factory
     * @return void
     */
    public function setFactory(ManagerFactoryInterface $factory);

    /**
     * Set event dispatcher for this manager to trigger events
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @return void
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher);

    /**
     * Return a new instance of the class managed by this manager
     *
     * @return object
     */
    public function newInstance();

    /**
     * Tells the ObjectManager to save the given object into the database.
     *
     * NOTE: The persist operation always considers objects that are not yet known to
     * this ObjectManager as NEW. Do not pass detached objects to the persist operation.
     *
     * @param object $object The instance to make managed and persistent.
     *
     * @return void
     */
    public function save($object);

}