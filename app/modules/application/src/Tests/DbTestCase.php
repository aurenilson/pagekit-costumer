<?php

namespace Pagekit\Tests;

use PHPUnit\Framework\TestCase;
abstract class DbTestCase extends TestCase
{
    use DbUtil;

    protected $connection;

    public function setUp(): void
    {
        try {

            $this->connection = $this->getSharedConnection();

        } catch (\Exception $e) {
            $this->markTestSkipped(sprintf('Unable to establish connection. (%s)', $e->getMessage()));
            return;
        }
    }
}