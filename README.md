# Micro framework

This is a mix of several popular libraries that provide basic functionality for working with PHP CLI (Console) and MVC (Web) architecture.

## Installation
In your `composer.json` file, add the lines below:
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/goncharovei/framework"
    }
],
"require": {
    "php": "^8.2",
    "goncharovei/framework": "^1.0"
},
``` 
And then do it `composer install`.  

## How to use

### Web

Routes are loaded from the folder specified by the `loadRoutes` method of the Http core. To generate Route links, use the `route()` function.<br>
How to work with Routes, see [thephpleague/route](https://github.com/thephpleague/route) and [nikic/FastRoute](https://github.com/nikic/FastRoute).

The Twig template engine is used for views. Its functions can be extended via the `addTwigExtension` method of the Http core.<br>
More details about working with Twig [here](https://twig.symfony.com/documentation).

To work with Request and Response, the [GuzzleHttp\Psr7](https://github.com/guzzle/psr7) library is used.<br>
For emitting response used [httpsoft/http-emitter](https://github.com/httpsoft/http-emitter).

Simple custom exception handling [set_exception_handler](https://www.php.net/manual/en/function.set-exception-handler.php) is implemented.<br>
The view for outputting errors is set via the `registerExceptionOutput` method of the Http core.

### Console

Execute command<br>
`php console`

See details here [The Console Symfony](https://symfony.com/doc/current/components/console.html#learn-more).

### Settings

Environment variables and configurations are supported.

Environment variables are in the ".env" file.<br>
Configuration is in the "config" folder.

### Query Builder

It is accessible globally through a static DB Instance.<br>
See details here [Using Illuminate Database](https://laravel.com/docs/11.x/queries).

### Mail Sender

Available via the "mailer" helper function.<br>
See details here [Using PHPMailer](https://github.com/PHPMailer/PHPMailer/tree/master/examples).

### Log

Available via the "logger" helper function.<br>
See details here [Using Monolog](https://github.com/Seldaek/monolog/blob/main/doc/01-usage.md).

### Tests
Execute command<br>
`php vendor/bin/phpunit --testsuite Unit --testdox --testdox-summary`

See details here [The Command-Line Test Runner](https://docs.phpunit.de/en/11.5/textui.html).

### PHPStan
Execute command<br>
`vendor/bin/phpstan analyse app`

See details here [Command Line Usage](https://phpstan.org/user-guide/command-line-usage).

### What was used
The Framework uses the recommended PHP standards: PSR-1, PSR-3, PSR-4, PSR-7, PSR-11, PSR-12, PSR-15, PSR-17.

When designing the logic of the Framework, some elements of the [Laravel Framework](https://github.com/laravel/framework) logic were used.

The implementation of the Framework functionality uses the following [programming patterns](https://designpatternsphp.readthedocs.io/en/latest/index.html): Abstract Factory, Factory Method, Fluent Interface, Prototype, Static Factory, Dependency Injection, Facade, Null object, Data Transfer Object.
