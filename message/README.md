Message service
=======

...

# Endpoints

| Path                    | Method | Scheme | Grant |
| ----------------------  | ------ | ------ | ----- |
| /api/............       | POST   | ANY    | ALL   |

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

RabitMQ open [https://localhost:15672 ](https://localhost:15672) and use login and password:

```
    user: rabbitmq
    password: rabbitmq
```

## Tests

* Postman
* Functional(PHPUnit)

## Useful links

[PHPMailer](https://github.com/PHPMailer/PHPMailer)

[Sending sms reminders with symfony](https://www.twilio.com/blog/sending-sms-reminders-with-symfony-php-framework)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)
