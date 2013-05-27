<?php

namespace Friend\Controller;


use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Zend\Form\Annotation\AnnotationBuilder;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\View\Helper\FormSelect;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FriendController extends AbstractActionController
{

    protected $friendModel;

    public function indexAction() {

    }

    public function listAction() {
        $friendModel = $this->getFriendModel();
        $users=$friendModel->getAllUsers();
        return new ViewModel(array(
            'users' => $users
        ));
    }

    public function addAction() {


    }

    public function addLentaAction() {

        $friendModel = $this->getFriendModel();

    }

    public function getFriendModel()
    {
        if (!$this->friendModel) {
            $sm = $this->getServiceLocator();
            $this->friendModel = $sm->get('Friend\Model\FriendModel');
        }
        return $this->friendModel;
    }
}
