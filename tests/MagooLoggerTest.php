<?php

/**
 * This file is part of Pachico/Magoo. (https://github.com/pachico/magoo)
 *
 * @link https://github.com/pachico/magoo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Mariano F.co Benítez Mulet. (https://github.com/pachico/)
 * @license https://raw.githubusercontent.com/pachico/magoo/master/LICENSE.md MIT
 */

namespace Pachico\MagooTest;

use Pachico\Magoo\Magoo;
use Pachico\Magoo\MagooLogger;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MagooLoggerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MagooLogger
     */
    private $sut;
    private $logger;
    private $magoo;

    public function setUp()
    {
        parent::setUp();

        $this->logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->magoo = new Magoo();
        $this->magoo->pushEmailMask();
        $this->sut = new MagooLogger($this->logger, $this->magoo);
    }

    public function dataProviderLogLevels()
    {
        return [
            [LogLevel::ALERT],
            [LogLevel::CRITICAL],
            [LogLevel::DEBUG],
            [LogLevel::EMERGENCY],
            [LogLevel::ERROR],
            [LogLevel::INFO],
            [LogLevel::NOTICE],
            [LogLevel::WARNING]
        ];
    }

    /**
     * @dataProvider dataProviderLogLevels
     */
    public function testLoggingLogLevelsCallMagooAndRedactsContent(string $logLevel)
    {
        // Arrange
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';
        $this->logger->expects($this->once())->method($logLevel)->with($maskedString, [$maskedString]);
        // Act
        call_user_func_array([$this->sut, $logLevel], [$rawString, [$rawString]]);
    }
    
    public function testGetLoggerReturnsLogger()
    {
        // Act
        $output = $this->sut->getLogger();
        // Assert
        $this->assertSame($this->logger, $output);
    }
    
    /**
     * @dataProvider dataProviderLogLevels
     */
    public function testLogCallMagooAndRedactsContent(string $logLevel)
    {
        // Arrange
        $rawString = 'My email is foo@bar.com.';
        $maskedString = 'My email is ***@bar.com.';
        $this->logger->expects($this->once())->method('log')->with($logLevel, $maskedString, [$maskedString]);
        // Act
        $this->sut->log($logLevel, $rawString, [$rawString]);
    }
    
    public function testGetMaskManagerReturnsMagoo()
    {
        // Act
        $output = $this->sut->getMaskManager();
        // Assert
        $this->assertSame($this->magoo, $output);
    }
}
