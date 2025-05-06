<?php

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Block\Adminhtml\Banner\Edit;

use Magento\Backend\Block\Widget\Context;
use Dzinehub\Banners\Api\BannerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var BannerRepositoryInterface
     */
    protected $bannerRepository;

    /**
     * @param Context $context
     * @param BannerRepositoryInterface $bannerRepository
     */
    public function __construct(
        Context $context,
        BannerRepositoryInterface $bannerRepository
    ) {
        $this->context = $context;
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Return CMS block ID
     *
     * @return int|null
     */
    public function getBannerId()
    {
        try {
            return $this->bannerRepository->getById(
                $this->context->getRequest()->getParam('banner_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
