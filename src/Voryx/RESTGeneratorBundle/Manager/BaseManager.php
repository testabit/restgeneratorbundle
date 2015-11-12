<?php

namespace Voryx\RESTGeneratorBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Voryx\RESTGeneratorBundle\Manager\Factory\ManagerFactoryInterface;

abstract class BaseManager implements ManagerInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @var \Voryx\RESTGeneratorBundle\Manager\Factory\ManagerFactoryInterface
     */
    protected $factory;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Class constructor
     *
     * @param string $class The class of the object to be managed by this manager service
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function setObjectManager(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function setFactory(ManagerFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function newInstance()
    {
        return new $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($object)
    {
        $this->em->persist($object);
    }
    
    /**
     * {@inheritdoc}
     */
    public function save($object)
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    /**
     * @param $name
     * @param Event $event
     */
    protected function dispatchEvent($name, Event $event)
    {
        $this->eventDispatcher->dispatch($name, $event);
    }

    /**
     * Get manager service for the given class
     *
     * @param $class
     * @return \Voryx\RESTGeneratorBundle\Manager\ManagerInterface
     */
    protected function getManagerForClass($class)
    {
        return $this->factory->getManager($class);
    }

    public function __call($name, $arguments)
    {
        if(!method_exists($this, $name) && strrpos($name, 'find') === 0) {
            return call_user_func_array(array($this->repository, $name), $arguments);
        }
    }
}
