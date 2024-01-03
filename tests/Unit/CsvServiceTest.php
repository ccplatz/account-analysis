<?php

namespace Tests\Unit;

use App\Exceptions\WrongValueCountException;
use App\Services\CsvService;
use PHPUnit\Framework\TestCase;

class CsvServiceTest extends TestCase
{
    private CsvService $csvService;

    public function setUp(): void
    {
        parent::setUp();
        $this->csvService = app(CsvService::class);
    }

    public function test_get_values_from_csv_string(): void
    {
        $csvString = 'a;b;c';
        $expected = ['a', 'b', 'c'];

        $result = $this->csvService->getValuesFromCsvString($csvString);

        $this->assertEquals($expected, $result);
    }

    public function test_get_associative_array_from_lines(): void
    {
        $lines = ['a;b;c', '1;2;3', 'x;y;z'];
        $expected = [
            [
                'a' => '1',
                'b' => '2',
                'c' => '3'
            ],
            [
                'a' => 'x',
                'b' => 'y',
                'c' => 'z'
            ],
        ];

        $result = $this->csvService->getAssociativeArrayFromLines($lines);

        $this->assertEquals($expected, $result);
    }

    public function test_throws_exception_for_wrong_count_of_fields(): void
    {
        $this->expectException(WrongValueCountException::class);
        $lines = ['a;b;c', '1;2;3', 'x;y'];

        $result = $this->csvService->getAssociativeArrayFromLines($lines);
    }
}