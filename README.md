# Laravel Naughty Username Validator

## Installation

```bash
composer require claraleigh/naughty-username
```

## Usage

```php
use Claraleigh\NaughtyUsername\NaughtyUsername;

// Test if a username is naughty
$request->validate([
    'username' => [
        'required',
        new NaughtyUsername(),
    ],
]);


// Use your own list of naughty usernames
$request->validate([
    'username' => [
        'required',
        new NaughtyUsername(
            ['path/to/blacklist.js'],
            ['path/to/whitelist.js']
        ),
    ],
]);
```
