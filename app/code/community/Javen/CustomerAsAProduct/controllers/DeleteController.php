<?php

class Javen_CustomerAsAProduct_DeleteController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {

    }

    // A method for sending email reminder for the basic packages
    public function NotifyPackagesAction() {

      $productCollection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToSelect('payment_date_package')
                        ->addAttributeToSelect('sku')
                        ->addAttributeToSelect('id')
                        ->addAttributeToSelect('email')
                        ->addAttributeToSelect('package_id')
                        ->addAttributeToSelect('payment_email_sent')
                        ->addAttributeToSelect('is_active');

      $packages = array('package-yearly-3' => 365, 'package-quartal-2' => 92, 'package-monthly-1' => 31);

      foreach ($productCollection as $product) {

        $productId = $product->getId();
        $productSku = $product->getSku();
        $paymentDatePackage = $product->getPaymentDatePackage();
        $productEmail = $product->getEmail();
        $productPackageId = $product->getPackageId();
        $productIsActive = $product->getIsActive();
        $productEmailSent = $product->getPaymentEmailSent();

        // Change it to true
        if (($productIsActive == true) && isset($paymentDatePackage) && array_key_exists($productPackageId, $packages) && $productId == 150) {

          $customer = Mage::getModel("customer/customer");
          $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
          $customer->loadByEmail($productEmail);

          $today = date("Ymd");
          //$today = date("20180730");

          $paymentTimestamp = strtotime($paymentDatePackage);
          $todayTimestamp = strtotime($today);

          $difference = $todayTimestamp - $paymentTimestamp;
          $days = floor($difference / (60*60*24) );
          $notifyDate = $packages[$productPackageId] - 10;
          $deactivateDate = $packages[$productPackageId] + 10;

          if ($days > $notifyDate && $productEmailSent == 0) {

            $templateId = 1;
            $emailTemplate = Mage::getModel('core/email_template')->load($templateId);

            $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplate);

            // Sending variables to the template
            $emailTemplateVariables = array(
              'customer_name' => $customer->getName(),
            );

            $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
            $processedSubject = "Payment Notify Packages";

            // Email details
            $mail = Mage::getModel('core/email')
                 ->setToName($customer->getName())
                 ->setToEmail($customer->getEmail())
                 ->setBody($processedTemplate)
                 ->setSubject($processedSubject)
                 ->setFromEmail('contact@javen.mk')
                 ->setFromName('Javen Advertajzing dooel')
                 ->setType('html');

            try {

                // Sending email
                $mail->send();

                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

                // Setting up email flag
                $productModel = Mage::getModel('catalog/product')->load($productId);
                $productModel->setPaymentEmailSent(1);
                $productModel->save();

                Mage::log("Email has been sent to: " . $customer->getName() . " for product " . $productId, null, 'cron.log');

            } catch (Exception $ex) {

                Mage::log($e->getMessage());

            }

          } elseif ($days > $deactivateDate && $productEmailSent == 1) {

            try {

                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

                // Deactivating product
                $productModel = Mage::getModel('catalog/product')->load($productId);
                $productModel->setPaymentEmailSent(0);
                $productModel->setIsActive(false);
                $productModel->save();

                Mage::log("Product " . $productId . " has been deactivated", null, 'cron.log');

            } catch (Exception $ex) {

                Mage::log($e->getMessage());

            }

          }

        }

      }

    }

    // A method for sending email reminder for the promotions
    public function NotifyPromotionsAction() {

      $productCollection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToSelect('payment_date_promotions')
                        ->addAttributeToSelect('sku')
                        ->addAttributeToSelect('id')
                        ->addAttributeToSelect('email')
                        ->addAttributeToSelect('marketing_promoted')
                        ->addAttributeToSelect('is_active')
                        ->addAttributeToSelect('payment_email_sent_promotions');


      $promotions = array('1' => 31,
                          '2' => 31,
                          '3' => 31,
                          '4' => 31,
                          '5' => 31,
                          '6' => 31,
                    );

      foreach ($productCollection as $product) {

        $productId = $product->getId();
        $productSku = $product->getSku();
        $paymentDatePromotions = $product->getPaymentDatePromotions();
        $productEmail = $product->getEmail();
        $productMarketingPromoted = $product->getMarketingPromoted();
        $productIsActive = $product->getIsActive();
        $productEmailSent = $product->getPaymentEmailSentPromotions();

        if (($productIsActive == true) && isset($paymentDatePromotions) && array_key_exists($productMarketingPromoted, $promotions)) {

          $customer = Mage::getModel("customer/customer");
          $customer->setWebsiteId(1);
          $customer->loadByEmail($productEmail);

          $today = date("Ymd");

          $paymentTimestamp = strtotime($paymentDatePromotions);
          $todayTimestamp = strtotime($today);

          $difference = $todayTimestamp - $paymentTimestamp;
          $days = floor($difference / (60*60*24) );

          $notifyDate = $promotions[$productMarketingPromoted] - 5;
          $deactivateDate = $promotions[$productMarketingPromoted] + 5;

          if ($days > $notifyDate && $productEmailSent == 0) {

            $templateId = 3;
            $emailTemplate = Mage::getModel('core/email_template')->load($templateId);

            $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplate);

            // Sending variables to the template
            $emailTemplateVariables = array(
              'customer_name' => $customer->getName(),
            );

            $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
            $processedSubject = "Payment Notify Promotions";

            // Email details
            $mail = Mage::getModel('core/email')
                 ->setToName($customer->getName())
                 ->setToEmail($customer->getEmail())
                 ->setBody($processedTemplate)
                 ->setSubject($processedSubject)
                 ->setFromEmail('contact@javen.mk')
                 ->setFromName('Javen Advertajzing dooel')
                 ->setType('html');

            try {

   	            // Sending email
   	            //$mail->send();

   	            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

   	            // Setting up email flag
   	            //$productModel = Mage::getModel('catalog/product')->load($productId);
   	            //$productModel->setPaymentEmailSentPromotions(1);
   	            //$productModel->save();

                Mage::log("Email has been sent to: " . $customer->getName() . " for product " . $productId, null, 'cron.log');

            } catch (Exception $ex) {

                Mage::log($e->getMessage());

   	        }

          } elseif ($days > $deactivateDate && $productEmailSent == 1) {

            try {

                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

                // Deactivating product
                //$productModel = Mage::getModel('catalog/product')->load($productId);
                //$productModel->setPaymentEmailSentPromotions(0);
                //$productModel->setMarketingPromoted(0);
                //$productModel->save();

                Mage::log("Product " . $productId . " has been deactivated", null, 'cron.log');

            } catch (Exception $ex) {

                Mage::log($e->getMessage());

            }

          }

        }

      }

    }

}