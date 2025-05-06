<?php 

namespace Dzinehub\Banners\Model\Banner;

use Dzinehub\Banners\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
/**
     * @var \Dzinehub\Banners\Model\ResourceModel\Norification\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    protected $_storeManager;

      /**
     * @var Filesystem
     */
    private $fileInfo;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $bannerCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $bannerCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $bannerCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->_storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

         $desimageData = [];
         $mobimageData = [];

        foreach ($items as $banner) {
            $this->loadedData[$banner->getBannerId()] = $banner->getData();

            $desktopFileName = $banner->getDesktopImage();

            $mobileFileName = $banner->getMobileImage();
            
             if ($desktopFileName)
             {
                $stat = $this->getFileInfo()->getStat($desktopFileName);
                $mime = $this->getFileInfo()->getMimeType($desktopFileName);
                $desimageData['desktop_image'][0]['name'] = $desktopFileName;
                $desimageData['desktop_image'][0]['url'] = $this->getDesktopBannerImage($desktopFileName);
                $desimageData['desktop_image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
                $desimageData['desktop_image'][0]['type'] = $mime;

                $fullData = $this->loadedData;
               $this->loadedData[$banner->getId()] = array_merge($fullData[$banner->getId()], $desimageData);
            }  

            if ($mobileFileName)
             {
                $stat = $this->getFileInfo()->getMobStat($mobileFileName);
                $mime = $this->getFileInfo()->getMobMimeType($mobileFileName);
                $mobimageData['mobile_image'][0]['name'] = $mobileFileName;
                $mobimageData['mobile_image'][0]['url'] = $this->getMobileBannerImage($mobileFileName);
                $mobimageData['mobile_image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
                $mobimageData['mobile_image'][0]['type'] = $mime;

                $fullData = $this->loadedData;
               $this->loadedData[$banner->getId()] = array_merge($fullData[$banner->getId()], $mobimageData);
            }  
        }

        $data = $this->dataPersistor->get('dzbanners');
        if (!empty($data)) {
            $banner = $this->collection->getNewEmptyItem();
            $banner->setData($data);
            $this->loadedData[$banner->getBannerId()] = $banner->getData();
            $this->dataPersistor->clear('dzbanners');
        }

        return $this->loadedData;
    }

    protected function getDesktopBannerImage($desname){
        $url = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'dzinehub/desktopbanners/' . $desname;
        return $url;
    }

    protected function getMobileBannerImage($mobname){
        $url = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'dzinehub/mobilebanners/' . $mobname;
        return $url;
    }

    /**
     * Get FileInfo instance
     *
     * @return FileInfo
     *
     * @deprecated 101.1.0
     */
    private function getFileInfo()
    {
        if ($this->fileInfo === null) {
            $this->fileInfo = ObjectManager::getInstance()->get(FileInfo::class);
        }
        return $this->fileInfo;
    }
}