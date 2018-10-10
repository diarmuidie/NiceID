Changelog
---------

### Version 1.2.0 (10 Oct 2018)

- Drop PHP < 5.6 support.
- Add support for PHP 7.2.
- Remove codeclimate test.
- Add PHPCS.

### Version 1.1.0 (18 Sept 2017)

- Drop PHP5.3 support.
- Specify mt_srand algorithm to maintain backwards compatibility from PHP 7.1 to earlier versions.

### Version 1.0.0 (22 Sept 2015)

- Initial stable release.
- Refactor BaseConvert class.

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
