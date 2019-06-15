# phuxtil-splfileinfo

`VirtualSplFileInfo` allows to use non existing (virtual) paths and still be able to perform 
all file operations like `getSize()`, `isFile()`, `getOnwer()`, and get predefined results.
 
It has setters support, and helper methods like `isVirtual()`, `toArray()`,  `fromArray()`, `fromSplFileInfo()`.

Possible use cases:

 - Legacy code using `\SplFileInfo` classes with hardcoded paths that only exist on production.
 - Testing / mocking / TDD
 


### Installation

```bash
composer require phuxtil/splfileinfo 
```

### Usage
You can turn virtual file into real file at any time, for example.

```php
$path = '/tmp/not-yet/existing-path';
$virtualInfo = new VirtualSplFileInfo($path);

// only PathInfo data is set at this point, the resource does not exist
$virtualInfo->getPathname();  # /tmp/not-yet/existing-path
$virtualInfo->getPath();      # /tmp/not-yet
...

// the rest of the properties is set to -1 by default
$virtualInfo->isDir();        # -1
$virtualInfo->getSize();      # -1
$virtualInfo->isExecutable(); # -1
...

// virtual resource has been created 
@mkdir($path);

// refresh virutal file info
$virtualInfo->fromSplFileInfo(new SplFileInfo($path));
```

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
  linkTarget: -1
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

```
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
  linkTarget: -1
}
```

#### toArray(): array 

``` php
$info = new VirtualSplFileInfo('/tmp/not-yet/existing-path');
$data = $info->toArray();
```

```php
[
  "path" => "/tmp/not-yet"
  "filename" => "existing-path"
  "basename" => "existing-path"
  "pathname" => "/tmp/not-yet/existing-path"
  "extension" => ""
  "realPath" => "/tmp/not-yet/existing-path"
  "aTime" => -1
  "mTime" => -1
  "cTime" => -1
  "inode" => -1
  "size" => -1
  "perms" => -1
  "owner" => -1
  "group" => -1
  "type" => "virtual"
  "linkTarget" => -1
  "writable" => -1
  "readable" => -1
  "executable" => -1
  "file" => -1
  "dir" => -1
  "link" => -1
]
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

```php
[
  "path" => "/tmp/not-yet"
  "filename" => "existing-path"
  "basename" => "existing-path"
  "pathname" => "/tmp/not-yet/existing-path"
  "extension" => ""
  "realPath" => "/tmp/not-yet/existing-path"
  "aTime" => 123
  "mTime" => 456
  "cTime" => 789
  "inode" => 222
  "size" => 333
  "perms" => 0755
  "owner" => 1
  "group" => 1
  "type" => "dir"
  "linkTarget" => -1
  "writable" => dir
  "readable" => dir
  "executable" => dir
  "file" => false
  "dir" => true
  "link" => false
]
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

```php
$info = new VirtualSplFileInfo('/tmp/not-yet/existing-path');
```

```
path: "/tmp/not-yet"
filename: "existing-path"
basename: "existing-path"
pathname: "/tmp/not-yet/existing-path"
extension: ""
realPath: "/tmp/not-yet/existing-path"
aTime: -1
mTime: -1
cTime: -1
inode: -1
size: -1
perms: -1
owner: -1
group: -1
type: "virtual"
writable: -1
readable: -1
executable: -1
file: -1
dir: -1
link: -1
linkTarget: -1
```


#### TDD

See [tests](https://github.com/oliwierptak/phuxtil-splfileinfo/blob/master/tests/Functional/SplFileInfo/SplFileInfoSimpleTest.php) for more examples.
