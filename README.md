# GCP Metadata

> Get the metadata from a Google Cloud Platform environment.

[![Current version](https://img.shields.io/packagist/v/kreait/gcp-metadata.svg)](https://packagist.org/packages/kreait/gcp-metadata)
[![Supported PHP version](https://img.shields.io/packagist/php-v/kreait/gcp-metadata.svg)]()
[![GitHub license](https://img.shields.io/github/license/kreait/gcp-metadata-php.svg)](https://github.com/beste/gcp-metadata-php/blob/main/LICENSE)
[![Tests](https://github.com/beste/gcp-metadata-php/actions/workflows/tests.yml/badge.svg)](https://github.com/beste/gcp-metadata-php/actions/workflows/tests.yml)
[![Sponsor](https://img.shields.io/static/v1?logo=GitHub&label=Sponsor&message=%E2%9D%A4&color=ff69b4)](https://github.com/sponsors/jeromegamez)

> [!IMPORTANT]
> **Support the project:** If this library saves you or your team time, please consider
> [sponsoring its development](https://github.com/sponsors/jeromegamez).

> [!NOTE]
> The project moved from the `kreait` to the `beste` GitHub Organization in January 2026.
> The namespace remains `Kreait\Laravel\Firebase` and the package name remains `kreait/laravel-firebase`.
> Please update your remote URL if you have forked or cloned the repository.


```bash
$ composer install kreait/gcp-metadata
```

```php
use Kreait\GcpMetadata;

$metadata = new GcpMetadata();
```

#### Check if the metadata server is available

```php
$isAvailable = $metadata->isAvailable();
```

#### Get all available instance properties

```php
$data = $metadata->instance();
```

#### Get all available project properties

```php
$data = $metadata->project();
```

#### Access a specific property

```php
$data = $metadata->instance('hostname');
```

#### Wrap queries in a try/catch block if you don't check for availability

```php
use Kreait\GcpMetadata;

$metadata = new GcpMetadata();

if ($metadata->isAvailable()) {
    echo $metadata->instance('hostname');
}

try {
    echo $metadata->instance('hostname');   
} catch (GcpMetadata\Error $e) {
    echo $e->getMessage();
}
```
