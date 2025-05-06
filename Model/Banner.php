<?php 

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Model;


use Dzinehub\Banners\Api\Data\BannerInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Dzinehub Banner model
 */
class Banner extends AbstractModel implements BannerInterface, IdentityInterface
{
   /**
    * Dzinehub Banner Cache Tag
    */
   const CACHE_TAG = 'dzbanners';

   /**#@+
     * Banner's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**#@-*/

     /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dzbanners';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dzinehub\Banners\Model\ResourceModel\Banner::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getBannerId()];
    }

    /**
    * Get Banner id
    * 
    * @return int
    * */
   public function getBannerId()
   {
   	   return $this->getData(SELF::BANNER_ID);
   }

   /**
     * Get Banner Title
     * 
     * @return string
     */
    public function getBannerTitle()
    {
       return $this->getData(SELF::BANNER_TITLE);
    }

    /**
     * Get Content
     * 
     * @return string
     */
    public function getContent()
    {
       return $this->getData(SELF::CONTENT);
    }

    /**
     * Get created at time
     *
     * @return string
     */
    public function getCreationTime()
    {
       return $this->getData(SELF::CREATION_TIME);
    }

    /**
     * Get updated at time
     *
     * @return string
     */
    public function getUpdatedTime()
    {
       return $this->getData(SELF::UPDATE_TIME);
    }

    /**
     * Get URL
     * 
     * @return string
     */
    public function getURL()
    {
       return $this->getData(SELF::URL);
    }

    /**
     * Get Desktop Image
     * 
     * @return string
     */
    public function getDesktopImage()
    {
       return $this->getData(SELF::DESKTOP_IMAGE);
    }

     /**
     * Get Mobile Image
     * 
     * @return string
     */
    public function getMobileImage()
    {
       return $this->getData(SELF::MOBILE_IMAGE);
    }

     /**
     * Get Sort Order
     * 
     * @return string
     */
    public function getSortOrder()
    {
       return $this->getData(SELF::SORT_ORDER);
    }

    /**
     * get Customer Group Ids
     * 
     * @return string
     */
    public function getCustomerGroupIds()
    {
    	return $this->getData(SELF::CUSTOMER_GROUP_IDS);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function getIsActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $bannerId
     * @return BannerInterface
     */
    public function setBannerId($bannerId)
    {
        return $this->setData(self::BANNER_ID, $bannerId);
    }

    /**
     * Set Banner Title
     * 
     * @param string $bannerTitle
     * @return BannerInterface
     */
    public function setBannerTitle($bannerTitle)
    {
        return $this->setData(self::BANNER_TITLE, $bannerTitle);
    }

     /**
     * Set Content
     * 
     * @param string $content
     * @return BannerInterface
     */
    public function setContent($content)
    {
    	return $this->setData(self::CONTENT, $content);
    }

    /**
     * Set created at time
     *
     * @param string $creationTime
     * @return BannerInterface
     */
    public function setCreationTime($creationTime)
    {
    	return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set updated at time
     *
     * @param string $updatedTime
     * @return BannerInterface
     */
    public function setUpdatedTime($updatedTime)
    {
    	return $this->setData(self::UPDATE_TIME, $updatedTime);
    }

    /**
     * Set URL
     * 
     * @param string $url
     * @return BannerInterface
     */
    public function setURL($url)
    {
    	return $this->setData(self::URL, $url);
    }

    /**
     * Set Desktop Image
     * 
     * @param string $desktopImage
     * @return BannerInterface
     */
    public function setDesktopImage($desktopImage)
    {
    	return $this->setData(self::DESKTOP_IMAGE, $desktopImage);
    }

     /**
     * Set Image
     * 
     * @param string $mobileImage
     * @return BannerInterface
     */
    public function setMobileImage($mobileImage)
    {
        return $this->setData(self::MOBILE_IMAGE, $mobileImage);
    }

    /**
     * Set Sort Order
     * 
     * @param string $sortOrder
     * @return BannerInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Set Customer Group Ids
     * @param  string $customerGroupIds
     * @return BannerInterface
     */
    public function setCustomerGroupIds($customerGroupIds)
    {
    	return $this->setData(self::CUSTOMER_GROUP_IDS, $customerGroupIds);
    }
    
    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return BlockInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

     /**
     * Receive banner store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

     /**
     * Prepare Banners's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }


}