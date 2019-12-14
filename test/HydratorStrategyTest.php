<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Hydrator\ClassMethods;
use Laminas\Stdlib\Hydrator\HydratorInterface;

/**
 * @category   Laminas
 * @package    Laminas_Stdlib
 * @subpackage UnitTests
 * @group      Laminas_Stdlib
 */
class HydratorStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The hydrator that is used during testing.
     *
     * @var HydratorInterface
     */
    private $hydrator;

    public function setUp()
    {
        $this->hydrator = new ClassMethods();
    }

    public function testAddingStrategy()
    {
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);

        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());

        $this->assertAttributeCount(1, 'strategies', $this->hydrator);
    }

    public function testCheckStrategyEmpty()
    {
        $this->assertFalse($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testCheckStrategyNotEmpty()
    {
        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());

        $this->assertTrue($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testRemovingStrategy()
    {
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);

        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());
        $this->assertAttributeCount(1, 'strategies', $this->hydrator);

        $this->hydrator->removeStrategy('myStrategy');
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);
    }

    public function testRetrieveStrategy()
    {
        $strategy = new TestAsset\HydratorStrategy();
        $this->hydrator->addStrategy('myStrategy', $strategy);

        $this->assertEquals($strategy, $this->hydrator->getStrategy('myStrategy'));
    }

    public function testExtractingObjects()
    {
        $this->hydrator->addStrategy('entities', new TestAsset\HydratorStrategy());

        $entityA = new TestAsset\HydratorStrategyEntityA();
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(111, 'AAA'));
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(222, 'BBB'));

        $attributes = $this->hydrator->extract($entityA);

        $this->assertContains(111, $attributes['entities']);
        $this->assertContains(222, $attributes['entities']);
    }

    public function testHydratingObjects()
    {
        $this->hydrator->addStrategy('entities', new TestAsset\HydratorStrategy());

        $entityA = new TestAsset\HydratorStrategyEntityA();
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(111, 'AAA'));
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(222, 'BBB'));

        $attributes = $this->hydrator->extract($entityA);
        $attributes['entities'][] = 333;

        $this->hydrator->hydrate($attributes, $entityA);
        $entities = $entityA->getEntities();

        $this->assertCount(3, $entities);
    }

    /**
     * @dataProvider underscoreHandlingDataProvider
     */
    public function testWhenUsingUnderscoreSeparatedKeysHydratorStrategyIsAlwaysConsideredUnderscoreSeparatedToo($underscoreSeparatedKeys, $formFieldKey)
    {
        $hydrator = new ClassMethods($underscoreSeparatedKeys);

        $strategy = $this->getMock('Laminas\Stdlib\Hydrator\Strategy\StrategyInterface');

        $entity = new TestAsset\ClassMethodsUnderscore();
        $value = $entity->getFooBar();

        $hydrator->addStrategy($formFieldKey, $strategy);

        $strategy
            ->expects($this->once())
            ->method('extract')
            ->with($this->identicalTo($value))
            ->will($this->returnValue($value))
        ;

        $attributes = $hydrator->extract($entity);

        $strategy
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->identicalTo($value))
            ->will($this->returnValue($value))
        ;

        $hydrator->hydrate($attributes, $entity);
    }

    public function underscoreHandlingDataProvider()
    {
        return array(
            array(true, 'foo_bar'),
            array(false, 'fooBar'),
        );
    }
}
