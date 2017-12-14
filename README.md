GETH-PHP
======================================
A PHP wrapper to the [Geth](https://github.com/ethereum/go-ethereum) [JSON-RPC](https://github.com/ethereum/wiki/wiki/JSON-RPC)

Requirements
============

* PHP >= 7.0.x
* phpunit >= 6.5.*
* cURL Extension
* illuminate/support >= 5.1.*

Installation
============

    composer require kemalevren/geth-php
    
    
Usage
=====
    ```php
    $geth = new \kemalevren\Geth\JsonRpc([
            // Geth JSON-RPC version
            'version' => '2.0',
            // Host part of address
            'host' => '127.0.0.1',
            // Port part of address
            'port' => 8545,
            // Return results as associative arrays instead of objects
            'assoc' => true,
    ]);
    
    $version = $geth->web3_getVersion();
    
    $accounts = $geth->eth_accounts();
    foreach($accounts as $account) {
        echo $account, ': ', $geth->eth_getBalance($account, 'latest'), PHP_EOL;
    }
    ```
   
Laravel 5
=========

Add the service provider and facade in your config/app.php

Service Provider

    kemalevren\Geth\Laravel5\GethPhpServiceProvider::class

Facade

    'JsonRpc'            => kemalevren\Geth\Laravel5\Facades\JsonRpc::class,
    
Laravel 5 Usage
===============
        ```php
        JsonRpc::setOptions([
                // Geth JSON-RPC version
                'version' => '2.0',
                // Host part of address
                'host' => '127.0.0.1',
                // Port part of address
                'port' => 8545,
                // Return results as associative arrays instead of objects
                'assoc' => true,
        ]);
        
        $version = JsonRpc::web3_getVersion();
            
        $accounts = JsonRpc::eth_accounts();
        foreach($accounts as $account) {
            echo $account, ': ', JsonRpc::eth_getBalance($account, 'latest'), PHP_EOL;
        }
        ```