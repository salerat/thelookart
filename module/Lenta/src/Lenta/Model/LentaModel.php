<?php
/**
 * Created by JetBrains PhpStorm.
 * User: solov
 * Date: 4/24/13
 * Time: 1:35 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Lenta\Model;

use Lenta\Entity\Lenta;
use Lenta\Entity\Item;
use Lenta\Entity\Image;

use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Id\UuidGenerator;
use User\Entity\User;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class LentaModel implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;

    public function createNewLenta($post,$userId) {
        $propArray=get_object_vars($post);
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');

        $lenta = new Lenta();
        $userId=new \MongoId($userId);
        $lenta->ownerId=$userId;
        $objectManager->persist($lenta);
        $objectManager->flush();
        $lentaId=$lenta->id;

        $item = new Item();

        $propArray['lentaId']=new \MongoId($lentaId);



        foreach ($propArray as $key => $value) {
            $item->$key = $value;
        }

        $objectManager->persist($item);
        $objectManager->flush();

        die(var_dump($item));
    }

    public function addImage() {
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        $image = new Image();
        $image->setName('Test image');
        $image->setFile('/home/solov/Downloads/453.png');

        $objectManager->persist($image);
        $objectManager->flush();

        $image = $objectManager->createQueryBuilder('Lenta\Entity\Image')
            ->field('name')->equals('Test image')
            ->getQuery()
            ->getSingleResult();

        header('Content-type: image/png;');
        echo $image->getFile()->getBytes();
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

public function test() {

}
}