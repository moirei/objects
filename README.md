# moirei/objects

A package for simple and lightweight data/array objects.

## Install

```bash
composer require moirei/objects
```

> Note: this package does not automatically cast nested objects.

## Usage

### Object

```php
final class User extends BaseObject
{
  public string $name;
  public string $email;
}
```

```php
$user = new User([
  'name' => 'Joe',
  'email' => 'top_lad@mail.com',
]);

// or

$user = User::make([
  'name' => 'Joe',
  'email' => 'top_lad@mail.com',
]);

// or

$user = User::make();
$user->name = 'Joe';
$user['email'] = 'top_lad@mail.com';

...

dump($user->toArray());
dump($user);
```

Accessing or mutation undefined properties throws an exception.

### Non-strict Object

```php
/**
 * @property string|null $city
 */
final class User extends BaseObject
{
  protected $strict = false;
  public string $name;
  public string $email;
}
```

```php
$user = User::make([
  'name' => 'Joe',
  'email' => 'top_lad@mail.com',
]);

...
$user->city = 'Adelaide';
```

Accessing or mutation undefined properties is allowed.

## Rationale

If you're no longer comfortable passing or returning data as untyped array to your app logic, and want a simple solution, then this package is for you.

For the below example, we can be confident of the data type being returned from the action.

```php
final class InstallationStatus extends BaseObject
{
    public bool $completed = false;
    public ?string $key;
    /** @var string[] */
    public array $errors = [];
}
```

```php
class InstallAppAction{
  use AsAction;

  public function handle(string $code): InstallationStatus{
    $status = InstallationStatus::make();

    try{
      // logic
      $status->completed = true;
      $status->key = '...';
    }catch(\Exception $e){
      $status->errors = [
        $e->getMessage()
      ];
    }

    return $status;
  }
}
```

## License

[MIT](./LICENSE)

Special thanks to Eduardo San Martin Morote ([posva](https://github.com/posva)) for [encoding utlities](https://github.com/vuejs/vue-router-next/blob/v4.0.1/src/encoding.ts)
