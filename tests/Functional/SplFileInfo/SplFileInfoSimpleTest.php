<?php

namespace PhuxtilTests\Functional\SplFileInfo;

use SplFileInfo;
use Phuxtil\SplFileInfo\VirtualSplFileInfo;
use PHPUnit\Framework\TestCase;

class SplFileInfoSimpleTest extends TestCase
{
    const TEST_FILE_REAL = \TESTS_FIXTURE_DIR . 'test.txt';
    const TEST_FILE_VIRTUAL = \TESTS_FIXTURE_DIR . 'non_existing.txt';

    public function test_path_and_properties()
    {
        $fileInfo = new SplFileInfo(static::TEST_FILE_VIRTUAL);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);

        $this->comparePaths($fileInfo, $virtualFileInfo);
        $this->assertInfoProperties($virtualFileInfo);
    }

    public function test_setters_and_getters()
    {
        $virtualFileInfo = new VirtualSplFileInfo('/invalid.foo');
        $fileInfo = new SplFileInfo('/invalid.foo');

        $this->comparePaths($fileInfo, $virtualFileInfo);

        $virtualFileInfo->setFile(true);
        $this->assertTrue($virtualFileInfo->isFile());
        $virtualFileInfo->setDir(true);
        $this->assertTrue($virtualFileInfo->isDir());
        $virtualFileInfo->setLink(true);
        $this->assertTrue($virtualFileInfo->isLink());

        $timestamp = time();
        $virtualFileInfo->setATime($timestamp);
        $this->assertEquals($timestamp, $virtualFileInfo->getATime());
        $virtualFileInfo->setMTime($timestamp);
        $this->assertEquals($timestamp, $virtualFileInfo->getMTime());
        $virtualFileInfo->setCTime($timestamp);
        $this->assertEquals($timestamp, $virtualFileInfo->getCTime());

        $virtualFileInfo->setInode(1234);
        $this->assertEquals(1234, $virtualFileInfo->getInode());

        $virtualFileInfo->setSize(123);
        $this->assertEquals(123, $virtualFileInfo->getSize());

        $virtualFileInfo->setPerms(0664);
        $this->assertEquals(0664, $virtualFileInfo->getPerms());

        $virtualFileInfo->setOwner(11);
        $this->assertEquals(11, $virtualFileInfo->getOwner());

        $virtualFileInfo->setGroup(2);
        $this->assertEquals(2, $virtualFileInfo->getGroup());

        $virtualFileInfo->setType('file');
        $this->assertEquals('file', $virtualFileInfo->getType());

        $virtualFileInfo->setWritable(true);
        $this->assertTrue($virtualFileInfo->isWritable());

        $virtualFileInfo->setReadable(true);
        $this->assertTrue($virtualFileInfo->isReadable());

        $virtualFileInfo->setExecutable(true);
        $this->assertTrue($virtualFileInfo->isExecutable());
    }

    public function test_toArray()
    {
        $virtualFileInfo = new VirtualSplFileInfo('/invalid.foo');

        $data = $virtualFileInfo->toArray();

        $this->assertEquals($virtualFileInfo->getPath(), $data['path']);
        $this->assertEquals($virtualFileInfo->getFileInfo(), $data['filename']);
        $this->assertEquals($virtualFileInfo->getBasename(), $data['basename']);
        $this->assertEquals($virtualFileInfo->getPathname(), $data['pathname']);
        $this->assertEquals($virtualFileInfo->getExtension(), $data['extension']);
        $this->assertEquals($virtualFileInfo->getRealPath(), $data['realPath']);
        $this->assertEquals($virtualFileInfo->getATime(), $data['aTime']);
        $this->assertEquals($virtualFileInfo->getMTime(), $data['mTime']);
        $this->assertEquals($virtualFileInfo->getCTime(), $data['cTime']);
        $this->assertEquals($virtualFileInfo->getInode(), $data['inode']);
        $this->assertEquals($virtualFileInfo->getSize(), $data['size']);
        $this->assertEquals($virtualFileInfo->getPerms(), $data['perms']);
        $this->assertEquals($virtualFileInfo->getOwner(), $data['owner']);
        $this->assertEquals($virtualFileInfo->getGroup(), $data['group']);
        $this->assertEquals($virtualFileInfo->getType(), $data['type']);
        $this->assertEquals($virtualFileInfo->isWritable(), $data['writable']);
        $this->assertEquals($virtualFileInfo->isReadable(), $data['readable']);
        $this->assertEquals($virtualFileInfo->isExecutable(), $data['executable']);
        $this->assertEquals($virtualFileInfo->isFile(), $data['file']);
        $this->assertEquals($virtualFileInfo->isDir(), $data['dir']);
        $this->assertEquals($virtualFileInfo->isLink(), $data['link']);
    }

    public function test_fromArray()
    {
        $virtualFileInfo = new VirtualSplFileInfo('/invalid.foo');

        $virtualFileInfo->fromArray(
            [
                'aTime' => 123,
                'mTime' => 456,
                'cTime' => 789,
                'inode' => 222,
                'size' => 333,
                'perms' => 0755,
                'owner' => 1,
                'group' => 2,
                'type' => 'dir',
                'writable' => true,
                'readable' => true,
                'executable' => true,
                'file' => false,
                'dir' => false,
                'link' => false,
            ]
        );

        $data = $virtualFileInfo->toArray();

        $this->assertEquals($virtualFileInfo->getPath(), $data['path']);
        $this->assertEquals($virtualFileInfo->getFileInfo(), $data['filename']);
        $this->assertEquals($virtualFileInfo->getBasename(), $data['basename']);
        $this->assertEquals($virtualFileInfo->getPathname(), $data['pathname']);
        $this->assertEquals($virtualFileInfo->getExtension(), $data['extension']);
        $this->assertEquals($virtualFileInfo->getRealPath(), $data['realPath']);
        $this->assertEquals($virtualFileInfo->getATime(), $data['aTime']);
        $this->assertEquals($virtualFileInfo->getMTime(), $data['mTime']);
        $this->assertEquals($virtualFileInfo->getCTime(), $data['cTime']);
        $this->assertEquals($virtualFileInfo->getInode(), $data['inode']);
        $this->assertEquals($virtualFileInfo->getSize(), $data['size']);
        $this->assertEquals($virtualFileInfo->getPerms(), $data['perms']);
        $this->assertEquals($virtualFileInfo->getOwner(), $data['owner']);
        $this->assertEquals($virtualFileInfo->getGroup(), $data['group']);
        $this->assertEquals($virtualFileInfo->getType(), $data['type']);
        $this->assertEquals($virtualFileInfo->isWritable(), $data['writable']);
        $this->assertEquals($virtualFileInfo->isReadable(), $data['readable']);
        $this->assertEquals($virtualFileInfo->isExecutable(), $data['executable']);
        $this->assertEquals($virtualFileInfo->isFile(), $data['file']);
        $this->assertEquals($virtualFileInfo->isDir(), $data['dir']);
        $this->assertEquals($virtualFileInfo->isLink(), $data['link']);
    }

    public function test_getFileInfo()
    {
        $fileInfo = new SplFileInfo(static::TEST_FILE_VIRTUAL);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);

        $this->comparePaths($fileInfo, $virtualFileInfo);
        $this->comparePaths($fileInfo, $virtualFileInfo->getFileInfo());
        $this->comparePaths($fileInfo, $virtualFileInfo->getFileInfo(VirtualSplFileInfo::class));
        $this->assertInfoProperties($virtualFileInfo->getFileInfo(VirtualSplFileInfo::class));
    }

    public function test_getPathInfo()
    {
        $fileInfo = new SplFileInfo(static::TEST_FILE_VIRTUAL);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);

        $this->comparePaths(
            $fileInfo->getPathInfo(),
            $virtualFileInfo->getPathInfo()
        );
    }

    protected function comparePaths(SplFileInfo $expected, SplFileInfo $actual)
    {
        $this->assertEquals($expected->getPath(), $actual->getPath());
        $this->assertEquals($expected->getFilename(), $actual->getFilename());
        $this->assertEquals($expected->getExtension(), $actual->getExtension());
        $this->assertEquals($expected->getBasename(), $actual->getBasename());
        $this->assertEquals($expected->getBasename('.txt'), $actual->getBasename('.txt'));
        $this->assertEquals($expected->getPathname(), $actual->getPathname());
    }

    protected function assertInfoProperties(SplFileInfo $fileInfo)
    {
        $this->assertEquals(-1, $fileInfo->getPerms());
        $this->assertEquals(-1, $fileInfo->getInode());
        $this->assertEquals(-1, $fileInfo->getSize());
        $this->assertEquals(-1, $fileInfo->getOwner());
        $this->assertEquals(-1, $fileInfo->getGroup());
        $this->assertEquals(-1, $fileInfo->getATime());
        $this->assertEquals(-1, $fileInfo->getMTime());
        $this->assertEquals(-1, $fileInfo->getCTime());

        $this->assertEquals('virtual', $fileInfo->getType());
        $this->assertFalse($fileInfo->getRealPath());

        $this->assertEquals(-1, $fileInfo->getLinkTarget());
        $this->assertEquals(-1, $fileInfo->isWritable());
        $this->assertEquals(-1, $fileInfo->isReadable());
        $this->assertEquals(-1, $fileInfo->isExecutable());
        $this->assertEquals(-1, $fileInfo->isFile());
        $this->assertEquals(-1, $fileInfo->isDir());
        $this->assertEquals(-1, $fileInfo->isLink());
        $this->assertEquals(-1, $fileInfo->isLink());
    }
}
