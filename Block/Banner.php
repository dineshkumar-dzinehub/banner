<?php 

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Block;


class Banner extends \Magento\Framework\View\Element\Template 
{
	/**
	 * @var $bannerFactory
	 */
    protected $bannerFactory;

    /**
     * @var $storeManager
     */
    protected $storeManager;
    
    /**
     * Dzinehub Banner Helper
     * @var $bannerHelper
     */
    protected $bannerHelper;

     /**
     * @var CustomerGroupRepository
     */
    protected $groupRepository;
    
    /**
     * @var $customerSessio
     */
    protected $customerSession;
   
   /**
    * [__construct description]
    * @param \Magento\Framework\View\Element\Template\Context $context        
    * @param \Dzinehub\Banners\Model\BannerFactory            $bannerFactory  
    * @param \Magento\Store\Model\StoreManagerInterface       $storeManager   
    * @param \Dzinehub\Banners\Helper\Data                    $bannerHelper   
    * @param \Magento\Customer\Api\GroupRepositoryInterface   $groupRepository
    * @param \Magento\Customer\Model\Session                  $customerSession
    */
	public function __construct(
		 \Magento\Framework\View\Element\Template\Context $context,
		 \Dzinehub\Banners\Model\BannerFactory $bannerFactory,
		 \Magento\Store\Model\StoreManagerInterface $storeManager,
		 \Dzinehub\Banners\Helper\Data $bannerHelper,
		 \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
		 \Magento\Customer\Model\Session $customerSession
	){
		$this->bannerFactory = $bannerFactory;
		$this->storeManager = $storeManager;
		$this->bannerHelper = $bannerHelper;
		$this->groupRepository = $groupRepository;
		$this->customerSession = $customerSession;
		parent::__construct($context);

	}

	/**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Get Media URL
     * 
     * @return string
     */
    public function getMediaUrl()
	{
    $mediaUrl = $this->_storeManager
                     ->getStore()
                     ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    return $mediaUrl;
	}

    /**
     * Banner Collection
     * @return array
     */
	public function _bannerCollection()
	{
		$bannerStatus = $this->bannerHelper->getBannerStatus();
		$customerGroupId = $this->customerSession->getCustomerGroupId();

		if($bannerStatus)
		{
			$result = $this->bannerFactory->create()->getCollection()
		               ->addFieldToFilter('is_active', 1)
		               ->addFieldToFilter('customer_group_ids',array('finset' => $customerGroupId))
		               ->addStoreFilter($this->getStoreId())
		               ->setOrder('sort_order','ASC');
			return $result;
		}
		else{
		    return false;
		}
		
	}
}