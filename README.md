# Json

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Json/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Json/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Json/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Json/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Json/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Json/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Json/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Json/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Innmind/Json/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Json/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Innmind/Json/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Json/build-status/develop) |

Type safe json encode/decoder, the goal is to not leave errors unchecked.

## Installation

```sh
composer require innmind/json
```

## Usage

```php
use Innmind\Json\Json;

Json::encode(['foo' => 'bar']); // {"foo":"bar"}
Json::decode('{"foo":"bar"}'); // ['foo' => 'bar']
Json::decode('{]'); // will throw an exception (instead of returning false)
```
