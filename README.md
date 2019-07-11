# Controllers Generator

A Laravel Artisan command to automatically generate controllers.

## Requirements

- PHP 5.6+
- Laravel 5.4+

## Installing

Use Composer to install it:

```
composer require filippo-toso/controllers-generator
```

## How does it work?

This generator is very simple. It builds a simple controller with CRUD capabilities (plus list) from a specified model. 

By default the generator doesn't overwrite existing controllers. 

## Configuration

You can publish the configuration file with the following command:

```
php artisan vendor:publish --tag=config --provider="FilippoToso\ControllersGenerator\ServiceProvider"
```

The config/controller-generator.php file allows you to:

- specify if flash messages should be added (requires laracasts/flash to work)
- specify the path format of the generated views
- specify the route format of the routes
- specify the url format of the routes

Just open the file and read the comments :)

## Options

The predefined use from command line is:

```
php artisan generate:controller {contoller} {model}
```

This command creates a {controller} with CRUD capabilities for the specified {model}.

If there is an existing controller in the App\Http\Controllers namespace it will not overwritten.

You can modify the default behavior using the following parameters:

```
php artisan generate:controller {contoller} {model} --overwrite 
```

With the overwrite option the generator will always overwrite the controller in the App\Http\Controllers namespace.

Other options include:

- --test : When set the generator creates also a test suite for the controller.
- --protected : When set the generator adds code to protect the routes from users without the required permissions.
- --owned : When both this option and --protected are set, the generator ads checks for ownership before updating and deleting resources.
- --routes : When set the generator will add the required routes to the routes/web.php file.
- --base-controller= : Specify the base controller that the generated controller will extend on.