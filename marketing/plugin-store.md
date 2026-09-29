Shortcut creates compact URLs from Craft without tying templates to one shortening service. Generate, store, and retrieve short links through a consistent API while choosing the provider that suits the project.

Pass a URL to Shortcut from Twig or PHP and receive a compact version suitable for messages, printed material, or other space-conscious contexts. Previously generated results can be retained rather than recreated on every request.

## Features

- Turn long destinations into compact links from Twig or PHP.
- Reuse generated shortcuts instead of requesting the same link repeatedly.
- Select a shortening service that fits the project.
- Use Bitly, TinyURL, is.gd, Rebrandly, or Short.io when links should be created externally.
- Keep shortened URLs within Craft when an external provider is unnecessary.
- Generate and retrieve shortcuts close to where they are presented.
- Add another service behind a consistent shortener interface.
- Keep service selection and credentials out of presentation templates.
