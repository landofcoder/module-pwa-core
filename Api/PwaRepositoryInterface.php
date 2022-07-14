<?php
/**
 * Copyright © landofcoder.com All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\PwaCore\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PwaRepositoryInterface
{
    /**
     * Retrieve Featured Products matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFeaturedProducts(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Retrieve Search Suggestion Tags
     * @param string|null $keyword
     * @param string|null $storeId
     * @return \Lof\PwaCore\Api\Data\SuggestionTagInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSuggestionTags($keyword = null, $storeId = null);

    /**
     * Retrieve Search Featured Suggestion Tags
     * @param int $limit = 10
     * @param string|null $storeId = null
     * @return \Lof\PwaCore\Api\Data\SuggestionTagInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFeaturedTags($limit = 10, $storeId = null);
}

