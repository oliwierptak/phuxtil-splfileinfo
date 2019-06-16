# phuxtil-splfileinfo

`VirtualSplFileInfo` allows to use non existing (virtual) paths and still be able to perform 
all file operations like `getSize()`, `isFile()`, `getOnwer()`, etc, and get predefined results.
 
It has setters support, and helper methods like `isVirtual()`, `toArray()`,  `fromArray()`, `fromSplFileInfo()`.
 

### Installation

```bash
composer require phuxtil/splfileinfo 
```

### Usage

#### Create virtual file info.

```php
$path = '/tmp/not-yet/existing-path';
$virtualInfo = new VirtualSplFileInfo($path);
```


Only PathInfo data is set at this point.

```php
$virtualInfo->getPathname();  # /tmp/not-yet/existing-path
$virtualInfo->getPath();      # /tmp/not-yet
...
```
The rest of the data can be updated with setters.
```php
$virtualInfo->setSize(120);
$virtualInfo->setATime(time());
$virtualInfo->setPerms(0775);
...
```

_Note: All properties besides PathInfo are set to -1 by default._


#### Check if resource is virtual.

```php
$virtualInfo->getType();      # virtual
$virtualInfo->isVirtual();    # true
```


####  Update virtual file info with real resource data

```php 
@mkdir($path);

$virtualInfo->fromSplFileInfo(new SplFileInfo($path));

$virtualInfo->isVirtual(); # false
```

`VirtualFileInfo` vs `\SplFileInfo`.

```
$splInfo = SplFileInfo {
  path: "/tmp/not-yet"
  filename: "existing-path"
  basename: "existing-path"
  pathname: "/tmp/not-yet/existing-path"
  extension: ""
  realPath: "/tmp/not-yet/existing-path"
  aTime: 2019-06-15 22:07:47
  mTime: 2019-06-15 22:07:47
  cTime: 2019-06-15 22:07:47
  inode: 10248205
  size: 64
  perms: 040755
  owner: 0
  group: 0
  type: "dir"
  writable: true
  readable: true
  executable: true
  file: false
  dir: true
  link: false
}

$virtualInfo = Phuxtil\SplFileInfo\VirtualSplFileInfo {
  path: "/tmp/not-yet"
  filename: "existing-path"
  basename: "existing-path"
  pathname: "/tmp/not-yet/existing-path"
  extension: ""
  realPath: "/tmp/not-yet/existing-path"
  aTime: 2019-06-15 22:07:47
  mTime: 2019-06-15 22:07:47
  cTime: 2019-06-15 22:07:47
  inode: 10248205
  size: 64
  perms: 040755
  owner: 0
  group: 0
  type: "dir"
  writable: true
  readable: true
  executable: true
  file: false
  dir: true
  link: false
}
```

### Extra methods

#### isVirtual(): bool

Returns true if the and does not really exist. 

_Note: isReadable(), isFile(),... etc, can return true, even if the resource does not exist._


#### fromSplFileInfo(\SplFileInfo $info) 

``` php
$path = '/tmp/not-yet/existing-path';
$virtualInfo = new VirtualSplFileInfo($path);

@mkdir($path, 0777, true);

$splInfo = new SplFileInfo($path);
$virtualInfo->fromSplFileInfo($splInfo);
```

#### toArray(): array 

``` php
$info = new VirtualSplFileInfo('/tmp/not-yet/existing-path');
$data = $info->toArray();
```

#### fromArray(array $data)

``` php
$info = new VirtualSplFileInfo('/tmp/not-yet/existing-path');
$info->fromArray(
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
        'link' => false,
    ]
);
```


#### Setter support
You can use setters for all properties besides `PathInfo`, which is resolved form the filename by default in `\SplFileInfo`.
The file does not have to exist for those methods to work.

Properties with setters:

```
realPath
aTime
mTime
cTime
inode
size 
perms
owner
group
type 
writable
readable
executable
file
dir 
link
linkTarget
```



#### Default values
All values besides `PathInfo` are set to `-1` by default.


#### TDD

See [tests](https://github.com/oliwierptak/phuxtil-splfileinfo/blob/master/tests/Functional/SplFileInfo/SplFileInfoSimpleTest.php) for more examples.
