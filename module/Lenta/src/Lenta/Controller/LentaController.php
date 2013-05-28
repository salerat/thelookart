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
        $lentaModel = $this->getLentaModel();
        $lentas = $lentaModel -> getAllLentas();
        return new ViewModel(array(
            'lentas' => $lentas
        ));
  //      die(var_dump($lentas));
    }

    public function addAction() {

        $builder = new AnnotationBuilder();
        $form = $builder->createForm('Lenta\Entity\Item');
        return new ViewModel(array(
           'form' => $form,
        ));
    }

    public function addLentaAction() {
        $request = $this->getRequest();
        $post = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );
        $lentaModel = $this->getLentaModel();
        $lentaModel->createNewLenta($post,$this->zfcUserAuthentication()->getIdentity()->getId());
        return $this->redirect()->toRoute('lenta');
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
