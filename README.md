# Integration Workshop

Welcome to Magento Commerce Order Management workshop! In this repository you will find a simple cli application which will be used to build integrations with [Magento Commerce Order Management](https://magento.com/products/commerce-order-management).

We choose to use cli application with SQLite as most basic one. You can run it on your host machine without need to run local web server, database server, docker or virtual machine.

The application build on top of [Silex microframework](http://silex.sensiolabs.org/), [Symfony Console component](http://symfony.com/doc/current/components/console/introduction.html) and [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html). These allow us to provide thin, but yet powerful, platform for building an integration. *Although, keep in mind, this stack was chosen only for purpose of this workshop, we encourage you to choose tools and framework you feel more comfortable with when building integrations.*

## Requirements

In order to run application you would need to have:

- [PHP Cli](http://php.net/downloads.php) - version 5.6 or 7.0
- [SQLite PDO driver](http://php.net/manual/en/ref.pdo-sqlite.php)
- [Bcmath PHP extension](http://php.net/manual/en/book.bc.php) - required by AMQP library
- [Composer](https://getcomposer.org/) - dependency management tool for PHP

## Getting started

Clone this repository and run [composer](https://getcomposer.org/) install. It will pull some 3rd party libraries required by the application.

```bash
$ composer install
```

Create a database file

```bash
$ app/console orm:schema-tool:create
```

Configure your AMQP connection

```bash
$ open app/config/parameters.yml # and set options in amqp section
```

You all set, now you can start building an integration!

## Quick reference
- [Explore the application](#explore-the-application)
    - [Directory Structure](#directory-structure)
    - [Database Schema](#database-schema)
- [Extend the application](#extend-the-application)
    - [Adding a command](#adding-a-command)
    - [Making API calls, publishing and broadcasting messages](#making-api-calls-publishing-and-broadcasting-messages)
    - [Consuming messages](#consuming-messages)
    - [Declaring your own entities](#declaring-your-own-entities)

## Explore the application

We've tried to minimize application and hide implementation details, so you don't need to learn how components work, just write your code in certain places and you should be good to go!

### Directory Structure

- **app** - application files
    - **config** - configuration
    - **console** - cli entry point
- **src** - source files
- **tests** - tests
- **vendor** - 3rd party libraries installed through composer

### Database Schema

We've pre-created few entities which should be useful for building warehouse integration. Each entity represented by class, and repository which allows to persist and query entities.

- **Sku** - an entity representing stock keeping unit
- **Stock** - an entity representing stock level for a given SKU
- **ShipmentRequest** - an entity representing a shipment request coming from MCOM
- **ShipmentRequestLine** - an entity which represents a single line of the shipment request

You can use any SQLite client to review and modify content of the database. But in case you don't have any we've created a few simple commands which prints all records from database:

```
$ app/console query:shipmentrequest:all # Prints all shipment requests
$ app/console query:shipmentrequestline:all # Prints all shipment request lines
$ app/console query:sku:all # Prints all sku
$ app/console query:stock:all # Prints all stock levels
```

## Extend the application

### Adding a command

During workshop you would need to build additional commands to perform additional actions. Commands in console application are similar to controllers in normal web application.
Each command has a name and arguments, you declare these per command. You can run command using cli entry point - `app/console`.

#### 1. Create a command class

Simply create a new PHP class inside of the `src/Command` folder and extend it from `AbstractCommand`. Make sure the namespace is `Magento\Bootstrap\Command`.

#### 2. Override configure method

Create `configure` method with the same signature as in the parent class and set command name and list of arguments in the body of the method. For example:

```php
protected function configure()
{
    $this->setName('update-stock-level') // name will be used to run your command
        ->setDescription('Send stock level update') // description will be shown when you run app/console without arguments
        ->addArgument('sku', InputArgument::REQUIRED, 'SKU') // you can add as many arguments as you need
        ->addArgument('qty', InputArgument::REQUIRED, 'Quantity')
    ;
}
```

#### 3. Declare execute method

Last step, you need to implement logic of your command. You would need to override `execute` method and implement your logic inside. For exmaple, this command will publish a message to the API client:

```php
protected function execute(InputInterface $input, OutputInterface $output)
{
    $this->getApiClient()->discover('oms')
        ->publish(new Request('magento.inventory.source_stock_management.update', '1', [
            'stock' => [
                'sku' => $input->getArgument('sku'),
                'quantity' => $input->getArgument('qty'),
                'source_id' => 'P_STORE_1',
                'type' => 'GOOD',
            ],
        ]));
}
```

You can access arguments declared in the step 2 using `$input->getArgument('argument_name')` method.

#### 4. Run your command

To run your command simply type `app/console` and name of your command. Example above you can run like this: 

```bash
$ app/console update-stock-level WB023115 14
```

### Making API calls, publishing and broadcasting messages

This application uses [message-bus-client](https://github.com/skolodyazhnyy/message-bus-simple-client) library, which provide very basic transport bindings for [Magento Shared Specification](https://magento-mcom.github.io/docs). *Keep in mind: It's not an official library, just a basic implementation for purpose of this workshop.* 

In the command, you can access API client using `AbstractCommand::getApiClient` method.

#### Broadcast

You can broadcast message to all consumers using `ClientInterface::broadcast` method.

```php
$this->getApiClient()
    ->boradcast(new Request(
        'message-topic',
        '1', // version
        [
            'argument_foo' => [1, 2, 3],
            'argument_bar' => 'ten',
        ]
    ));
```

#### Publish

You can publish message to one, particular service using `ClientInterface::discover` method to choose the service, and then `EndpointInterface::publish` to actually publish a message.

```php
$this->getApiClient()
    ->discover('oms')
    ->publish(new Request(
        'message-topic',
        '1', // version
        [
            'argument_foo' => [1, 2, 3],
            'argument_bar' => 'ten',
        ]
    ))
```

#### RPC Call

You can make a synchronous call to particular service using `ClientInterface::discover` method to choose the service, and then `EndpointInterface::call` to actually perform a call. This method call will return you a promise which can be resolved into Response.

```php
$promise = $this->getApiClient()
    ->discover('oms')
    ->call(new Request(
        'message-topic',
        '1', // version
        [
            'argument_foo' => [1, 2, 3],
            'argument_bar' => 'ten',
        ]
    ));

$response = $promise->resolve($timeout);

var_export($response->getValue());
```

#### Advanced details

- API client is defined in `Magento\Bootstrap\DependencyInjection\Provider\ApiProvider`, you can tune it up, if needed.
- More examples for client library can be found [at Github](https://github.com/skolodyazhnyy/message-bus-simple-client/tree/master/examples).

### Consuming messages

In previous section you learn how to broadcast and send messages to other services. Now it's time to see how you can receive messages from other services! 

First, you need to create bindings for topics your application is interested in. Your application may implement one or multiple services defined in [Magento Shared Service Specification](https://magento-mcom.github.io/docs/specification/#services). Implementing a service means being able to process all command and queries and dispatch all events defined in specification. Apart of that your application also can subscribe to any event exposed by other services.

You can subscribe to topics using API of [message-bus-client](https://github.com/skolodyazhnyy/message-bus-simple-client) library. You already learn how to discover remote endpoint, but as well you can define your own services using `ClientInterface::define` method.

```php
$service = $client->define('my-service');
```

Now, you can create bindings for topics you are interested in by calling `ServiceInterface::bind` method. It accepts instance of the `BindingInterface` interface as an argument. You can create your own implementation which will work best for you, but for quick start you can use `CallbackBinding`. It allows you to bind any callable to the topic using `CallbackBinding::on` method.

```
$service->bind(
    (new CallbackBinding())
        ->on('message-topic', '0', function(Request $request) {
            // for sync methods you can return Response
            return new Response('result');
        })
        ->on('some-other-topic', '0', function(Request $request) {
            // for async methods you don't need to return anything
        })
);
```

These bindings can be defined in `src/DependencyInjection/Provider/ServiceProvider.php`. This file already have service defined, all you need to do is to add your callbacks.

Once bindings are done you can use service in AMQP or HTTP consumer.

#### AMQP consumer

There is already an AMQP consumer in the application, so you don't need to worry about a thing. You can start consuming messages by running:

```bash
$ app/console consume
```

### Declaring your own entities

We decided to use Doctrine ORM in the application because it nicely hides all complexity of persistance layer providing nice object-level API. There are few entities defined in `src/Model/Entity`, you can use them as example to define your owns.

#### 1. Create entity and define mapping

Create a new class in `src/Model/Entity` and define properties of your entity.

```php
<?php

namespace Magento\Bootstrap\Model\Entity;

class Sku
{
    private $sku;
    private $name;

    // Define __construct, getters and setters depending on mutability of each field
}
```

Once you decide what fields your model will have, add Doctrine Annotations to explain what SQL types doctrine should use in the database.

```php
<?php
namespace Magento\Bootstrap\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Magento\Bootstrap\Model\Repository\SkuRepository")
 * @ORM\Table(name="sku")
 */
class Sku
{
    /**
     * @ORM\Id                  -- exactly one of the fields should be marked as primary key
     * @ORM\Column(length=128)
     */
    private $sku;

    /**
     * @ORM\Column(length=128)
     */
    private $name;
    
    // ...
}
```

Read more about annotations in [Doctrine documentation](http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/basic-mapping.html).
