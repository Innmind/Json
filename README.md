# Json

[![Build Status](https://github.com/Innmind/JSON/workflows/CI/badge.svg?branch=master)](https://github.com/Innmind/JSON/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/Innmind/JSON/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/JSON)
[![Type Coverage](https://shepherd.dev/github/Innmind/JSON/coverage.svg)](https://shepherd.dev/github/Innmind/JSON)

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
