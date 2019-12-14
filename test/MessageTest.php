<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Http;

use Laminas\Stdlib\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testMessageCanSetAndGetContent()
    {
        $message = new Message();
        $ret = $message->setContent('I can set content');
        $this->assertInstanceOf('Laminas\Stdlib\Message', $ret);
        $this->assertEquals('I can set content', $message->getContent());
    }

    public function testMessageCanSetAndGetMetadataKeyAsString()
    {
        $message = new Message();
        $ret = $message->setMetadata('foo', 'bar');
        $this->assertInstanceOf('Laminas\Stdlib\Message', $ret);
        $this->assertEquals('bar', $message->getMetadata('foo'));
        $this->assertEquals(array('foo' => 'bar'), $message->getMetadata());
    }

    public function testMessageCanSetAndGetMetadataKeyAsArray()
    {
        $message = new Message();
        $ret = $message->setMetadata(array('foo' => 'bar'));
        $this->assertInstanceOf('Laminas\Stdlib\Message', $ret);
        $this->assertEquals('bar', $message->getMetadata('foo'));
    }

    public function testMessageGetMetadataWillUseDefaultValueIfNoneExist()
    {
        $message = new Message();
        $this->assertEquals('bar', $message->getMetadata('foo', 'bar'));
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataSet()
    {
        $message = new Message();

        $this->setExpectedException('Laminas\Stdlib\Exception\InvalidArgumentException');
        $message->setMetadata(new \stdClass());
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataGet()
    {
        $message = new Message();

        $this->setExpectedException('Laminas\Stdlib\Exception\InvalidArgumentException');
        $message->getMetadata(new \stdClass());
    }

    public function testMessageToStringWorks()
    {
        $message = new Message();
        $message->setMetadata(array('Foo' => 'bar', 'One' => 'Two'));
        $message->setContent('This is my content');
        $expected = "Foo: bar\r\nOne: Two\r\n\r\nThis is my content";
        $this->assertEquals($expected, $message->toString());
    }
}
