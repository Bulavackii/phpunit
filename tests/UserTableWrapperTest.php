<?php

namespace App\Tests;

use App\UserTableWrapper;
use PHPUnit\Framework\TestCase;

class UserTableWrapperTest extends TestCase
{
    private UserTableWrapper $table;

    protected function setUp(): void
    {
        $this->table = new UserTableWrapper();
    }

    /**
     * @dataProvider dataProviderInsert
     */
    public function testInsert(array $values, array $expected): void
    {
        $this->table->insert($values);
        $this->assertEquals($expected, $this->table->get());
    }

    public static function dataProviderInsert(): array
    {
        return [
            [['id' => 1, 'name' => 'John'], [['id' => 1, 'name' => 'John']]],
            [['id' => 2, 'name' => 'Jane'], [['id' => 2, 'name' => 'Jane']]],
        ];
    }

    /**
     * @dataProvider dataProviderUpdate
     */
    public function testUpdate(int $id, array $updateValues, array $expected): void
    {
        $this->table->insert(['id' => 1, 'name' => 'Old Name']);
        $this->table->update($id, $updateValues);
        $this->assertEquals($expected, $this->table->get());
    }

    public static function dataProviderUpdate(): array
    {
        return [
            [1, ['name' => 'Doe'], [['id' => 1, 'name' => 'Doe']]],
            [1, ['name' => 'Smith'], [['id' => 1, 'name' => 'Smith']]],
            [2, ['name' => 'Smith'], [['id' => 1, 'name' => 'Old Name']]],
        ];
    }

    /**
     * @dataProvider dataProviderDelete
     */
    public function testDelete(array $initialData, int $idToDelete, array $expected): void
    {
        foreach ($initialData as $data) {
            $this->table->insert($data);
        }
        $this->table->delete($idToDelete);
        $this->assertEquals($expected, $this->table->get());
    }

    public static function dataProviderDelete(): array
    {
        return [
            [
                [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Jane']],
                1,
                [['id' => 2, 'name' => 'Jane']]
            ],
            [
                [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Jane']],
                2,
                [['id' => 1, 'name' => 'John']]
            ],
        ];
    }

    public function testGet(): void
    {
        $this->table->insert(['id' => 1, 'name' => 'John']);
        $this->table->insert(['id' => 2, 'name' => 'Jane']);
        $this->assertEquals([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane']
        ], $this->table->get());
    }
}