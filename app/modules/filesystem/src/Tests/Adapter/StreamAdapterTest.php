<?php

namespace Pagekit\Filesystem\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use Pagekit\Tests\FileUtil;
use Pagekit\Filesystem\Adapter\StreamAdapter;
use Pagekit\Filesystem\Filesystem;
use Pagekit\Filesystem\StreamWrapper;

class StreamAdapterTest extends TestCase
{
    use FileUtil;

    protected ?Filesystem $file = null;
    protected ?string $fixtures = null;
    protected $workspace;

    public function setUp(): void
    {
        $this->file      = new Filesystem;
        $this->fixtures  = dirname(__DIR__).'/Fixtures';
        $this->workspace = $this->getTempDir('filesystem_');

        $this->file->registerAdapter('temp', new StreamAdapter($this->workspace));

        StreamWrapper::setFilesystem($this->file);
    }

    public function tearDown(): void
    {
        $this->removeDir($this->workspace);
        stream_wrapper_unregister('temp');
    }

    public function testCopyFile(): void
    {
        $file1 = $this->fixtures.'/file1.txt';

        $this->assertTrue($this->file->copy($file1, 'temp://file1.txt'));
        $this->assertTrue($this->file->exists('temp://file1.txt'));
    }

    public function testCopyFileNotFound(): void
    {
        $file3 = $this->fixtures.'/file3.txt';

        $this->assertFalse($this->file->exists($file3));
        $this->assertFalse($this->file->copy($file3, 'temp://file3.txt'));
    }

    public function testCopyDir(): void
    {
        $this->assertTrue($this->file->copyDir($this->fixtures, 'temp://'));
        $this->assertTrue($this->file->exists('temp://file1.txt'));
        $this->assertTrue($this->file->exists('temp://file2.txt'));
    }

    public function testCopyDirNotFound(): void
    {
        $dir = __DIR__.'/Directory';

        $this->assertFalse($this->file->exists($dir));
        $this->assertFalse($this->file->copyDir($dir, 'temp://'));
    }

    public function testDeleteFile(): void
    {
        $file1 = $this->fixtures.'/file1.txt';

        $this->assertTrue($this->file->copy($file1, 'temp://file1.txt'));
        $this->assertTrue($this->file->delete('temp://file1.txt'));
        $this->assertFalse($this->file->exists('temp://file1.txt'));
    }

    public function testDeleteFileNotFound(): void
    {
        $file3 = 'temp://file3.txt';

        $this->assertFalse($this->file->exists($file3));
        $this->assertFalse($this->file->delete($file3));
    }

    public function testDeleteDir(): void
    {
        $dir = 'temp://Directory';

        $this->assertTrue($this->file->copyDir($this->fixtures, $dir));
        $this->assertTrue($this->file->delete($dir));
        $this->assertFalse($this->file->exists($dir));
    }
}
