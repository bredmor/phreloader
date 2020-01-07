# Phreloader
[![Packagist Version](https://img.shields.io/packagist/v/bredmor/phreloader)](https://packagist.org/packages/bredmor/phreloader) ![PHP from Packagist](https://img.shields.io/packagist/php-v/bredmor/phreloader) [![License from GitHub](https://img.shields.io/github/license/bredmor/phreloader)](LICENSE)

 Phreloader is a simple *auto-preloader* for the [PHP preloader](https://wiki.php.net/rfc/preload) available from version 7.4 and later.
 
 This preloader will automatically preload every PHP file defined by your composer classmap, as long as it can be found within the parent directory.
 
## Work In Progress
 This package is a heavy work in progress. As of the time of this writing, PHP 7.4 has not yet been released, nor has this package been extensively tested. ***USE AT YOUR OWN RISK***
 
 This package is a stopgap solution until [Composer supports preloading](https://github.com/composer/composer/issues/7777).
 
### Planned Features (Not Yet Implemented)
 - Support more custom vendor directory configurations
 - Publish configured preloading script to project root
 - Tests
 
## Installation
 
### From Source:
 Clone the repository from GitHub or unzip into your vendor directory. Phreloader is packaged for [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloading.
 
### From Composer:
 `composer require bredmor/phreloader`
 
### Basic Usage
 Create a file `preloader.php` in your project root directory with the following contents:
 
 ```$php
require_once(__DIR__ . '/vendor/autoload.php');

use bredmor/phreloader/Preloader;
(new Preloader(__DIR__))->go(); // Where `__DIR__` refers to the root directory of your project's code.

```

#### Optional - Specify vendor directory location
```$php
require_once(__DIR__ . '/vendor/autoload.php');

use bredmor/phreloader/Preloader;
(new Preloader(__DIR__, '/my/custom/vendor/dir'))->go(); 
```

Add the following line to your `php.ini` configuration file(s):

```$bash
opcache.preload=/path/to/your/project/preload.php
```

That's  it! The next time you restart your server, PHP will preload your project files in memory.

### Ignoring specific files or directories
In the same directory as your project root, create a new file `.nopreload`. Each line in this file should be the (relative or absolute) path to files or directories not to be preloaded. 

Example:
```
tests/
some_rarely_used_dir/
badfile.php
another/directory/file.php
``` 


## Caveats
- Preloading requires more available RAM the larger your project is. Preloading your entire classmap may not be desirable for larger projects, especially on smaller servers/containers. It is strongly recommended to use the ignore list to ignore rarely-loaded files, or directories that contain them..

- Because preloading stores the opcodes for your files in memory, you *must restart your server every time you change one of these files* - the new code will not be picked up until you do, your server will continue to use the version cached in RAM.

## Requirements

Phreloader is built for PHP `7.4` and later.

## Authors

- Morgan Breden  | [GitHub](https://github.com/bredmor)  | [Twitter](https://twitter.com/bredmor)

## Contributing

Pull requests, bug reports and feature requests are welcome.

## License

Phreloader is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
