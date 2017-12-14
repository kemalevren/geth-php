A PHP wrapper to the geth JSON-RPC API 
======================================


Requirements
============

* PHP >= 7.0.x
* phpunit >= 6.5.*
* cURL Extension

Installation
============

    composer require kemalevren/geth-php
    
    
Laravel 5
=========

Add the service provider and facade in your config/app.php

Service Provider

    kemalevren\Geth\Laravel5\ServiceProviders\GethPhpServiceProvider

Facade

    'Geth'            => 'kemalevren\Geth\Laravel5\Facades\Geth',


Usage
=====


