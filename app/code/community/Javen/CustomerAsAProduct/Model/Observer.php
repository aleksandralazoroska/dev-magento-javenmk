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

			public function marketingPromotedProduct(Varien_Event_Observer $observer) {

				$event = $observer->getEvent();
        $order = $event->getOrder();

				$productSku = '';

        foreach ($order->getAllVisibleItems() as $item) {
						$productSku = $item->getProduct()->getSku();
        }

				if (strpos($productSku, 'marketing-package-main-product-package') !== false ) {

					$marketingProductId = substr($productSku, strrpos( $productSku, '-' ) + 1);

					// Load customer by ID
					$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
					$businessPage = $customer->getBusinessPage();

					// Load Product by Business ID
					$product = Mage::getModel('catalog/product')->load($businessPage);
					$product->setMarketingPromoted($marketingProductId);
					$product->save();

				}

				//Mage::log($quote, null, 'development.log');

			}

}
