SeiffertConsoleExtraBundle
====================

This bundle adds support for commands defined as DI services.

## Setup

Require the package via composer:

`composer.json`:

        "require": {
            ...
            "seiffert/console-extra-bundle": "1.0.0",
            ...
        }

Activate the bundle in your AppKernel:

`app/AppKernel.php`:

        public function registerBundles()
        {
            $bundles = array(
                ...
                new Seiffert\ConsoleExtraBundle\SeiffertConsoleExtraBundle(),
                ...
            );
            ...
        }

Disable automatic command registration in your bundle by overriding `Bundle::registerCommands()` with an empty
implementation:

`src\You\YourBundle\YouYourBundle.php`:

        public function registerCommands(Application $application)
        {
            // commands are registered by SeiffertConsoleExtraBundle
        }

## Usage

After following the above setup steps, you can start defining all your commands as services. Command services are being
identified by a special tag `console.command`:

`src\You\YourBundle\Resources\config\services.yml`:

    parameters:
        acme_demo.test_command.class: Acme\DemoBundle\Command\TestCommand

    services:
        acme_demo.test_command:
            class: %acme_demo.test_command.class%
            tags:
                - { "name": "console.command" }

Your commands will be added to the dependency injection container. This enables you to write commands that have their
dependencies injected and therefore are truly unit-testable.