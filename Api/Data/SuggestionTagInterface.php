<?php
/**
 * Copyright © landofcoder.com All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\PwaCore\Api\Data;

interface SuggestionTagInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const TERM = 'term';
    const TITLE = 'title';
    const NUM_OF_RESULTS = 'num_of_results';
    const IS_FEATURED = 'is_featured';
    const IS_HOT = 'is_hot';

    /**
     * Get term
     * @return string|null
     */
    public function getTerm();

    /**
     * Set term
     * @param string $term
     * @return $this
     */
    public function setTerm($term);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get num_of_results
     * @return int|null
     */
    public function getNumOfResults();

    /**
     * Set num_of_results
     * @param int $num_of_results
     * @return $this
     */
    public function setNumOfResults($num_of_results);

    /**
     * Get is_featured
     * @return int|null
     */
    public function getIsFeatured();

    /**
     * Set is_featured
     * @param int $is_featured
     * @return $this
     */
    public function setIsFeatured($is_featured);

    /**
     * Get is_hot
     * @return int|null
     */
    public function getIsHot();

    /**
     * Set is_hot
     * @param int $is_hot
     * @return $this
     */
    public function setIsHot($is_hot);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Lof\PwaCore\Api\Data\SuggestionTagExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Lof\PwaCore\Api\Data\SuggestionTagExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Lof\PwaCore\Api\Data\SuggestionTagExtensionInterface $extensionAttributes
    );
}

