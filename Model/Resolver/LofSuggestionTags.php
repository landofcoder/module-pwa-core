<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_PwaCore
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\PwaCore\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Lof\PwaCore\Api\PwaRepositoryInterface;

class LofSuggestionTags implements ResolverInterface
{

    /**
     * @var PwaRepositoryInterface
     */
    private $apiRepository;

    /**
     * @param PwaRepositoryInterface $apiRepository
     */
    public function __construct(
        PwaRepositoryInterface $apiRepository
    ) {
        $this->apiRepository = $apiRepository;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $keyword = isset($args['keyword']) ? $args['keyword'] : "";
        $foundTerms = $this->apiRepository->getSuggestionTags($keyword, $storeId);
        if($foundTerms){
            $items = [];
            foreach($foundTerms as $_item){
                $items[] = $_item->__toArray();
            }
            return $items;
        }
        return [];
    }
}

