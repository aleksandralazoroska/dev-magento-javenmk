<?php

include '../app/Mage.php';

Mage::app();

$setup = Mage::getModel('customer/entity_setup' , 'core_setup');

$entityTypeId = $setup->getEntityTypeId('customer');
$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->removeAttribute("customer_address", "company_register_id");
