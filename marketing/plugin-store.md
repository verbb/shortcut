Shortcut creates compact URLs from Craft without tying templates to one shortening service. Generate, store, and retrieve short links through a consistent API while choosing the provider that suits the project.

Pass a URL to Shortcut from Twig or PHP and receive a compact version suitable for messages, printed material, or other space-conscious contexts. Previously generated results can be retained rather than recreated on every request.

## Features

- **Short URLs:** Turn long destinations into compact links from Twig or PHP.
- **Stored results:** Reuse generated shortcuts instead of requesting the same link repeatedly.
- **Provider choice:** Select a shortening service that fits the project.
- **Hosted providers:** Use Bitly, TinyURL, is.gd, Rebrandly, or Short.io when links should be created externally.
- **Local links:** Keep shortened URLs within Craft when an external provider is unnecessary.
- **Template API:** Generate and retrieve shortcuts close to where they are presented.
- **Extensible providers:** Add another service behind a consistent shortener interface.
- **Central configuration:** Keep service selection and credentials out of presentation templates.
- **Flexible providers:** Use a supported public shortener or implement another provider behind the same plugin interface. Project code can ask for a short URL without taking on each service’s request and response details.
