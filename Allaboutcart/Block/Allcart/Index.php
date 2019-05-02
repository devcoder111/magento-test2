<?php
/**
 * Copyright © 2015 AM . All rights reserved.
 */
namespace AM\Allaboutcart\Block\Allcart;
use AM\Allaboutcart\Block\BaseBlock;
class Index extends BaseBlock
{
	protected $_cart; 
	protected $_collection; 
	protected $_formKey; 
	
	public function __construct( 
			\AM\Allaboutcart\Block\Context $context, 
			\Magento\Checkout\Model\Cart $cart, 
			\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collection, 
			\Magento\Framework\Data\Form\FormKey $formKey
	)
    {
        $this->_devToolHelper = $context->getAllaboutcartHelper();
		$this->_config = $context->getConfig();
        $this->_urlApp=$context->getUrlFactory()->create();
        $this->_cart=$cart; 
        $this->_collection=$collection; 
        $this->_formKey=$formKey; 
		parent::__construct($context);
	
    }

	public function getCartItems() { 
 		$cart =  $this->_cart; 
		$itemsCollection = $cart->getQuote()->getItemsCollection(); 
		return $itemsCollection; 
	} 
	public function getProductCollections() { 
		//$this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
		$productCollectionFactory = $this->_collection; 
		$productCollection = $productCollectionFactory->create(); 
		$productCollection = $productCollection->addAttributeToSelect('*'); 
		return $productCollection; 
	} 
	
	public function getFormKey() { 
		$formKey = $this->_formKey; 
		return $formKey->getFormKey(); 
	} 
	
}
