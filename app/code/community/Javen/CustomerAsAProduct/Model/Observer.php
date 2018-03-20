<?php

class Javen_CustomerAsAProduct_Model_Observer {

			public function customerRegisterSuccess(Varien_Event_Observer $observer) {
				/*
				$response = Mage::app()->getFrontController()->getResponse();
    		$response->setRedirect(Mage::getUrl('customerasaproduct'));

				//Mage::log($response, null, 'development.log');

				try {

    			Mage::app()->getFrontController()->sendResponse();

				} catch(Exception $e){

	        Mage::log($e->getMessage());

	      }
				*/

			}

}
