<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\Magoo;

use Monolog\Logger;
use Monolog\Handler\TestHandler;
use Psr\Log\LogLevel;

/**
 * Test for MagooLogger
 */
class MagooLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *  Contains PSR3 log levels
     *
     * @var array
     */
    private $logLevels = [
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::DEBUG,
        LogLevel::EMERGENCY,
        LogLevel::ERROR,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING
    ];

    /**
     * Testing that string passed to MagooLogger using log levels gets
     * masked and added in logger
     */
    public function testLogLevels()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask();

        $logger = new Logger('app');
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $magooLogger = new MagooLogger($logger, $magoo);

        // Act
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';

        foreach ($this->logLevels as $logLevel) {
            $magooLogger->{$logLevel}($rawString);
        }

        $records = $handler->getRecords();

        // Assert
        foreach (array_keys($this->logLevels) as $key) {
            $this->assertSame($maskedString, $records[$key]['message']);
        }
    }

    /**
     * Testing that string and context passed to MagooLogger using log levels gets
     * masked and added in logger
     */
    public function testLogLevelsWithContext()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask();

        $logger = new Logger('app');
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $magooLogger = new MagooLogger($logger, $magoo);

        // Act
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';

        foreach ($this->logLevels as $logLevel) {
            $magooLogger->{$logLevel}($rawString, [$rawString]);
        }

        $records = $handler->getRecords();

        // Assert
        foreach (array_keys($this->logLevels) as $key) {
            $this->assertSame($maskedString, $records[$key]['message']);
            $this->assertSame($maskedString, $records[$key]['context'][0]);
        }
    }

    /**
     * Testing that String passed to MagooLogger using arbitrary log level gets
     * masked and added in logger
     */
    public function testLogMethod()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask();

        $logger = new Logger('app');
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $magooLogger = new MagooLogger($logger, $magoo);

        // Act
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';
        $magooLogger->log(100, $rawString);
        $records = $handler->getRecords();

        // Assert
        $this->assertSame($maskedString, $records[0]['message']);
    }

    /**
     * Testing that string and context passed to MagooLogger using arbitrary log level gets
     * masked and added in logger
     */
    public function testLogMethodWithContext()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask();

        $logger = new Logger('app');
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $magooLogger = new MagooLogger($logger, $magoo);

        // Act
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';
        $magooLogger->log(100, $rawString, [$rawString]);
        $records = $handler->getRecords();

        // Assert
        $this->assertSame($maskedString, $records[0]['message']);
        $this->assertSame($maskedString, $records[0]['context'][0]);
    }

    /**
     * Testing that calling invalid methods throw exceptions
     */
    public function testingInvalidLoggerMethodException()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask();

        $logger = new Logger('app');
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $magooLogger = new MagooLogger($logger, $magoo);

        // Act
	$this->assertSame(false, is_callable(array($magooLogger, 'foo')));
    }

    public function testingLoggerExceptionPropagation()
    {
        // Arrange
        $this->setExpectedException('\InvalidArgumentException', 'bar');
        $magoo = new Magoo();
        $magoo->pushEmailMask();
        $loggerMockBuilder = $this->getMockBuilder('Monolog\Logger');
        $loggerMockBuilder->disableOriginalConstructor();
        $loggerMock = $loggerMockBuilder->getMock();
        $loggerMock->expects($this->once())->method('info')->willThrowException(new \InvalidArgumentException('bar'));
        $magooLogger = new MagooLogger($loggerMock, $magoo);
        // Act
        $magooLogger->info('foo');
    }

    /**
     * Testing Logger getter
     */
    public function testGetLogger()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask();

        $logger = new Logger('app');
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $magooLogger = new MagooLogger($logger, $magoo);

        // Act
        // Assert
        $this->assertSame($logger, $magooLogger->getLogger());
    }

    /**
     * Testing Magoo
     */
    public function testGetMaskManager()
    {
        // Arrange
        $magoo = new Magoo();
        $magoo->pushEmailMask();

        $logger = new Logger('app');
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $magooLogger = new MagooLogger($logger, $magoo);

        // Act
        // Assert
        $this->assertSame($magoo, $magooLogger->getMaskManager());
    }
}
