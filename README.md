# Munus

Power of object-oriented programming with the elegance of functional programming.
Increase the robustness with reduced amount of code.

At the moment, in the experimental phase.

Due to the lack of generic types, Munus achieves genericity with the help of [Psalm](https://github.com/vimeo/psalm) `template` annotation.

Option example:
```php
/** @var Option<Success> $option */
$option = Option::of(domainOperation());
```

Either example:
```php
/**
 * @return Either<Failure,Success>
 */
function domainOperation(): Either {}
```

Try example:
```php
/** @var Trƴ<Result> $result */
$result = Trƴ::of(function(){throw new \DomainException('use ddd');});

$result->getOrElse(new Result())
```

**Psalm was able to infer types for 100% of the codebase**

### Features

 - Try
 - Either
 - Option

### Roadmap

 - More sugar in Value
    - helper methods
    - collection support
 - Future
 - Lazy
 - List
 - Stream
 - Pattern matching
 - Try with recover
 - Tuples

## Inspiration

This library is inspired by [vavr.io](https://www.vavr.io/)

## License

Munus is released under the MIT Licence. See the bundled LICENSE file for details.

## Author

[@ArkadiuszKondas](https://twitter.com/ArkadiuszKondas)
