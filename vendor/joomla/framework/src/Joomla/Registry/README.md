# The Registry Package

``` php
use Joomla\Registry\Registry;

$registry = new Registry;

// Set a value in the registry.
$registry->set('foo') = 'bar';

// Get a value from the registry;
$value = $registry->get('foo');

```

## Load config by Registry

``` php
use Joomla\Registry\Registry;

$registry = new Registry;

// Load by string
$registry->loadString('{"foo" : "bar"}');

$registry->loadString('<root></root>', 'xml');

// Load by object or array
$registry->loadObject($object);
$registry->loadaArray($array);

// Load by file
$registry->loadFile($root . '/config/config.json', 'json');
```

## Accessing a Registry by getter & setter

### Get value

``` php
$registry->get('foo');

// Get a non-exists value and return default
$registry->get('foo', 'default');

// OR

$registry->get('foo') ?: 'default';
```

### Set value

``` php
// Set value
$registry->set('bar', $value);

// Sets a default value if not already assigned.
$registry->def('bar', $default);
```

### Accessing children value by path

``` php
$json = '{
	"parent" : {
		"child" : "Foo"
	}
}';

$registry = new Registry($json);

$registry->get('parent.child'); // return 'Foo'

$registry->set('parent.child', $value);
```

## Accessing a Registry as an Array

The `Registry` class implements `ArrayAccess` so the properties of the registry can be accessed as an array. Consider the following examples:

``` php
// Set a value in the registry.
$registry['foo'] = 'bar';

// Get a value from the registry;
$value = $registry['foo'];

// Check if a key in the registry is set.
if (isset($registry['foo']))
{
	echo 'Say bar.';
}
```

## Merge Registry

#### Using load* methods to merge two config files.

``` php
$json1 = '{
    "field" : {
        "keyA" : "valueA",
        "keyB" : "valueB"
    }
}';

$json2 = '{
    "field" : {
        "keyB" : "a new valueB"
    }
}';

$registry->loadString($json1);
$registry->loadString($json2);
```

Output

```
Array(
    field => Array(
        keyA => valueA
        keyB => a new valueB
    )
)
```

#### Merge another Registry

``` php
$object1 = '{
	"foo" : "foo value",
	"bar" : {
		"bar1" : "bar value 1",
		"bar2" : "bar value 2"
	}
}';

$object2 = '{
	"foo" : "foo value",
	"bar" : {
		"bar2" : "new bar value 2"
	}
}';

$registry1 = new Registry(json_decode($object1));
$registry2 = new Registry(json_decode($object2));

$registry1->merge($registry2);
```

If you just want to merge first level, do not hope recursive:

``` php
$registry1->merge($registry2, false); // Set param 2 to false that Registry will only merge first level
```

## Dump to file.

``` php
$registry->toString();

$registry->toString('xml');

$registry->toString('ini');
```

## Using YAML

Add Symfony YAML component in `composer.json`

``` json
{
	"require-dev": {
		"symfony/yaml": "~2.0"
	}
}
```

Using `yaml` format

``` php
$registry->loadFile($yamlFile, 'yaml');

$registry->loadString('foo: bar', 'yaml');

// Convert to string
$registry->toString('yaml');
```


## Installation via Composer

Add `"joomla/registry": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/registry": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/registry "dev-master"
```
