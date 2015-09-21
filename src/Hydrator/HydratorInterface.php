<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Stdlib\Hydrator;

use Zend\Hydrator\HydrationInterface as BaseHydrationInterface;
use Zend\Stdlib\Extractor\ExtractionInterface;

/**
 * @deprecated Use Zend\Hydrator\HydratorInterface from zendframework/zend-hydrator instead.
 */
interface HydratorInterface extends BaseHydrationInterface, ExtractionInterface
{
}
