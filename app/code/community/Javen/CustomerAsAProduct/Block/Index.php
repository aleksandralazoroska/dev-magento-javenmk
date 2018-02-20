<?php

class Javen_CustomerAsAProduct_Block_Index extends Mage_Core_Block_Template {

    public function getBusinessDetails() {

      $customer = Mage::getSingleton('customer/session')->getCustomer();
      $businessPage = $customer->getBusinessPage();

      return $businessPage;

    }

}
