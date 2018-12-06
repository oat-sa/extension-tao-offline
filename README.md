#Synchronisation & Encryption
## Synchronisation
The sync process consists in having two actors, one is the ClientServer, which exists on a Virtual Machine and the second one being the CentralServer.

###Setup ClientServer
The fallowing script needs to be run on TAO instance in order to make a client server.

```php
sudo -u www-data php index.php '\oat\taoOffline\scripts\tools\setup\SetupClientServer'
```
    In case taoEncryption it's installed this script it's gonna setup the instance with encryption.
    
 In order to point this instance to a specific server the fallowing command needs to be run
 
 ```php
    sudo -u www-data php index.php '\oat\taoSync\scripts\tool\RegisterHandShakeRootURL' --rootUrl=http://tao-central.dev/
 ```
 
 
###Setup CentralServer
The fallowing script needs to be run on TAO instance in order to make a central server.

```php
sudo -u www-data php index.php 'oat\taoOffline\scripts\tools\setup\SetupCentralServer'
```
     In case taoEncryption it's installed this script it's gonna setup the instance with encryption.

###Types of Syncs available
* Test Center Based on OrganisationId
    * Users:
        * Test Takers
        * Proctor Administrators
        * Proctor
        * Eligibility
        * Deliveries
 * Results   
 * Results Logs


#### Overview Flow
![alt text](docs/sync_flow.png)

#### Sync Users with encryption
Each users has assign to him the application id in order to have access to the delivery content.

![alt text](docs/sync_users.png)

#### Sync Deliveries with encryption
![alt text](docs/sync_delivery.png)

#### Sync Results with encryption
The results chunkSize it's a very important config that needs to be set in advance, by default it's 10.
Each request to the server will include the no of results, the process will stop after all results are sent.

![alt text](docs/sync_results.png)





