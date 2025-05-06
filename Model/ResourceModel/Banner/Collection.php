<?php 

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Model\ResourceModel\Banner;

use Dzinehub\Banners\Api\Data\BannerInterface;
use Dzinehub\Banners\Model\ResourceModel\AbstractCollection;

/**
 * CMS Block Collection
 */
class Collection extends AbstractCollection
{

     /**
     * @var string
     */
    protected $_idFieldName = 'banner_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'dzbanners_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'dzbanners_collection';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);

        $this->performAfterLoad('dzinehub_banners_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

     /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dzinehub\Banners\Model\Banner::class,\Dzinehub\Banners\Model\ResourceModel\Banner::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['banner_id'] = 'main_table.banner_id';
    }

    /**
     * Returns pairs banner_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('banner_id', 'title');
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $this->joinStoreRelationTable('dzinehub_banners_store', $entityMetadata->getLinkField());
    }
}