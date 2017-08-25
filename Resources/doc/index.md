Getting started
===============

Prerequisites
-------------

This bundle requires Symfony 2.8+.

Installation
------------

Add [`youmesoft/callr-bundle`](https://packagist.org/packages/youmesoft/callr-bundle)
to your `composer.json` file:

    php composer.phar require "youmesoft/callr-bundle"

Register the bundle in `app/AppKernel.php`:

``` php
public function registerBundles()
{
    return array(
        // ...
        new Youmesoft\CallrBundle\YoumesoftCallrBundle(),
    );
}
```

Configuration
-------------

Configure bundle in your `config.yml` :

``` yaml
youmesoft_callr:
    debug:
        enabled: true
        mode: "orm" # can be one of values: file or orm
        manager: "app" # name of entity manager. Type default if you use default one in doctrine configuration required if "orm" mode is used
        path: "%kernel.root_dir/../var/callr%" # path to the folder where to store the logs. required if "file" mode is used
    disable_delivery: true
    auth_type: "login_password" # can be one of values: login_password or api_key
    credentials:
        username: "%callr_username%" # required if "login_password" auth_type is used
        password: "%callr_password%" # required if "login_password" auth_type is used
        key: "%callr_key%" # required if "api_key" auth_type is used
```


Add the extensions to your mapping

Bundle is using its own entities to do its work. You need to register its mapping in Doctrine when you want to use them.

``` yaml
    # app/config/config.yml
    doctrine:
        orm:
            entity_managers:
                default:
                    mappings:
                    callr_log:
                        type: annotation
                        prefix: Youmesoft\CallrBundle\Entity
                        dir: "%kernel.root_dir%/../vendor/youmesoft/callr-bundle/Entity"
                        alias: CallrLog # (optional) it will default to the name set for the mapping
                        is_bundle: false
```

**Note**

    If you are using the short syntax for the ORM configuration, the ``mappings``
    key is directly under ``orm:``

**Note**

    If you are using several entity managers, take care to register the entities
    for the right ones.