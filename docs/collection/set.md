# Set

Data structure which only holds unique values. Set is implemented as immutable array.

## Construction

```php
Set::empty();
Set::of('first', 'second');
Set::ofAll(['alpha', 'beta']);
```

## Operations

You can `add` new element, `remove` existing one and check if given `$element` exist with `contains`.

```php
$set = Set::of('alpha');

$set2 = $set->add('beta');
$set3 = $set2->remove('beta');

$set->contains('alhpa'); // true
```

You can also join two sets with `union`. The merged set will contain only unique values.

```php
$set = Set::of('alpha');
$set2 = Set::of('alpha', 'beta');

$set->union($set2); // 'alpha', 'beta'
```
