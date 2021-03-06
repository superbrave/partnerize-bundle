## Introduction
A Symfony bundle to talk to the Partnerize API.

## Overview
Provides methods (not all) to send, approve and reject conversions in Partnerize.

## Setup

### Installation

Using this package is similar to all Symfony bundles.

#### Step 1.

Open a command console, enter your project directory and execute the
following command to download the latest version of this bundle:

```
$ composer require superbrave/partnerize-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

#### Step 2.

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Superbrave\PartnerizeBundle\SuperbravePartnerizeBundle(),
        );
        // ...
    }
    // ...
}
```

#### Step 3.

You can now add the configuration for the bundle to your application.
If you are using Symfony ^4.0, just add the `superbrave_partnerize.yml` to your
packages folder and fill it with the following config values:
```yaml
superbrave_partnerize:
    application_key: 'YOUR_APPLICATION_KEY'
    user_api_key: 'YOUR_USER_API_KEY'
    campaign_id: 'YOUR_CAMPAIGN_ID'
    base_uri: (optional, default 'https://api.partnerize.com/')
    tracking_uri: (optional, default 'https://prf.hn/conversion/')
```

## Usage

### Conversions

#### Create

```php
public function createConversion(Sale $sale): string;
```

You can use this bundle to create conversions in Partnerize through their API.
The `createConversion` method takes a `Sale` object as its parameter.
This `Sale` object takes an array of `Item` objects, which are the products being sold.

The `createConversion` method returns the `conversionId` of the conversion that was created in Partnerize.

#### Approve

```php
public function approveConversion(string $conversionId): Job;
```

Once a conversion has been approved on your side of the application,
you can use the `approveConversion` to approve it in Partnerize.
This method only needs the `conversionId` that was returned by the `createConversion` method.

The `approveConversion` method returns a `Job` object.

#### Reject

```php
public function rejectConversion(string $conversionId, string $reason): Job;
```
If you want to reject a conversion, you can use the `rejectConversion` method.
Supply the method with the `conversionId` of the conversion and a `reason` for rejecting it.

The `rejectConversion` method returns a `Job` object.

### Jobs

A `Job` is an object that contains data about the work that is being done on the Partnerize side.
Creating, approving, rejecting a conversion will return you that `Job` object.
It will tell you the current `status` of whatever you've pushed.

#### Update

```php
public function getJobUpdate(string $id): Job;
```
You can get an update for a `Job` by calling the `getJobUpdate` method,
this method takes a single parameter: the `id` found in the `Job` you received earlier.
So it is recommended to save this `id` somewhere after pushing something to Partnerize.

This method will return the `Job` object you called for.

#### Response

```php
public function getJobResponse(string $id): Response;
```
Once an update tells you that the `status` of a `Job` is completed, you can retrieve the `Response`
by calling the `getJobResponse` method. This method once again, only takes the `jobId` as the first parameter.

This method will return a `Response` object containing any `errors` and/or `conversionItems` that are
returned after the `Job` has done its work.

## Example

The Partnerize Client can be autowired into a class if that is enabled in your application.
```php
class PartnerizeHandler
{
    private $client;

    public function __construct(Superbrave\PartnerizeBundle\Client\PartnerizeClient $client)
    {
        $this->client = $client;     
    }

    public function sendConversionToPartnerize(): void
    {
        $item = new Superbrave\PartnerizeBundle\Model\Item('yourCategory', 1 /* quantity */);
        $item->setProductBrand('productBrand');
        $item->setProductName('productName');
        $item->setProductType('productType');
        $item->setSku('productSku');
        $item->setValue(10.00);
    
        $sale = new Superbrave\PartnerizeBundle\Model\Sale('yourClickReference', 'yourConversionReference');
        $sale->setConversionTime(new \DateTime());
        $sale->setCountry('NL');
        $sale->setCurrency('EUR');
        $sale->setCustomerReference('customer123456')
        $sale->setCustomerType(Superbrave\PartnerizeBundle\Model\Sale::CUSTOMERTYPE_NEW);
        $sale->setVoucher('yourVoucherCode');
        $sale->addItem($item);
        
        $conversionId = $this->client->createConversion($sale);
        $job = $this->client->approveConversion($conversionId);
        
        if ($job->getStatus() === Superbrave\PartnerizeBundle\Model\Job::STATUS_COMPLETE) {
            $response = $this->client->getJobResponse($job->getJobId());
    
            // Use $response->getErrors() and $response->getErrorsCount() to check for any errors
            // Use $response->getConversionItems() to get the conversion that was approved, if it is
            // empty your conversion was already approved (or rejected).
        } else {
            // Wait some time and check again with getJobUpdate()
        }
    }
}
```

## API

If you need any more information or want to contribute to this client,
you can go to the [Partnerize API documentation](https://performancehorizon.docs.apiary.io/).

## License

This bundle is under the MIT license. See the complete license [in the bundle](LICENSE)
