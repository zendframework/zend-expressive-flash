# zend-expressive-flash

_Flash messages_ are self-expiring, session-based messages. They are typically
used when when you need to redirect after processing a request (e.g., when using
the [Post-Redirect-Get pattern](https://en.wikipedia.org/wiki/Post/Redirect/Get)),
but want to display a message back to the user indicating a processing result.

As an example, I may want to display a "Thank You" message to the user after
successfully completing a form. I can do that with flash messages.

When processing, I would create the message in my middleware:

```php
$flashMessages->flash('form-complete', 'Thank you; your submission was recorded.');
```

On the subsequent request, my middleware would pull that message:

```php
$message = $flashMessages->getFlash('form-complete');
```

On any subsequent requests, the message is no longer available!

## Installation

To use the component, install via [Composer](https://getcomposer.org):

```bash
$ composer require zendframework/zend-expressive-flash
```

> ### Persistence required
>
> zend-expressive-flash depends on zend-expressive-session, which defines
> abstractions around session containers &mdash; for use within applications for
> accessing session data &mdash; and session persistence (how the session data
> is persisted between requests, and reported to the client).
>
> Persistence requires a _persistence adapter_. We offer one basd on PHP's
> session extension via the package zend-expressive-session-ext; others may also
> be available soon.
