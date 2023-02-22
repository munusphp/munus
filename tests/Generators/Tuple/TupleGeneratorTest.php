<?php

declare(strict_types=1);

namespace Munus\Tests\Generators\Tuple;

use Munus\Generators\Tuple\TupleGeneratorConfiguration;
use Munus\Tests\Generators\Tuple\Fixtures\FakeClassPersister;
use PHPUnit\Framework\TestCase;

class TupleGeneratorTest extends TestCase
{
    private const MAX_TUPLE_SIZE = 3;

    private FakeClassPersister $fakeClassPersister;

    public function setUp(): void
    {
        $this->fakeClassPersister = new FakeClassPersister();
        $tupleGenerator = TupleGeneratorConfiguration::getTupleGenerator($this->fakeClassPersister);
        $tupleGenerator->generateTuples(self::MAX_TUPLE_SIZE);
    }

    public function testGenerateTuple0(): void
    {
        $expected = file_get_contents(__DIR__.'/Fixtures/tuple_0_class_content.txt');

        [$directory, $className, $content] = $this->fakeClassPersister->getFileSavedFromBeginning(1);

        self::assertEquals('Tuple', $directory);
        self::assertEquals('Tuple0', $className);
        self::assertEquals($expected, $content);
    }

    public function testGenerateTuple1(): void
    {
        $expected = file_get_contents(__DIR__.'/Fixtures/tuple_1_class_content.txt');

        [$directory, $className, $content] = $this->fakeClassPersister->getFileSavedFromBeginning(2);

        self::assertEquals('Tuple', $directory);
        self::assertEquals('Tuple1', $className);
        self::assertEquals($expected, $content);
    }

    public function testGenerateTuple2(): void
    {
        $expected = file_get_contents(__DIR__.'/Fixtures/tuple_2_class_content.txt');

        [$directory, $className, $content] = $this->fakeClassPersister->getFileSavedFromBeginning(3);

        self::assertEquals('Tuple', $directory);
        self::assertEquals('Tuple2', $className);
        self::assertEquals($expected, $content);
    }
}
