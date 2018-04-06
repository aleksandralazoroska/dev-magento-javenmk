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

				Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

				$event = $observer->getEvent();
        $order = $event->getOrder();

				$productSku = '';
				$productAttributeSetId = '';

        foreach ($order->getAllVisibleItems() as $item) {
						$productSku = $item->getProduct()->getSku();
						$productAttributeSetId = $item->getProduct()->getAttributeSetId();
        }

				$attributeSetModel = Mage::getModel("eav/entity_attribute_set");
				$attributeSetModel->load($productAttributeSetId);
				$attributeSetName  = $attributeSetModel->getAttributeSetName();

				// Load customer by ID
				$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
				$businessPage = $customer->getBusinessPage();

				try {

					// This is for the Marketing packages
					if ($attributeSetName == 'Packages') {

						if (strpos($productSku, 'marketing-package-main-product-package') !== false ) {

							$marketingProductId = substr($productSku, strrpos( $productSku, '-' ) + 1);

							// Marketing promotion with marketing ID 4
							if ($marketingProductId == 4) {

								$product = Mage::getModel('catalog/product')->load($businessPage);
								$product->setIsBusinessPromoted(true);
								$product->setMarketingPromoted($marketingProductId);
								$product->setMarketingPromotedSearch(1);
								$product->save();

							} else {


								$product = Mage::getModel('catalog/product')->load($businessPage);
								$product->setIsBusinessPromoted(true);
								$product->setMarketingPromoted($marketingProductId);
								$product->setMarketingPromotedSearch(0);
								$product->save();

							}

						} else {

							// Load Product by Business ID
							$product = Mage::getModel('catalog/product')->load($businessPage);
							$product->setIsActive(true);
							$product->setPackageId($productSku);
							$product->save();

						}

					}

				} catch(Exception $e){

	        Mage::log($e->getMessage());

	      }

				//Mage::log("in", null, 'development.log');
				return false;

			}

}
