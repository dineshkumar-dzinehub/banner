<?php 

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Model;

use Dzinehub\Banners\Api\BannerRepositoryInterface;
use Dzinehub\Banners\Api\Data;
use Dzinehub\Banners\Model\ResourceModel\Banner as ResourceBanner;
use Dzinehub\Banners\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class BannerRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BannerRepository implements BannerRepositoryInterface
{
   /**
     * @var Resourcebanner
     */
    protected $resource;

    /**
     * @var bannerFactory
     */
    protected $bannerFactory;

    /**
     * @var bannerCollectionFactory
     */
    protected $bannerCollectionFactory;

    /**
     * @var Data\bannerSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Dzinehub\Banners\Api\Data\BannerInterfaceFactory
     */
    protected $dataBannerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ResourceBanner $resource
     * @param BannerFactory $BannerFactory
     * @param Data\BannerInterfaceFactory $dataBannerFactory
     * @param BannerCollectionFactory $bannerCollectionFactory
     * @param Data\BannerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceBanner $resource,
        BannerFactory $bannerFactory,
        Data\BannerInterfaceFactory $dataBannerFactory,
        BannerCollectionFactory $bannerCollectionFactory,
        Data\BannerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource = $resource;
        $this->bannerFactory = $bannerFactory;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBannerFactory = $dataBannerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

     /**
     * Save Banner data
     *
     * @param \Dzinehub\Banners\Api\Data\BannerInterface $banner
     * @return Banner
     * @throws CouldNotSaveException
     */
    public function save(Data\BannerInterface $banner)
    {
        
        if (empty($banner->getStoreId())) {
            $banner->setStoreId($this->storeManager->getStore()->getId());
        }
        try {
            $this->resource->save($banner);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $banner;
    }
    
    /**
     * Load Banner Data by given banner entity
     * @param  string $bannerId 
     * @return banner
     * @throws \Magento\Framework\Exception\NoSuchEntityException               
     */
    public function getById($bannerId)
    {
       $banner = $this->bannerFactory->create();
       $this->resource->load($banner, $bannerId);
       if(!$banner->getId()){
       	 throw new NoSuchEntityException(__('Banner with Id "%1" does not exist', $bannerId));
       }

       return $banner;
    }

    /**
     * Load Banner data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Dzinehub\Banners\Api\Data\BannerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Dzinehub\Banners\Model\ResourceModel\Banner\Collection $collection */
        $collection = $this->bannerCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\BlockSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Banner
     *
     * @param \Dzinehub\Banners\Api\Data\BannerInterface $banner
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\BannerInterface $banner)
    {
        try {
            $this->resource->delete($banner);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Banner by given Banner Identity
     *
     * @param string $bannerId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($bannerId)
    {
        return $this->delete($this->getById($bannerId));
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 100.2.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface::class
            );
        }
        return $this->collectionProcessor;
    }
    
    
}