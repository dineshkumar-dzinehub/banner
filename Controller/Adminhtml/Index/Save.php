<?php 

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Dzinehub\Banners\Api\BannerRepositoryInterface;
use Dzinehub\Banners\Model\Banner;
use Dzinehub\Banners\Model\BannerFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;	
use Magento\Framework\App\Config\ScopeConfigInterface;

class Save extends \Dzinehub\Banners\Controller\Adminhtml\Index
{
   
   /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var BannerFactory
     */
    private $bannerFactory;

    /**
     * @var BlockRepositoryInterface
     */
    private $notificationRepository;

    /**
     * @var ScopeConfiguration
     */
    protected $_scopeConfig;

    /**
     * @var CustomerGroupRepository
     */
    protected $groupRepository;

    /**
     * @var $storeManager
     */
    protected $_storeManager;

    protected $groupCollection;

    protected $bannerRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param BannerFactory|null $notificationFactory
     * @param BannerRepositoryInterface|null $notificationRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        BannerFactory $bannerFactory = null,
        BannerRepositoryInterface $bannerRepository = null,
        ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\ResourceModel\Group\Collection $groupCollection
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->bannerFactory = $bannerFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(bannerFactory::class);
        $this->bannerRepository = $bannerRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(BannerRepositoryInterface::class);
        $this->_scopeConfig = $scopeConfig;
        $this->groupRepository = $groupRepository;
        $this->_storeManager = $storeManager;
        $this->groupCollection = $groupCollection;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save pushnotification action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
    	/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
/*
        var_dump($data);
        die;*/


        if($data) {
        	if(isset($data['is_active']) && $data['is_active'] === 'true') {
        		$data['is_active'] = Banner::STATUS_ENABLED;
        	}
        	if(empty($data['banner_id'])){
        		$data['banner_id'] = null;
        	}

            $data['customer_group_ids'] = implode(',', $data['customer_group_ids']);

        	/** @var  \Dzinehub\PushBanner\Model\Banner $notification */
        	$model = $this->bannerFactory->create();

        	$id = $this->getRequest()->getParam('banner_id');

        	if($id) {
        		try{
        			$model = $this->bannerRepository->getById($id);
        		} catch(LocalizedException $e) {
        			$this->messageManager->addErrorMessage(__('Banner no longer exists'));
        			return $resultRedirect->setPath('*/*/');
        		}
        	}

           if (isset($data['desktop_image']) && is_array($data['desktop_image'])) {
            if (!empty($data['desktop_image']['delete'])) {
                $data['desktop_image'] = null;

            } else {
                if (isset($data['desktop_image'][0]['name']) && isset($data['desktop_image'][0]['tmp_name'])) {
                        $data['desktop_image'] = $data['desktop_image'][0]['name'];
                }
                else if(isset($data['desktop_image'][0]['name'])){
                     $data['desktop_image'] = $data['desktop_image'][0]['name'];
                }
                 else {
                    unset($data['desktop_image']);
                }
            }
        }
        else{
            $data['desktop_image'] = null;
        }
        if (isset($data['mobile_image']) && is_array($data['mobile_image'])) {
            if (!empty($data['mobile_image']['delete'])) {
                $data['mobile_image'] = null;

            } else {
                if (isset($data['mobile_image'][0]['name']) && isset($data['mobile_image'][0]['tmp_name'])) {
                        $data['mobile_image'] = $data['mobile_image'][0]['name'];
                }
                 else if(isset($data['mobile_image'][0]['name'])){
                     $data['mobile_image'] = $data['mobile_image'][0]['name'];
                }
                 else {
                    unset($data['mobile_image']);
                }
            }
        }
        else{
            $data['mobile_image'] = null;
        }

             $model->setData($data);

            try {
                $this->bannerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the banner.'));
                $this->dataPersistor->clear('dzbanners');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['banner_id' => $model->getBannerId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the banner.'));
            }

            $this->dataPersistor->set('dzbanners', $data);
            return $resultRedirect->setPath('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}