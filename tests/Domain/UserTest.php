<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class UserTest extends TestCase
{
    public function testCreateUserWithValidData(): void
    {
        $name = 'Alice';
        $id = 1;

        $user = new User($id, $name);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($name, $user->getName());
    }

    public function testCreateUserWithEmptyNameThrowsException(): void
    {
        $name = '';
        $id = 1;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le nom ne peut pas être vide');

        new User($id, $name);
    }

    public function testCreateUserWithNegativeIdThrowsException(): void
    {
        $name = 'Alice';
        $id = -1;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'ID doit être positif');

        new User($id, $name);
    }

    public function testCreateUserWithWhitespaceOnlyNameThrowsException(): void
    {
        $name = '   ';
        $id = 1;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le nom ne peut pas être vide');

        new User($id, $name);
    }
}

