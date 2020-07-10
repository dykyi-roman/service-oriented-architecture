Message service
=======

![image](docs/message.png)

User notification playing a very important role in modern application. Basically exist are two types of notifications:

* trigger - which are triggered based on action of event.

* bulk - which are triggered after user subscribed for some event.

## Consumer

On production, there are a few important things to think about:

* Use Supervisor to keep your worker(s) running

* Don't Let Workers Run Forever

* Restart Workers on Deploy

To run a new consumer for message processing - use the command with parameter. Where parameter - this is a queue name. 

```
    bin/console messenger:consume message sent notsent
```

## Cache

Cache usage gives us an available template without load from DB. Thanks for that we save time for loading and transform.

## Priority

For us each type of message we could have a next priority queue:
 
 * high -  use for message that have a limited time fife (password restore, confirm registration...)
 
 * medium - use for event or action notification
  
 * low - use for subscribers notification 

## Storage sent message

When there is no more space in the collection ["sent"], the oldest documents are deleted and new data is added to the end.
More detail read [here](https://docs.mongodb.com/manual/core/capped-collections/)

When storage a sent messages becomes a bottleneck in your architecture, you could to replace repository interface to queue interface 
and push the data in the next queue ["sent", "notSent"]. I following YAGNI principle and do not stay do it this improvement now.

## Queue message:

| Params   |  Example                                              | Description                                       |
| ---------| ----------------------------------------------------- | ------------------------------------------------- |
| user_id  | 74b757bb-79de-44cf-88f4-08ce874bd374                  | user who needs to send an notification            |
| to       | {"email":"mr.dukuy@gmail.com","phone":"+380938982443"}| sent type for recipient and contact data          |                        
| template | {"name":"welcome","lang":"en","variables":["Roman"]}  | sent template with require params                 |                        

## Admin endpoints

| Path                          | Method | Scheme | Grant |
| ----------------------------  | ------ | ------ | ----- |
| /api/admin/templates           | POST   | ANY    | ALL   |
| /api/admin/templates           | GET    | ANY    | ALL   |
| /api/admin/templates/{id}      | GET    | ANY    | ALL   |
| /api/admin/templates/{id}      | PUT    | ANY    | ALL   |
| /api/admin/templates/{id}      | DELETE | ANY    | ALL   |

## Configuration

Add docker machine IP to /etc/hosts:  

```
127.0.0.1 admin.test
```

## Requirements

* PHP 7.4
* Symfony 5.0
* RabbitMQ 3.7
* MongoDB 4.2

## Clients

MongoDB open [https://localhost:8081](https://localhost:8081) and use login and password:

```
    user: root
    password: secret
```

RabitMQ open [https://localhost:15672](https://localhost:15672) and use login and password:

```
    user: rabbitmq
    password: rabbitmq
```

## Clean code

```
make pre-commit
```

## Resources

[Email Sending Architecture Using Messaging Queue](https://medium.com/naukri-engineering/email-sending-architecture-using-messaging-queue-314a18f8595c)

[AMQP 0-9-1 Model Explained](https://www.rabbitmq.com/tutorials/amqp-concepts.html#exchange-fanout)

[PHPMailer](https://github.com/PHPMailer/PHPMailer)

[Sending sms reminders with symfony](https://www.twilio.com/blog/sending-sms-reminders-with-symfony-php-framework)

[Symfony messenger](https://symfony.com/doc/current/messenger.html)

[Symfony notifier](https://symfony.com/doc/current/notifier.html)

[NATS](https://docs.nats.io/nats-concepts/intro)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)
