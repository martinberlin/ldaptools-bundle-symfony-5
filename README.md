# LdapToolsBundle [![Build Status](https://travis-ci.org/ldaptools/ldaptools-bundle.svg)](https://travis-ci.org/ldaptools/ldaptools-bundle) [![AppVeyor Build Status](https://ci.appveyor.com/api/projects/status/github/ldaptools/ldaptools-bundle?branch=master&svg=true)](https://ci.appveyor.com/project/ChadSikorra/ldaptools-bundle-td18j) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ldaptools/ldaptools-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ldaptools/ldaptools-bundle/?branch=master) [![Latest Stable Version](https://poser.pugx.org/ldaptools/ldaptools-bundle/v/stable.svg)](https://packagist.org/packages/ldaptools/ldaptools-bundle)
-----------

The LdapToolsBundle provides easy integration of LDAP for Symfony via [LdapTools](https://github.com/ldaptools/ldaptools).

### About this fork

This was upgraded so it's possible to use Symfony 5.4 in my time working for [www.skygate.de](https://www.skygate.de)

Please use also package [ldaptools-php8](https://github.com/martinberlin/ldaptools-php8) in it's latest **^1** release.
Remember that composer does allow external repositories **ONLY** in the root *composer.json* other composers fetched from Vendors are not allowed to use this (Or it would be a chaos that might suck the universe into a black hole)
Just as an example if you would like to use both of this packages into your composer.json with Symfony 5.4 and PHP 8:

```json
{
"require" : {
        "ldaptools/ldaptools" : "^1",
        "ldaptools/ldaptools-bundle": "^1"
        }
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/martinberlin/ldaptools-bundle-symfony-5.git",
            "name": "ldaptools/ldaptools-bundle"
        },
        {
            "type": "git",
            "url": "https://github.com/martinberlin/ldaptools-php8.git",
            "name": "ldaptools/ldaptools"
        }
    ],
    "minimum-stability": "stable"
}
```

**Please fix the ldaptools-bundle to 1.0 to use PHP 7.4. The Tag 1.0.1 will work only with PHP 8.0 / 8.1**

It's important to notice that to meet that min. stability referenced packages should have **a valid release Tag** otherwise you need to use dev-branch and that is never a good idea. Since it won't work with min. stability:stable. Do not ever turn this to dev for any production application!

* An [LDAP authentication provider](/Resources/doc/LDAP-Authentication-Provider.md), including AdvancedUserInterface support.
* An [LDAP form type](/Resources/doc/LDAP-Object-Form-Type.md) to easily use LDAP objects in forms.
* An LDAP type for Doctrine to easily store and retrieve LDAP results in a Doctrine entity.
* Logging capabilities for LDAP operations.
* Web Debug Toolbar integration for LDAP operations.
* Integration of [LdapTools events](/Resources/doc/LDAP-Events.md) for LDAP operations (authentication, creation, modification, etc) using service tags.

**Note**: The LdapTools library requires PHP 5.6+.

### Installation

The recommended way to install the LdapToolsBundle is using [Composer](http://getcomposer.org/download/):

```bash
composer require ldaptools/ldaptools-bundle
```

Then enable the bundle in the kernel:

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new LdapTools\Bundle\LdapToolsBundle\LdapToolsBundle(),
        );

        // ...
    }
}
```

### Getting Started

After installing the bundle you can run the following command to assist in generating/testing your LDAP config:

```php
# It will prompt for some basic questions (LDAP server, username/password to use, etc)
php bin/console ldaptools:generate:config
```

Adding your domain(s) to the `config.yml` file is as easy as the following example: 

```yaml
# app/config/config.yml
ldap_tools:
    domains:
        # The below "example" key can be anything you want. It just has to be a unique name for the YML config.
        example:
            # The LDAP FQDN is required
            domain_name: example.local
            # The username to use for the LDAP connection
            username: foo
            # The password to use for the username
            password: secret
            # The base DN for LDAP searches (queried from the RootDSE if not provided)
            base_dn: "dc=example,dc=local"
            # The LDAP servers to use for the connection (Queried from DNS if not provided)
            servers: ["dc1", "dc2", "dc3"]
        # Define another domain if you want
        foo:
            domain_name: foo.bar
            username: foo
            password: bar
            servers: ['dc1.foo.bar', 'dc2.foo.bar']
            base_dn: 'dc=foo,dc=bar'
```

Domain configuration options are also documented in the [LdapTools documentation](https://github.com/ldaptools/ldaptools/blob/master/docs/en/reference/Main-Configuration.md#domain-section).

Then in your controller you can use the `ldap_tools.ldap_manager` service to query/modify/create LDAP objects...

```php

class DefaultController extends Controller
{
    public function indexAction()
    {
        $ldap = $this->get('ldap_tools.ldap_manager');
        
        $users = $ldap->buildLdapQuery()->fromUsers()->getLdapQuery()->getResult();
        
        $users->count();
        foreach ($users as $user) {
            $user->getFirstName();
            $user->getLastName();
            $user->getUsername();
        }
        
        # ...
    }
}
```

### Generate/Retrieve Your LDAP SSL Certificate

If you want a quick way to retrieve your server's LDAP SSL certificate you can run a simple command to output it:

```php
# Just supply your LDAP server name...
php bin/console ldaptools:generate:sslcert --server "servername"
```

### Documentation

* [Configuration Reference](/Resources/doc/Configuration-Reference.md)
* [LDAP Authentication Provider](/Resources/doc/LDAP-Authentication-Provider.md)
* [LDAP Authentication with the FOSUserBundle](/Resources/doc/LDAP-Authentication-With-The-FOSUserBundle.md)
* [Save LDAP Users to the Database After Login](/Resources/doc/Save-LDAP-Users-to-the-Database-After-Login.md)
* [LDAP Object Form Type](/Resources/doc/LDAP-Object-Form-Type.md)
* [LdapTools LDAP Events](/Resources/doc/LDAP-Events.md)
* [Bundle Events Reference](/Resources/doc/Bundle-Event-Reference.md)
* [LDIF Parser URL Loaders](/Resources/doc/LDIF-Parser-URL-Loaders.md)
