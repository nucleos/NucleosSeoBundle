NucleosSeoBundle
======================
[![Latest Stable Version](https://poser.pugx.org/nucleos/seo-bundle/v/stable)](https://packagist.org/packages/nucleos/seo-bundle)
[![Latest Unstable Version](https://poser.pugx.org/nucleos/seo-bundle/v/unstable)](https://packagist.org/packages/nucleos/seo-bundle)
[![License](https://poser.pugx.org/nucleos/seo-bundle/license)](https://packagist.org/packages/nucleos/seo-bundle)

[![Total Downloads](https://poser.pugx.org/nucleos/seo-bundle/downloads)](https://packagist.org/packages/nucleos/seo-bundle)
[![Monthly Downloads](https://poser.pugx.org/nucleos/seo-bundle/d/monthly)](https://packagist.org/packages/nucleos/seo-bundle)
[![Daily Downloads](https://poser.pugx.org/nucleos/seo-bundle/d/daily)](https://packagist.org/packages/nucleos/seo-bundle)

[![Continuous Integration](https://github.com/nucleos/NucleosSeoBundle/actions/workflows/continuous-integration.yml/badge.svg?event=push)](https://github.com/nucleos/NucleosSeoBundle/actions?query=workflow%3A"Continuous+Integration"+event%3Apush)
[![Code Coverage](https://codecov.io/gh/nucleos/NucleosSeoBundle/graph/badge.svg)](https://codecov.io/gh/nucleos/NucleosSeoBundle)
[![Type Coverage](https://shepherd.dev/github/nucleos/NucleosSeoBundle/coverage.svg)](https://shepherd.dev/github/nucleos/NucleosSeoBundle)

The NucleosSeoBundle is a fork of [SonataSeoBundle](https://github.com/sonata-project/SonataSeoBundle/) which respects BC. It also provides sitemap functionality of the deprecated [NucleosSitemapBundle](https://github.com/nucleos/NucleosSitemapBundle).

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this library:

```
composer require nucleos/seo-bundle
```


### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Nucleos\SeoBundle\NucleosSeoBundle::class => ['all' => true],
];
```

### Configure the Bundle

Create a configuration file called `nucleos_seo.yaml`:

```yaml
# config/routes/nucleos_seo.yaml

nucleos_seo:
    resource: '@NucleosSeoBundle/Resources/config/routing/sitemap.yml'
    prefix: /
```

If you want to use symfony cache, you should define a new cache pool (PSR 6) and create an adapter to map it to a simple cache (PSR 16):

```yaml
nucleos_seo:
    cache:
        service: 'sitemap.cache.simple'

framework:
    cache:
        pools:
            sitemap.cache:
                adapter: cache.app
                default_lifetime: 60

services:
    sitemap.cache.simple:
        class: 'Symfony\Component\Cache\Psr16Cache'
        arguments:
            - '@sitemap.cache'
```


### Add static entries

You can add static entries in your yaml config:

```yaml
# config/packages/nucleos_seo.yaml

nucleos_seo:
    static:
        - { url: 'http://example.com', priority: 75, changefreq: 'weekly' }
```

### Add a custom sitemap

If you want to create a custom sitemap, the only thing you have to do is to create a service that uses
`Nucleos\SeoBundle\Sitemap\SitemapServiceInterface` and tag the service with `nucleos_seo.sitemap`.

```xml
    <service id="App\Sitemap\CustomSitemap">
      <tag name="nucleos_seo.sitemap"/>
    </service>
```

## License

This bundle is under the [MIT license](LICENSE.md).

