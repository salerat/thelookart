<?php
/**
 * Created by JetBrains PhpStorm.
 * User: solov
 * Date: 4/24/13
 * Time: 1:35 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Friend\Model;

use Friend\Entity\Friend;
use ZfcUser\Entity\User;

use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Id\UuidGenerator;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;
use Zend\Server\Reflection as ReflectionClass;

class FriendModel implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;

    public function getAllUsers()  {
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        //$this->getServiceLocator()->loadModule('ZfcUser');
        $users =$objectManager->getRepository('User\Entity\User')->createQueryBuilder()
            ->getQuery()->execute();

        $result=array();
        foreach($users as $us) {
            $us=array('id'=>$us->getId(), 'id'=>$us->getId(), 'username'=> $us->getUsername(), 'displayName'=>$us->getDisplayName(),'email'=>$us->getEmail());
            array_push($result,$us);

        }

        return $result;
    }

    public function addFriend($friendId,$userId) {
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        $friend=new Friend();
        $friend->friendId=new \MongoId($friendId);
        $friend->userId=new \MongoId($userId);

        $objectManager->persist($friend);
        $objectManager->flush();
    }


    public function deleteFriend($friendId,$userId) {
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        $users =$objectManager->getRepository('Friend\Entity\Friend')->createQueryBuilder()->remove()
        ->field('friendId')->equals(new \MongoId($friendId))
            ->field('userId')->equals(new \MongoId($userId))
            ->getQuery()->execute();
    }

    public function getFollowers($userId) {
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        $users = $objectManager->getRepository('Friend\Entity\Friend')->findBy(array('friendId' => new \MongoId($userId)));
        $result=array();
        foreach($users as $us) {
            array_push($result, get_object_vars($us));
        }
        return $result;
    }

    public function getFollowing($userId) {
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        $users = $objectManager->getRepository('Friend\Entity\Friend')->findBy(array('userId' => new \MongoId($userId)));
        $result=array();
        foreach($users as $us) {
            array_push($result, get_object_vars($us));
        }
        return $result;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


}