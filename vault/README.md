Vault
=======

Vault is one of Hashicorp’s awesome services, which enables you to centrally store, access and distribute dynamic secrets such as tokens, passwords, certificates and encryption keys.

In PHP applications there's a common pattern to keep configuration values and access details in a .env file that resides in a place where the PHP application can reach it.
Its mean that we need to update variable - `$_ENV['VAULT_MYSQL_PASS']`. The best way to do this in `index.php` after get all variable from `.env` file. 

This does not protect you from a huck your server and `echo $_ENV`. You can additional encrypt/decrypt this keys, but this is not also protect your from a situation someone will know your key.

The above methods save us from Developers eyes. But, DevOps know Vault token and can use it to read secrets. 
For resolving this problem we need to set a time life for token and auto generate it each 10-minutes. And a very useful tool is audit, they log all information who and when use read or write operation.

Using a root token in Vault is just as bad as logging in to root in Ubuntu, so you should use a policy.

# Installation

Start all services with exposed data directories:

```
docker-compose up
```

#### Server configuration

Vault [open](http://127.0.0.1:8200) and use your keys and token to enter.

* create an administrator
* choose engine for secrets storage 
* create a new policies
* write a secrets
* generate token for read 

#### Application configuration

1. Add next variables in your `.env` file:

```
  VAULT_HOST=https://vault:8200
  VAULT_TOKEN=cb347ae0-9eb4-85d1-c556-df43e82be4b0
```

2. Use Vault Client to read secrets:

Example: 
```
   curl \
      -H "X-Vault-Token:cb347ae0-9eb4-85d1-c556-df43e82be4b0" \
      https://vault:8200/v2/secret/{service}/{env}/{variable}
```

Client Example:
```
    $secretClient = new VaultClient(new Client(), env('VAULT_HOST'), env('VAULT_TOKEN'));
    $secrets = $secretClient->read(sprintf('%s/%s', env('APP_ENV'), env('APP_NAME')));
    foreach ($secrets as $key => $value) {
        if (array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }
```

## Resources:

[Versioned Key/Value Secrets Engine](https://learn.hashicorp.com/vault/secrets-management/sm-versioned-kv)

[Managing application secrets with Hashicorp Vault](http://www.inanzzz.com/index.php/post/3gmy/managing-application-secrets-with-hashicorp-vault)    

[Setup Hashicorp Vault Server on Docker](https://blog.ruanbekker.com/blog/2019/05/06/setup-hashicorp-vault-server-on-docker-and-cli-guide/)
        
[Управление секретами при помощи HashiCorp Vault](https://habr.com/ru/company/oleg-bunin/blog/438740/)        
        
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)

