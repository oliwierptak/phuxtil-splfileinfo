<?php

namespace Phuxtil\SplFileInfo;

class VirtualSplFileInfo extends \SplFileInfo
{
    /**
     * @var int
     */
    protected $aTime = -1;

    /**
     * @var int
     */
    protected $mTime = -1;

    /**
     * @var int
     */
    protected $cTime = -1;

    /**
     * @var int
     */
    protected $inode = -1;

    /**
     * @var int
     */
    protected $size = -1;

    /**
     * @var mixed
     */
    protected $perms = -1;

    /**
     * @var int
     */
    protected $owner = -1;

    /**
     * @var int
     */
    protected $group = -1;

    /**
     * @var string
     */
    protected $type = 'virtual';

    /**
     * @var bool
     */
    protected $writable = -1;

    /**
     * @var bool
     */
    protected $readable = -1;

    /**
     * @var bool
     */
    protected $executable = -1;

    /**
     * @var bool
     */
    protected $file = -1;

    /**
     * @var bool
     */
    protected $dir = -1;

    /**
     * @var bool
     */
    protected $link = -1;

    /**
     * @var string
     */
    protected $realPath = -1;

    /**
     * @var string
     */
    protected $linkTarget = -1;

    public function getRealPath(): string
    {
        return $this->realPath;
    }

    public function setRealPath(string $realPath): self
    {
        $this->realPath = $realPath;

        return $this;
    }

    public function getLinkTarget()
    {
        return $this->linkTarget;
    }

    public function setLinkTarget(string $linkTarget)
    {
        $this->linkTarget = $linkTarget;
    }

    public function getATime()
    {
        return $this->aTime;
    }

    public function setATime(int $aTime): self
    {
        $this->aTime = $aTime;

        return $this;
    }

    public function getMTime()
    {
        return $this->mTime;
    }

    public function setMTime(int $mTime): self
    {
        $this->mTime = $mTime;

        return $this;
    }

    public function getCTime()
    {
        return $this->cTime;
    }

    public function setCTime(int $cTime): self
    {
        $this->cTime = $cTime;

        return $this;
    }

    public function getInode()
    {
        return $this->inode;
    }

    public function setInode(int $inode): self
    {
        $this->inode = $inode;

        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPerms()
    {
        return $this->perms;
    }

    public function setPerms($perms): self
    {
        $this->perms = $perms;

        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner(int $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup(int $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isWritable()
    {
        return $this->writable;
    }

    public function setWritable(bool $writable): self
    {
        $this->writable = $writable;

        return $this;
    }

    public function isReadable()
    {
        return $this->readable;
    }

    public function setReadable(bool $readable): self
    {
        $this->readable = $readable;

        return $this;
    }

    public function isExecutable()
    {
        return $this->executable;
    }

    public function setExecutable(bool $executable): self
    {
        $this->executable = $executable;

        return $this;
    }

    public function isFile()
    {
        return $this->file;
    }

    public function setFile(bool $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function isDir()
    {
        return $this->dir;
    }

    public function setDir(bool $dir): self
    {
        $this->dir = $dir;

        return $this;
    }

    public function isLink()
    {
        return $this->link;
    }

    public function setLink(bool $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getFileInfo($class_name = null)
    {
        if ($class_name === null) {
            $class_name = self::class;
        }

        return parent::getFileInfo($class_name);
    }

    public function getPathInfo($class_name = null)
    {
        if ($class_name === null) {
            $class_name = self::class;
        }

        return parent::getPathInfo($class_name);
    }

    public function isVirtual(): bool
    {
        return $this->type === 'virtual';
    }

    public function fromSplFileInfo(\SplFileInfo $info)
    {
        $data = $this->infoToArray($info);
        $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        $this->aTime = $data['aTime'] ?? -1;
        $this->mTime = $data['mTime'] ?? -1;
        $this->cTime = $data['cTime'] ?? -1;
        $this->inode = $data['inode'] ?? -1;
        $this->size = $data['size'] ?? -1;
        $this->perms = $data['perms'] ?? -1;
        $this->owner = $data['owner'] ?? -1;
        $this->group = $data['group'] ?? -1;
        $this->type = $data['type'] ?? 'virtual';
        $this->writable = $data['writable'] ?? -1;
        $this->readable = $data['readable'] ?? -1;
        $this->executable = $data['executable'] ?? -1;
        $this->file = $data['file'] ?? -1;
        $this->dir = $data['dir'] ?? -1;
        $this->link = $data['link'] ?? -1;
        $this->realPath = $this->isLink() ? $data['realPath'] : $this->getPathname();
        $this->linkTarget = $this->isLink() ? $data['linkTarget'] : -1;

        return $this;
    }

    public function toArray(): array
    {
        return $this->infoToArray($this);
    }

    protected function infoToArray(\SplFileInfo $info): array
    {
        return [
            'path' => $info->getPath(),
            'filename' => $info->getFilename(),
            'basename' => $info->getBasename(),
            'pathname' => $info->getPathname(),
            'extension' => $info->getExtension(),
            'realPath' => $info->isLink() ? $info->getRealPath() : $info->getPathname(),
            'aTime' => $info->getATime(),
            'mTime' => $info->getMTime(),
            'cTime' => $info->getCTime(),
            'inode' => $info->getInode(),
            'size' => $info->getSize(),
            'perms' => $info->getPerms(),
            'owner' => $info->getOwner(),
            'group' => $info->getGroup(),
            'type' => $info->getType(),
            'linkTarget' => $info->isLink() ? $info->getLinkTarget() : -1,
            'writable' => $info->isWritable(),
            'readable' => $info->isReadable(),
            'executable' => $info->isExecutable(),
            'file' => $info->isFile(),
            'dir' => $info->isDir(),
            'link' => $info->isLink(),
        ];
    }
}
