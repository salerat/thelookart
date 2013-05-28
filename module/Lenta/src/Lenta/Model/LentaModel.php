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
use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class LentaModel implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;

    public function createNewLenta($post,$userId) {
//        $propArray=get_object_vars($post);
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');

        $lenta = new Lenta();
        $userId=new \MongoId($userId);
        $lenta->ownerId=$userId;
        $objectManager->persist($lenta);
        $objectManager->flush();
        $lentaId=$lenta->id;
        $favItem = 0;
        $itemsArray = $this->createItemsArray($post);
        foreach($itemsArray as $propArray){
            $item = new Item();
            $image = $this->addImage($propArray['image']);

            $item->imageLink = new \MongoId($image);

            $propArray['lentaId']=new \MongoId($lentaId);


            if ($favItem == 0){
                $lenta -> favItem = new \MongoId($item->id);
                $favItem = 1;

            }
    //        die(var_dump($item));
            foreach ($propArray as $key => $value) {
                $item->$key = $value;
            }

            $objectManager->persist($item);
            $objectManager->flush();
        }

    }

    public function addImage($postImg) {

        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        $image = new Image();
        $image->setName($postImg['tmp_name']);
        $image->setFile($postImg['tmp_name']);

        $objectManager->persist($image);
        $objectManager->flush();

        return $image->getId();


    }

    public function getSingleLenta() {

    }

    public function getAllLentas()  {
        $objectManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        //$this->getServiceLocator()->loadModule('ZfcUser');
        $lentas =$objectManager->getRepository('Lenta\Entity\Lenta')->createQueryBuilder()
            ->getQuery()->execute();

        $result=array();
        foreach($lentas as $len) {
            $item = $objectManager->createQueryBuilder('Lenta\Entity\Item')
                ->field('lentaId')->equals(new \MongoId($len->id))
                ->getQuery()
                ->getSingleResult();

            $imageLink= $objectManager->createQueryBuilder('Lenta\Entity\Image')
                ->field('id')->equals(new \MongoId($item->imageLink))
                ->getQuery()
                ->getSingleResult();
            $imageBytes=$imageLink->getFile()->getBytes();
           ;
            $len=array('id'=>$len->id, 'item'=> $item, 'image'=>$imageBytes );

            array_push($result,$len);
        }

        return $result;
    }

    public function createItemsArray($post) {
 //       die(var_dump($post));
        $itemsArray = array();
        for($i=0; $i<3; $i++){
            $text_key = 'text_'.$i;
            $image_key = 'image_'.$i;
            $text =  $post[$text_key];
            $image =  $post[$image_key];
            $t_arr = array( 'text' => $text, 'image' => $image);
            array_push($itemsArray, $t_arr);
        }
        return $itemsArray;
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