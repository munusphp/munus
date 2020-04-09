# Munus

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![buddy pipeline](https://app.buddy.works/akondas/munus/pipelines/pipeline/220416/badge.svg?token=bfd952ec0cee0cb4db84dbd50ded487354ee6c9f37a7034f7c46425fed70dea7 "buddy pipeline")](https://app.buddy.works/akondas/munus/pipelines/pipeline/220416)
[![github action](https://github.com/munusphp/munus/workflows/build/badge.svg)](https://github.com/munusphp/munus/actions?query=workflow%3Abuild)
[![Latest Stable Version](https://poser.pugx.org/munusphp/munus/v/stable?format=flat)](https://packagist.org/packages/munusphp/munus)
[![Maintainability](https://api.codeclimate.com/v1/badges/4b9585a0fb57553737d5/maintainability)](https://codeclimate.com/github/munusphp/munus/maintainability)
[![codecov](https://codecov.io/gh/munusphp/munus/branch/master/graph/badge.svg)](https://codecov.io/gh/munusphp/munus)
[![Total Downloads](https://poser.pugx.org/munusphp/munus/downloads?format=flat)](https://packagist.org/packages/munusphp/munus)
![GitHub](https://img.shields.io/github/license/munusphp/munus)

Power of object-oriented programming with the elegance of functional programming.
Increase the robustness with reduced amount of code.

At the moment, in the experimental phase.

**[Documentation](https://munusphp.github.io/docs/start)**

Due to the lack of generic types, Munus achieves genericity with the help of [Psalm](https://github.com/vimeo/psalm) `template` annotation.

Stream example: find the sum of the first ten squares of even numbers
```php
Stream::from(1)
    ->filter(fn($n) => $n%2===0)
    ->map(fn($n) => $n**2)
    ->take(10)
    ->sum();
```

Other examples:
```php
/** @var Stream<int> $stream */
$stream = Stream::range(1, 10)->map(function(int $int): int {return $int * 5});

/** @var Option<Success> $option */
$option = Option::of(domainOperation());

/** @return Either<Failure,Success> */
function domainOperation(): Either {}

/** @var TryTo<Result> $result */
$result = TryTo::run(function(){throw new \DomainException('use ddd');});
$result->getOrElse(new Result())
```

The goal is to help achieve:
**Psalm was able to infer types for 100% of the codebase**

### Features

**Values:**
 - TryTo
 - Either
 - Option
 - Lazy

**Collections:**
 - Set
 - Stream (implemented as lazy linked list)
 - GenericList (implemented as immutable linked list)
 - Iterator

**Other:**
 - Tuple

### Roadmap

 - Pattern matching
 - Property checking


## Inspiration

This library is inspired by [vavr.io](https://www.vavr.io/)

## License

Munus is released under the MIT Licence. See the bundled LICENSE file for details.

## Author

[@ArkadiuszKondas](https://twitter.com/ArkadiuszKondas)
