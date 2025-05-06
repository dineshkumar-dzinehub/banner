<?php 
/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Api\Data;

/**
 * Banner interface.
 * @api
 * @since 2.0.0
 */
interface BannerInterface
{
   /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
    */
   
   const BANNER_ID = 'banner_id';
   const BANNER_TITLE = 'banner_title';
   const CONTENT = 'content';
   const URL = 'url';
   const DESKTOP_IMAGE = 'desktop_image';
   const MOBILE_IMAGE = 'mobile_image';
   const SORT_ORDER = 'sort_order';
   const CUSTOMER_GROUP_IDS = 'customer_group_ids';
   const CREATION_TIME = 'creation_time';
   const UPDATE_TIME = 'update_time';
   const IS_ACTIVE = 'is_active';
   /**#@-*/

   /**
    * Get Banner id
    * 
    * @return int | null
    * */
   public function getBannerId();

   /**
     * Set Banner id
     *
     * @param int $bannerId
     * @return $this
     */
    public function setBannerId($bannerId);
  
    /**
     * Get Banner Title
     * 
     * @return string|null
     */
    public function getBannerTitle();
    
    /**
     * Set Banner Title
     * 
     * @param string $bannerTitle
     * @return $this
     */
    public function setBannerTitle($bannerTitle);

    /**
     * Get Content
     * 
     * @return string|null
     */
    public function getContent();

    /**
     * Set Content
     * 
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Get URL
     * 
     * @return string|null
     */
    public function getURL();

    /**
     * Set URL
     * 
     * @param string $url
     * @return $this
     */
    public function setURL($url);

    /**
     * Get Desktop Image
     * 
     * @return string|null
     */
    public function getDesktopImage();

    /**
     * Set Desktop Image
     * 
     * @param string $desktopImage
     * @return $this
     */
    public function setDesktopImage($desktopImage);

    /**
     * Get Mobile Image
     * 
     * @return string|null
     */
    public function getMobileImage();

    /**
     * Set Mobile Image
     * 
     * @param string $mobileImage
     * @return $this
     */
    public function setMobileImage($mobileImage);

    /**
     * Get Sort Order
     * 
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set Sort Order
     * 
     * @param string $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * get Customer GroupIds
     * 
     * @return string
     */
    public function getCustomerGroupIds();
    
    /**
     * Set Customer Group Ids
     * @param string $customerGroupIds
     * @return $this 
     */
    public function setCustomerGroupIds($customerGroupIds);

    /**
     * Get Active
     * 
     * @return boolean|null
     */
    public function getIsActive();

    /**
     * Set Active
     * 
     * @param bool|int $isActive
     * @return $this
     */
    public function setIsActive($isActive);
    
    /**
     * Get created at time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Set created at time
     *
     * @param string $creationTime
     * @return $this
     */
    public function setCreationTime($creationTime);

    /**
     * Get updated at time
     *
     * @return string|null
     */
    public function getUpdatedTime();

    /**
     * Set updated at time
     *
     * @param string $updatedTime
     * @return $this
     */
    public function setUpdatedTime($updatedTime);

}