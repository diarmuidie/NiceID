diarmuidie/niceid
=============

> Converts an ID into a non-sequential, URL friendly and short NiceID. Similiar to YouTube (https://www.youtube.com/watch?v=dQw4w9WgXcQ) and Bitly (http://bit.ly/1D0CAzd) URLs.

Developed by [Diarmuid](https://diarmuid.ie/).

[![Build Status](https://travis-ci.org/diarmuidie/niceid.svg)](https://travis-ci.org/diarmuidie/niceid)
[![Test Coverage](https://codeclimate.com/github/diarmuidie/niceid/badges/coverage.svg)](https://codeclimate.com/github/diarmuidie/niceid)
[![Code Climate](https://codeclimate.com/github/diarmuidie/niceid/badges/gpa.svg)](https://codeclimate.com/github/diarmuidie/niceid)

Features
--------

- Non-sequential IDs (i.e. difficult to guess the next one).
- URL friendly.
- No external dependencies.
- PSR-4 compatible.
- Compatible with PHP >= 5.3.3.

Installation
------------

You can install NiceID through [Composer](https://getcomposer.org):

```shell
$ composer require diarmuidie/niceid
```


Usage
-----

Generate an ID from a int:

```php
require 'vendor/autoload.php';

use Diarmuidie\NiceID\NiceID;

$niceid = new NiceID('Some Random Secret Value');
echo $niceid->encode(123); // uSdqd
```
Notice that a secret is passed to the NiceID constructor. This secret is used to encode and decode the IDs. If the secret changes then IDs cannot be decoded.

Use the decode method to decode the NiceID back to an int.
```php
echo $niceid->decode('uSdqd'); // 123
```

You can also change the min length of the NiceID (Defaults to 5 if not specified):

```php
$niceid->setMinLength(10);
echo $niceid->encode(123); // uccccccu8p
```

To specify the characters to use in the encoded string use the  `setCharacters()` method.

```php
$niceid->setCharacters('abcde');
echo $niceid->encode(123); // adcce
```

The default character set is: `0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_`

You can even specify a UTF-8 character set if you want:

```php
$niceid->setCharacters('ⓐⓑⓒⓓⓔⓕⓖⓗⓘⓙⓚⓛⓜⓝⓞⓟⓠⓡⓢⓣⓤⓥⓦⓧⓨⓩ');
echo $niceid->encode(123); // ⓖⓑⓥⓘⓘⓠ
```

To Do
---------
- [x] Add library to [packagist](http://packagist.org).
- [x] Setup Travis to run unit tests.
- [x] Better handle UTF-8 chars in character string.
- [x] 100% Code coverage for unit tests.
- [x] Refactor `BaseConvert::convert()` method.
- [x] Handle `PHP_INT_MAX` overflow.

Contributing
---------

Feel free to contribute features, bug fixes or just helpful advice :smile:

1. Fork this repo
2. Create a feature branch
3. Submit a PR
...
4. Profit :sunglasses:


Changelog
---------

### Version 0.2.2 (16 May 2015)

- Bump composer dependencies.
- Add `.gitattributes` file to exclude build assets from composer install.

### Version 0.2.1 (6 April 2015)

- NiceID class now implements the NiceIdInterface.
- Throw `InvalidArgumentException` if the `encode`/`decode` method params are not of the correct type.

### Version 0.2.0 (28 March 2015)

_Warning: NiceIds generated with previous versions will not decode correctly in this version._

- Only pad string if `minLength` greater than 2.
- Add _ (underscore) to characters string.
- Throw `LengthException` for PHP_MAX_INT overflow.
- Shuffle NiceID in encode function to eliminate consecutive similiar chars.

### Version 0.1.0 (23 March 2015)

- Initial release.

Authors
-------

- [Diarmuid](http://diarmuid.ie) ([Twitter](http://twitter.com/diarmuidie))


License
-------

The MIT License (MIT)

Copyright (c) 2015 Diarmuid

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
