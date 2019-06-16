<?php

namespace PhuxtilTests\Functional\SplFileInfo;

use SplFileInfo;
use Phuxtil\SplFileInfo\VirtualSplFileInfo;
use PHPUnit\Framework\TestCase;

class SplFileInfoSimpleTest extends TestCase
{
    const TEST_FILE_REAL = \TESTS_FIXTURE_DIR . 'test.txt';
    const TEST_FILE_VIRTUAL = \TESTS_FIXTURE_DIR . 'non_existing.txt';
    const TEST_FILE_LINK = \TESTS_FIXTURE_DIR . 'test_link.txt';

    protected function setUp()
    {
        @unlink(static::TEST_FILE_LINK);
        @unlink(static::TEST_FILE_VIRTUAL);
    }

    public function test_fromSplFileInfo()
    {
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);
        $this->assertTrue($virtualFileInfo->isVirtual());

        $info = new SplFileInfo(static::TEST_FILE_REAL);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_REAL);
        $this->assertTrue($virtualFileInfo->isVirtual());

        $virtualFileInfo->fromSplFileInfo($info);

        $virtualFileInfo->setType($info->getType());
        $this->assertFalse($virtualFileInfo->isVirtual());
    }

    public function test_path_and_properties()
    {
        $fileInfo = new SplFileInfo(static::TEST_FILE_VIRTUAL);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);

        $this->comparePaths($fileInfo, $virtualFileInfo);
        $this->assertVirtualProperties($virtualFileInfo);
    }

    public function test_link_target()
    {
        \symlink(static::TEST_FILE_REAL, static::TEST_FILE_LINK);

        $fileInfo = new SplFileInfo(static::TEST_FILE_LINK);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_LINK);
        $virtualFileInfo
            ->setType($fileInfo->getType())
            ->setLinkTarget($fileInfo->getLinkTarget());

        $this->comparePaths($fileInfo, $virtualFileInfo);
        $this->assertVirtualProperties($virtualFileInfo);
    }

    public function test_setters_and_getters()
    {
        $virtualFileInfo = new VirtualSplFileInfo('/tmp/invalid.foo');
        $fileInfo = new SplFileInfo('/tmp/invalid.foo');

        $this->comparePaths($fileInfo, $virtualFileInfo);

        $virtualFileInfo->setRealPath('/tmp/invalid.foo');
        $this->assertEquals('/tmp/invalid.foo', $virtualFileInfo->getRealPath());

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
        $virtualFileInfo = new VirtualSplFileInfo('/tmp/non/existing-path');

        $data = $virtualFileInfo->toArray();

        $this->assertEquals($virtualFileInfo->getPath(), $data['path']);
        $this->assertEquals($virtualFileInfo->getFilename(), $data['filename']);
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
        $this->assertEquals($virtualFileInfo->getLinkTarget(), $data['linkTarget']);
        $this->assertEquals($virtualFileInfo->isWritable(), $data['writable']);
        $this->assertEquals($virtualFileInfo->isReadable(), $data['readable']);
        $this->assertEquals($virtualFileInfo->isExecutable(), $data['executable']);
        $this->assertEquals($virtualFileInfo->isFile(), $data['file']);
        $this->assertEquals($virtualFileInfo->isDir(), $data['dir']);
        $this->assertEquals($virtualFileInfo->isLink(), $data['link']);
    }

    public function test_fromArray()
    {
        $virtualFileInfo = new VirtualSplFileInfo('/tmp/non/existing-path');

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
                'dir' => true,
                'link' => false
            ]
        );

        $data = $virtualFileInfo->toArray();

        $this->assertEquals($data['path'], $virtualFileInfo->getPath());
        $this->assertEquals($data['filename'], $virtualFileInfo->getFilename());
        $this->assertEquals($data['basename'], $virtualFileInfo->getBasename());
        $this->assertEquals($data['pathname'], $virtualFileInfo->getPathname());
        $this->assertEquals($data['extension'], $virtualFileInfo->getExtension());
        $this->assertEquals($data['realPath'], $virtualFileInfo->getRealPath());
        $this->assertEquals($data['aTime'], $virtualFileInfo->getATime());
        $this->assertEquals($data['mTime'], $virtualFileInfo->getMTime());
        $this->assertEquals($data['cTime'], $virtualFileInfo->getCTime());
        $this->assertEquals($data['inode'], $virtualFileInfo->getInode());
        $this->assertEquals($data['size'], $virtualFileInfo->getSize());
        $this->assertEquals($data['perms'], $virtualFileInfo->getPerms());
        $this->assertEquals($data['owner'], $virtualFileInfo->getOwner());
        $this->assertEquals($data['group'], $virtualFileInfo->getGroup());
        $this->assertEquals($data['type'], $virtualFileInfo->getType());
        $this->assertEquals(-1, $virtualFileInfo->getLinkTarget());
        $this->assertEquals($data['writable'], $virtualFileInfo->isWritable());
        $this->assertEquals($data['readable'], $virtualFileInfo->isReadable());
        $this->assertEquals($data['executable'], $virtualFileInfo->isExecutable());
        $this->assertEquals($data['file'], $virtualFileInfo->isFile());
        $this->assertEquals($data['dir'], $virtualFileInfo->isDir());
        $this->assertEquals($data['link'], $virtualFileInfo->isLink());
        $this->assertFalse($virtualFileInfo->isVirtual());
    }

    public function test_fromArray_linkTarget()
    {
        $virtualFileInfo = new VirtualSplFileInfo('/tmp/non/existing-path-link');

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
                'type' => 'link',
                'writable' => true,
                'readable' => true,
                'executable' => true,
                'file' => false,
                'dir' => false,
                'link' => true,
                'linkTarget' => '/tmp/non/existing-path-link',
                'realPath' => '/tmp/non/existing-path',
            ]
        );

        $data = $virtualFileInfo->toArray();

        $this->assertEquals($data['path'], $virtualFileInfo->getPath());
        $this->assertEquals($data['filename'], $virtualFileInfo->getFilename());
        $this->assertEquals($data['basename'], $virtualFileInfo->getBasename());
        $this->assertEquals($data['pathname'], $virtualFileInfo->getPathname());
        $this->assertEquals($data['extension'], $virtualFileInfo->getExtension());
        $this->assertEquals($data['realPath'], $virtualFileInfo->getRealPath());
        $this->assertEquals($data['aTime'], $virtualFileInfo->getATime());
        $this->assertEquals($data['mTime'], $virtualFileInfo->getMTime());
        $this->assertEquals($data['cTime'], $virtualFileInfo->getCTime());
        $this->assertEquals($data['inode'], $virtualFileInfo->getInode());
        $this->assertEquals($data['size'], $virtualFileInfo->getSize());
        $this->assertEquals($data['perms'], $virtualFileInfo->getPerms());
        $this->assertEquals($data['owner'], $virtualFileInfo->getOwner());
        $this->assertEquals($data['group'], $virtualFileInfo->getGroup());
        $this->assertEquals($data['type'], $virtualFileInfo->getType());
        $this->assertEquals('/tmp/non/existing-path-link', $virtualFileInfo->getLinkTarget());
        $this->assertEquals($data['writable'], $virtualFileInfo->isWritable());
        $this->assertEquals($data['readable'], $virtualFileInfo->isReadable());
        $this->assertEquals($data['executable'], $virtualFileInfo->isExecutable());
        $this->assertEquals($data['file'], $virtualFileInfo->isFile());
        $this->assertEquals($data['dir'], $virtualFileInfo->isDir());
        $this->assertEquals($data['link'], $virtualFileInfo->isLink());
        $this->assertFalse($virtualFileInfo->isVirtual());
    }

    public function test_getFileInfo()
    {
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);
        $this->assertEquals('virtual', $virtualFileInfo->getType());
        $this->assertTrue($virtualFileInfo->isVirtual());

        \file_put_contents(static::TEST_FILE_VIRTUAL, 'Lorem ipsum');
        $fileInfo = new SplFileInfo(static::TEST_FILE_VIRTUAL);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);

        $this->comparePaths($fileInfo, $virtualFileInfo);
        $this->comparePaths($fileInfo, $virtualFileInfo->getFileInfo());
        $this->assertVirtualProperties($virtualFileInfo->getFileInfo(VirtualSplFileInfo::class));
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

    public function test_isVirtual()
    {
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_VIRTUAL);
        $this->assertTrue($virtualFileInfo->isVirtual());

        $info = new SplFileInfo(static::TEST_FILE_REAL);
        $virtualFileInfo = new VirtualSplFileInfo(static::TEST_FILE_REAL);
        $this->assertTrue($virtualFileInfo->isVirtual());

        $virtualFileInfo->setType($info->getType());
        $this->assertFalse($virtualFileInfo->isVirtual());
    }

    protected function comparePaths(SplFileInfo $expected, SplFileInfo $actual)
    {
        $this->assertEquals($expected->getPath(), $actual->getPath());
        $this->assertEquals($expected->getFilename(), $actual->getFilename());
        $this->assertEquals($expected->getExtension(), $actual->getExtension());
        $this->assertEquals($expected->getBasename(), $actual->getBasename());
        $this->assertEquals($expected->getBasename('.txt'), $actual->getBasename('.txt'));
        $this->assertEquals($expected->getPathname(), $actual->getPathname());

        if ((int)$actual->getLinkTarget() !== -1) {
            $this->assertEquals($expected->getLinkTarget(), $actual->getLinkTarget());
        }
        else {
            $this->assertEquals(-1, $actual->getLinkTarget());
        }
    }

    protected function assertVirtualProperties(SplFileInfo $fileInfo)
    {
        $this->assertEquals(-1, $fileInfo->getPerms());
        $this->assertEquals(-1, $fileInfo->getInode());
        $this->assertEquals(-1, $fileInfo->getSize());
        $this->assertEquals(-1, $fileInfo->getOwner());
        $this->assertEquals(-1, $fileInfo->getGroup());
        $this->assertEquals(-1, $fileInfo->getATime());
        $this->assertEquals(-1, $fileInfo->getMTime());
        $this->assertEquals(-1, $fileInfo->getCTime());

        $this->assertEquals(-1, $fileInfo->isWritable());
        $this->assertEquals(-1, $fileInfo->isReadable());
        $this->assertEquals(-1, $fileInfo->isExecutable());
        $this->assertEquals(-1, $fileInfo->isFile());
        $this->assertEquals(-1, $fileInfo->isDir());
        $this->assertEquals(-1, $fileInfo->isLink());
        $this->assertEquals(-1, $fileInfo->getRealPath());

        $type = 'virtual';
        $linkTarget = -1;

        if ($fileInfo->getType() !== 'virtual') {
            $type = $fileInfo->getType();
            $linkTarget = $fileInfo->getLinkTarget();
        }

        $this->assertEquals($type, $fileInfo->getType());
        $this->assertEquals($linkTarget, $fileInfo->getLinkTarget());
    }
}
