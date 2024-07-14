<?php

namespace StreamData\infrastructure\persistence\tests;

use PHPUnit\Framework\TestCase;
use StreamData\infrastructure\persistence\InDisk;

error_reporting(E_ALL);


final class InDiskTest extends TestCase
{

    static $fileDir = __DIR__ . '/.db';

    private function createDbFile()
    {
        if (file_exists(self::$fileDir)) {
            exec(sprintf('rm %s', self::$fileDir));
        }

        exec(sprintf('touch %s', self::$fileDir));
    }

    public function testShouldReturnTheValueIfFound(): void
    {
        // Arrange
        $this->createDbFile();
        $instance = InDisk::getInstance(__DIR__ . '/.db');
        $instance->set('key', 'mock');

        // Act
        $value = $instance->get('key');

        // Asserts
        $this->assertSame('mock', $value);
    }

    public function testShouldRemoveTheValue(): void
    {
        // Arrange
        $this->createDbFile();
        $instance = InDisk::getInstance(self::$fileDir);
        $instance->set('key', 'mock');
        $instance->set('key-two', 'mock-two');

        // Act
        $instance->remove('key');
        $value = $instance->get('key');
        $secondValue = $instance->get('key-two');

        // Asserts
        $this->assertNull($value);
        $this->assertEquals('mock-two', $secondValue);
    }

    public function testShouldReturnNullIfValueNotExists(): void
    {
        // Arrange
        $instance = InDisk::getInstance(self::$fileDir);

        // Act
        $value = $instance->get('key');

        // Asserts
        $this->assertNull($value);
    }

    public function testShouldReturnTheSameInstanceIfExists(): void
    {
        // Arrange
        $instance = InDisk::getInstance(self::$fileDir);

        // Act
        $expectedInstance = InDisk::getInstance(self::$fileDir);

        // Asserts
        $this->assertSame($expectedInstance, $instance);
    }
}
