# Contributing to Munus

Munus is an open source project. If you'd like to contribute, please read the following text. Before we can merge your 
pull request, here are some guidelines that you need to follow. These guidelines exist not to annoy you, but to keep the 
code base clean, unified and future proof.

## Branch

You should only open pull requests against the `master` branch.

## Unit Tests

Please try to add a test for your pull request. You can run the unit-tests by calling:

```bash
vendor/bin/phpunit
```

## Build

GitHub automatically run your pull request through GitHub Action.
If you break the tests, we cannot merge your code, so please make sure that your code is working before opening up a pull request.

## Merge

Please give us time to review your pull requests. We will give our best to review everything as fast as possible.

## Coding Standards & Static Analysis

When contributing code to Munus, you must follow its coding standards. To do that, just run:

```bash
composer fix-cs
```
[More about Php-Cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

Code has to also pass static analysis by [PHPStan](https://github.com/phpstan/phpstan):

```bash
composer phpstan
```

and by [Psalm](https://github.com/vimeo/psalm):

```bash
composer psalm
```

## Documentation

Please update the documentation pages if necessary.
You can find them in this repository [munusphp/website](https://github.com/munusphp/website)

---

Thank you very much for contribution!
