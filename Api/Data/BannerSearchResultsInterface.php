<?php 
/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Banner search results.
 * @api
 * @since 2.0.0
 */

interface BannerSearchResultsInterface extends SearchResultsInterface
{
   
   /**
     * Get notification list.
     *
     * @return \Dzinehub\Banners\Api\Data\BannerInterface[]
     */
    public function getItems();

    /**
     * Set notification list.
     *
     * @param \Dzinehub\Banners\Api\Data\BannerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}