<?php 
/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 * Developer: srilekha@dzine-hub.com
 */

namespace Dzinehub\Banners\Model\ResourceModel;

use Dzinehub\Banners\Api\Data\BannerInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb; 
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Banner extends AbstractDb
{

	 /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var \Magento\Catalog\Model\ImageUploader
     */
    private $desktopImageUploader;

    /**
     * @var \Magento\Catalog\Model\ImageUploader
     */
    private $mobileImageUploader;

    protected $fileSystem;


    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        Filesystem $fileSystem,
        $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        $this->fileSystem = $fileSystem;
        parent::__construct($context, $connectionName);
    }
   
   /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dzinehub_banners', 'banner_id');
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(BannerInterface::class)->getEntityConnection();
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Dzinehub\Banners\Model\Banner|AbstractModel $object
     * @return Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['cbs' => $this->getTable('dzinehub_banners_store')],
                $this->getMainTable() . '.' . $linkField . ' = cbs.' . $linkField,
                ['store_id']
            )
                ->where('is_active = ?', 1)
                ->where('cbs.store_id in (?)', $stores)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['cbs' => $this->getTable('dzinehub_banners_store')], 'store_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . $linkField . ' = cb.' . $linkField,
                []
            )
            ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :banner_id');

        return $connection->fetchCol($select, ['banner_id' => (int)$id]);
    }

     private function getDesktopImageUploader()
    {
        if ($this->desktopImageUploader === null) {
            $this->desktopImageUploader = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Dzinehub\Banners\BannerDesktopImageUpload::class);
        }

        return $this->desktopImageUploader;
    }

    private function getMobileImageUploader()
    {
        if ($this->mobileImageUploader === null) {
            $this->mobileImageUploader = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Dzinehub\Banners\BannerMobileImageUpload::class);
        }

        return $this->mobileImageUploader;
    }

    function fileExists($filePath)
    {
      return is_file($filePath) && file_exists($filePath);
    }



    /**
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);

        $mediapath = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();

        $dzdesktopImagePath = "dzinehub/tmp/desktopbanners/".$object->getDesktopImage();
        $dzmobileImagePath = "dzinehub/tmp/mobilebanners/".$object->getMobileImage();

       if($object->getDesktopImage() && $this->fileExists($mediapath.$dzdesktopImagePath) ){
           $result = $this->getDesktopImageUploader()->moveFileFromTmp($object->getDesktopImage());
       }
       if($object->getMobileImage() && $this->fileExists($mediapath.$dzmobileImagePath) ){
           $result = $this->getMobileImageUploader()->moveFileFromTmp($object->getMobileImage());
       }   
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}