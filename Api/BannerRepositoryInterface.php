<?php 
/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Api;


use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Banners block CRUD interface.
 * @api
 * @since 100.0.2
 */
interface BannerRepositoryInterface
{
   /**
     * Save Banners.
     *
     * @param \Dzinehub\Banners\Api\Data\BannerInterface $banner
     * @return \Dzinehub\Banners\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\BannerInterface $banner);

    /**
     * Retrieve Banners.
     *
     * @param int $bannerId
     * @return \Dzinehub\Banner\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($bannerId);

    /**
     * Retrieve Banners matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dzinehub\Banners\Api\Data\BannerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Banners.
     *
     * @param \Dzinehub\Banners\Api\Data\BannerInterface $banner
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\BannerInterface $banner);

    /**
     * Delete Banners by ID.
     *
     * @param int $bannerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($bannerId);
}