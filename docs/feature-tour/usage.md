# Usage

Shortcut gives an element or an external URL a shorter address you can share. For an entry, pass the entry itself so Shortcut can associate the link with that element.

In an entry's Twig template, this example obtains its shortcut and displays a labelled link:

```twig
{% set shortcut = craft.shortcut.get({ element: entry }) %}

{% if shortcut %}
    <a href="{{ shortcut.getUrl() }}">Share {{ entry.title }}</a>
{% endif %}
```

Open the generated link and check that it reaches the entry. If an external provider is configured, `getUrl()` returns that provider's shortened URL. Otherwise, it returns Shortcut's local URL.

You can also shorten a fixed URL. This is useful for a destination outside Craft, such as an external booking page:

```twig
{% set shortcut = craft.shortcut.get({ url: 'https://example.com/bookings' }) %}

{% if shortcut %}
    <a href="{{ shortcut.getUrl() }}">Book a Place</a>
{% endif %}
```

Replace the example URL with your destination. Use the element form for Craft content when you want the shortcut associated with the element, and the URL form when you have a standalone destination.
