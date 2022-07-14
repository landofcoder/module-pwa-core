<?php
/**
 * Copyright © landofcoder.com All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\PwaCore\Model\Data;

use Lof\PwaCore\Api\Data\SuggestionTagInterface;

class SuggestionTag extends \Magento\Framework\Api\AbstractExtensibleObject implements SuggestionTagInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTerm()
    {
        return $this->_get(self::TERM);
    }

    /**
     * {@inheritdoc}
     */
    public function setTerm($term)
    {
        return $this->setData(self::TERM, $term);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getNumOfResults()
    {
        return $this->_get(self::NUM_OF_RESULTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setNumOfResults($num_of_results)
    {
        return $this->setData(self::NUM_OF_RESULTS, $num_of_results);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsFeatured()
    {
        return $this->_get(self::IS_FEATURED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsFeatured($is_featured)
    {
        return $this->setData(self::IS_FEATURED, $is_featured);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsHot()
    {
        return $this->_get(self::IS_HOT);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsHot($is_hot)
    {
        return $this->setData(self::IS_HOT, $is_hot);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Lof\PwaCore\Api\Data\SuggestionTagExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}

