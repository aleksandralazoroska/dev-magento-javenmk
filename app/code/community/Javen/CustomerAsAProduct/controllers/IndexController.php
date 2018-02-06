<?php

class Javen_CustomerAsAProduct_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {

	    $this->loadLayout();
      $this->renderLayout();

    }

    public function postAction() {

      //Fetch submited params
      $params = $this->getRequest()->getParams();

      print_r($params);

    }

}
