Service oriented architecture
=======

![image](base.png)

Microservices architecture has been gaining a lot of ground as the preferred architecture for implementing solutions, as it provides benefits like scalability, logical and physical separation, small teams managing a part of the functionality, flexibility in technology, etc. 
But since microservices are distributed the complexity of managing them increases.
One of the key challenges is how to implement authentication and authorization in microservices so that we can manage security and access control.

Avoid extensive APIs. If the microservice is getting too complicated, then you are giving the service too much functionality.
The goal is to have a series of services that can be strung together to meet the needs of future business problems.

I clearly understand that this is a not silver bullet. Clean Architecture by Robert C. Martin has a good describe base problems.
Here I want to added some plus from me:

* Small and single in purpose

* Communicate via technology agnostic protocols

* Support continuous integration

* Independently deployable.

# Documentation

[Auth service](auth/README.md)

[Message service](message/README.md)

[Admin panel service](adminpanel/README.md)

[Sentry](sentry/README.md)

[Graylog](graylog/README.md)

<a href="https://github.com/dykyi-roman/service-oriented-architecture/tree/master#newman">Newman</a>

# Auth service

![image](auth/docs/main.png)

In this strategy, a microservice is created for the purpose of authentication. Authentication primarily involves issuing and verifying tokens. 
JWT (JSON Web Tokens) can be used to verify tokens without having to hit a database or other persistent storage. 
This means each service can verify requests on their own. Token issuing is done in the auth service, while verification is handled in every service where it’s required. 
A client library is usually used to share this verification functionality with all the services that need to perform authentication. 

When you take a JWT from the authorization server you can use it for communicating with another service, putting token inside a request header. Or you can use the SSL certificate between microservices and left the problem of security for DevOps.

# Message service

![image](message/docs/message.png)

# Admin Panel service

![image](adminpanel/docs/adminflow2.png)

Exist a lot of ways how you can organize architecture to work with the admin panel.
I have experience working with four. Taking into consideration the first of the SOLID principles (Single Responsibility Principle) on the modules level I would take for realization approach №2.
On the picture use a REST for data transfer between a service but this is a detail, request/response can easily be implemented by using messaging. 

# Verification service 
   
...

# Application

...

# Performance and monitoring

## Sentry

Sentry provides self-hosted and cloud-based error monitoring that helps all software teams discover, triage, and prioritize errors in real-time.

## Graylog

Graylog is a leading centralized log management solution built to open standards for capturing, storing, and enabling real-time analysis of terabytes of machine data.

## Newman

<a href="https://github.com/postmanlabs/newman" target="_blank">Newman</a> is a command-line collection runner for
<a href="https://getpostman.com" target="_blank">Postman</a>. It allows you to effortlessly run and test a
<a href="https://www.getpostman.com/docs/collections" target="_blank">Postman Collections<a/> directly from the
command-line. It is built with extensibility in mind so that you can easily integrate it with your continuous integration servers and build systems.

Run a collection using the newman image:

```
docker-compose run postman_checks
```

## Ressources:

[Messaging Patterns for Event-Driven Microservices](https://solace.com/blog/messaging-patterns-for-event-driven-microservices/)

[REST vs Messaging for Microservices – Which One is Best?](https://solace.com/blog/experience-awesomeness-event-driven-microservices/)

[Create an SSL Certificate on Nginx](https://www.digitalocean.com/community/tutorials/how-to-create-an-ssl-certificate-on-nginx-for-ubuntu-14-04)

[json-schema](https://json-schema.org/understanding-json-schema/reference/object.html)

[newman](https://github.com/postmanlabs/newman)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)

