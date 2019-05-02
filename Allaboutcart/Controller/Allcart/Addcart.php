<?php
/**
 *
 * Copyright © 2015 AMcommerce. All rights reserved.
 */
namespace AM\Allaboutcart\Controller\Allcart;

class Addcart extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
	
	
	protected $_product; 
	
	
	protected $_cart; 
	
	protected $_messageManager; 
	
	protected $_option; 
    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory, 
		\Magento\Catalog\Model\Product $product, 
		\Magento\Checkout\Model\Cart $cart, 
		\Magento\Framework\Message\ManagerInterface $messageManager , 
		\Magento\Catalog\Model\Product\Option $option
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->_product = $product;
        $this->_cart = $cart;
        $this->_messageManager = $messageManager; 
        $this->_option = $option; 
    }
	
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {
		$post = $this->getRequest()->getPostValue();
		
		if( count($post) ) { 
			if( count($post['productids']) ) { 
			
				$errMsg = $successMsg = "" ; 
				foreach($post['productids'] as $eachProductId) { 
					$productObject = $this->_product->load( (int) $eachProductId); 
					
					$customOptions = $this->_option->getProductOptionCollection($productObject);

					if(count($customOptions) > 0){
						$errMsg .= $productObject->getName() . " has required option. Please choose option first. "; 
					} else { 
						if($productObject->isAvailable()) { 
							$params = array(
								'form_key' => $post['form_key'],
								'product' => $eachProductId, 
								'qty'   =>1
							);              
							$this->_cart->addProduct($productObject, $params); 
							unset($post['productids'][0]);
							$this->_cart->addProductsByIds($post['productids']);
							$this->_cart->save(); 
							$successMsg .= " Product(s) added to cart."; 
						} 
					}
					$productObject = NULL; 
					unset($productObject); 
					break; 
					
				} 
				
				if($successMsg != "") $this->_messageManager->addSuccess(__($successMsg)); 
				if($errMsg != "") $this->_messageManager->addNotice(__($errMsg)); 
				
			} 
		} 
		
		$resultRedirect = $this->resultRedirectFactory->create(); 
		$resultRedirect->setPath('*/*/index'); 
		return $resultRedirect; 
        
    }
}
