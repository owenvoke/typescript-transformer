<?php

namespace Spatie\TypeScriptTransformer\Tests\Transformers;

use MyCLabs\Enum\Enum;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Transformers\MyclabsEnumTransformer;

class MyclabsEnumTransformerTest extends TestCase
{
    private MyclabsEnumTransformer $transformer;

    protected function setUp() : void
    {
        parent::setUp();

        $this->transformer = new MyclabsEnumTransformer();
    }

    /** @test */
    public function it_will_check_if_an_enum_can_be_transformed()
    {
        $enum = new class('view') extends Enum {
            private const VIEW = 'view';
            private const EDIT = 'edit';
        };

        $noEnum = new class {
        };

        $this->assertTrue($this->transformer->canTransform(new ReflectionClass($enum)));
        $this->assertFalse($this->transformer->canTransform(new ReflectionClass($noEnum)));
    }

    /** @test */
    public function it_can_transform_an_enum()
    {
        $enum = new class('view') extends Enum {
            private const VIEW = 'view';
            private const EDIT = 'edit';
        };

        $type = $this->transformer->transform(new ReflectionClass($enum), 'Enum');

        $this->assertEquals("export type Enum = 'view' | 'edit';", $type->transformed);
        $this->assertTrue($type->missingSymbols->isEmpty());
    }
}
