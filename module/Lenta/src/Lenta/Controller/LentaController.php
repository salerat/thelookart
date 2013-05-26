<?php

namespace Lenta\Controller;


use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Zend\Form\Annotation\AnnotationBuilder;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\View\Helper\FormSelect;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LentaController extends AbstractActionController
{

    protected $lentaModel;

    public function indexAction() {

    }

    public function addAction() {

        $builder = new AnnotationBuilder();
        $form = $builder->createForm('Lenta\Entity\Item');
        return new ViewModel(array(
           'form' => $form,
        ));
    }

    public function addLentaAction() {
        $post= $this->getRequest()->getPost();
        $lentaModel = $this->getLentaModel();
        $lentaModel->createNewLenta($post,$this->zfcUserAuthentication()->getIdentity()->getId());
    }

    public function getLentaModel()
    {
        if (!$this->lentaModel) {
            $sm = $this->getServiceLocator();
            $this->lentaModel = $sm->get('Lenta\Model\LentaModel');
        }
        return $this->lentaModel;
    }
}
