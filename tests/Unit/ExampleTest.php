<?php

declare(strict_types=1);

namespace Eheca\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class ExampleTest extends TestCase
{
    #[TestDox('Basic test that always passes')]
    public function test_basic_test_passes(): void
    {
        $this->assertTrue(true, 'Basic test should always pass');
    }

    #[TestDox('String concatenation works as expected')]
    public function test_string_concatenation_works(): void
    {
        $result = 'Hello' . ' ' . 'World';
        $this->assertSame('Hello World', $result, 'Strings should concatenate with a space');
    }

    #[TestDox('Array operations work as expected')]
    public function test_array_operations_work(): void
    {
        $array = [1, 2, 3];
        
        $this->assertCount(3, $array, 'Array should contain 3 elements');
        $this->assertContainsEquals(2, $array, 'Array should contain the value 2');
        $this->assertNotContains(4, $array, 'Array should not contain the value 4');
    }
}
