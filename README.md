# PB Web Media Audit Bundle

Provides an easily extendible Symfony Bundle to create an audit log.

## Installation
Install the audit-bundle using composer:

```
composer require pbweb/audit-bundle
```

## Usage
To insert an entry in the audit logger, create an `AuditEvent` and give it to the `AuditLog`

```php
$event = new \Pbweb\AuditBundle\Event\AuditEvent('test');
$log = $container->get('pbweb_audit.audit_log');
$log->log($event);
```

You can also create your own events (which we recommend), as long as they implement the `AuditEventInterface`.

### Flow
Events enter the audit logger which will then trigger the following events (in order) with the provided `AuditEventInterface` event as an argument:
1. `Pbweb\AuditBundle\Event\AppendAuditEvent`
1. The class name of `$event`
1. `Pbweb\AuditBundle\Event\LogAuditEvent`

That way you can hook into generic flow moments or only respond to specific events.

The Audit Bundle uses the Symfony event system so all the normal shenanigans (like listener priorities, stopping propagation, etc) work when handling these events.

## Event appenders
Events aren't usually complete by themselves, you probably want to append some data related to the event like the user that triggered it or the ip of the client.

To do this listen to the `Pbweb\AuditBundle\Event\AppendAuditEvent` event (either through a [listener](http://symfony.com/doc/current/event_dispatcher.html#creating-an-event-listener)
or [subscriber](http://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber)).
It will receive all `AuditEventInterface` events that go into the audit log.

### Default event appenders
The Audit Bundle comes packaged with a few event appenders that will be loaded by default, see the configuration section below on how to disable that.

If you use these appenders, you need to add `symfony/security` to your project.

## Loggers
Eventually the audit log entries need to go somewhere, either the database, a PSR logger or whatever you can think of.

The Audit Bundle comes packaged with a PSR logger and Doctrine logger (see below).

You can also implement your own logger by listening to the `Pbweb\AuditBundle\Event\LogAuditEvent` event.
It will receive all `AuditEventInterface` events that go into the audit log.

### PSR logger
By default the Audit Bundle loads the `PsrLogger`, which will use the `@logger` service to log events to your default log.
Look at the configuration section below on how to disable this behaviour.

### Doctrine logger
For small(ish) audit logs you could use the database as a data store.
The Audit Bundle comes with an `AbstractDoctrineLogger`.
The only thing you need to do is create a service that implements the `convertToEntity` method and give it the tag `kernel.event_subscriber`.

## Configuration
You can optionally configure the audit bundle in your `app/config.yml` file

```yaml
pbweb_audit:
    load_default_event_loggers: true    # loads the loggers defined in Pbweb/AuditBundle/Resources/config/default/loggers.yml
    load_default_event_appenders: true  # loads the event appenders in Pbweb/AuditBundle/Resources/config/default/appenders.yml
```

## Copyright
© PB Web Media

