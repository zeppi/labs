Zeppi Labs
============

Repository about tests and learning exercices

## 20.12.2013

### Zeppi-AS2

Using Net-AS2, http://search.cpan.org/~swong/Net-AS2-0.01/lib/Net/AS2.pm

For this lab, we needs two certificate (client, server). Is for the first level encryption

    `openssl genrsa -out client.key 1024`
  
    `openssl req -new -x509 -days 3650 -key client.key -out client.cert`
 
    `openssl genrsa -out server.key 1024`
  
    `openssl -new -x509 -days 3650 -key server.key -out server.cert`
 
## 26.07.2013

### Zeppi-snippets

Snippets and templates currently used

## 15.07.2013

### Zeppi-biorythme

Create a basic Facebook application. Normally I do an hello world, but is not very funny for facebook. I was looking for original idea and suddenly, I remembered an exercise in Excel programming was to calculate the Biorithme.

By the way, I would test Silex micro framework and RedBeanPhp micro orm. Micro tools for micro project :-)

###  Installation

Use composer,  Installation instruction are here http://getcomposer.org/download/ and run `php composer.phar install`


## 13.07.2013

  - Setup github
