<?php

class Javen_CustomerAsAProduct_Model_Observer {

			public function customerLogin(Varien_Event_Observer $observer) {
				//Mage::dispatchEvent('admin_session_user_login_success', array('user'=>$user));
				//$user = $observer->getEvent()->getUser();
				//$user->doSomething();

        //Mage::log('Ilche Bedelovski Developer', null, 'development.log');

			}

}
