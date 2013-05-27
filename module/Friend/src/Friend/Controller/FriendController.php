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
        $friendModel = $this->getFriendModel();

    }

    public function followersAction() {
        $friendModel = $this->getFriendModel();
        $users= $friendModel->getFollowers($this->zfcUserAuthentication()->getIdentity()->getId());
        return new ViewModel(array(
            'users' => $users
        ));
    }

    public function followingAction() {
        $friendModel = $this->getFriendModel();
        $users=$friendModel->getFollowing($this->zfcUserAuthentication()->getIdentity()->getId());
        die(var_dump($users));
        return new ViewModel(array(
            'users' => $users
        ));
        die(var_dump($users));
    }

    public function deleteFriendAction() {
        $friendModel = $this->getFriendModel();
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $friendModel->deleteFriend($id,$this->zfcUserAuthentication()->getIdentity()->getId());
        return $this->redirect()->toUrl('/friends/list');
    }

    public function addFriendAction() {
        $friendModel = $this->getFriendModel();
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $friendModel->addFriend($id,$this->zfcUserAuthentication()->getIdentity()->getId());
        return $this->redirect()->toUrl('/friends/list');

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
