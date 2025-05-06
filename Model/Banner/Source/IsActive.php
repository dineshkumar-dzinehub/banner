<?php 

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 * Developer: srilekha@dzine-hub.com
 */
namespace Dzinehub\Banners\Model\Banner\Source;


use Magento\Framework\Data\OptionSourceInterface;

/**
 * class IsActive
 */
class IsActive implements OptionSourceInterface
{
	/**
	 * @var \Dzinehub\Banners\Model\Banner
	 */
   protected $_banner;

   /**
    * Init function
    * @param \Dzinehub\Banners\Model\Banner $banner
    */
   public function __construct(
   	 \Dzinehub\Banners\Model\Banner $banner
   ){

   	$this->_banner = $banner;

   }

   /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_banner->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}