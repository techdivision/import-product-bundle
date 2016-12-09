<?php

/**
 * TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionActionTest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Actions;

/**
 * Test class for the product bundle option action implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class ProductBundleOptionActionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test's the persist() method successfull.
     *
     * @return void
     */
    public function testPersistWithSuccess()
    {

        // create a persist processor mock instance
        $mockPersistProcessor = $this->getMockBuilder($processorInterface = 'TechDivision\Import\Actions\Processors\ProcessorInterface')
                                     ->setMethods(get_class_methods($processorInterface))
                                     ->getMock();
        $mockPersistProcessor->expects($this->once())
                             ->method('execute')
                             ->with($row = array())
                             ->willReturn(null);

        // create a mock for the product bundle option action
        $mockAction = $this->getMockBuilder('TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionAction')
                           ->setMethods(array('getPersistProcessor'))
                           ->getMock();
        $mockAction->expects($this->once())
                   ->method('getPersistProcessor')
                   ->willReturn($mockPersistProcessor);

        // test the persist() method
        $this->assertNull($mockAction->persist($row));
    }
}
