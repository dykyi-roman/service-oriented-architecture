Service oriented architecture
=======
Microservices architecture has been gaining a lot of ground as the preferred architecture for implementing solutions, as it provides benefits like scalability, logical and physical separation, small teams managing a part of the functionality, flexibility in technology, etc. 
But since microservices are distributed the complexity of managing them increases.
One of the key challenges is how to implement authentication and authorization in microservices so that we can manage security and access control.

#Documentation

[Auth service](auth/README.md)

[Message service](message/README.md)

[Admin panel service](adminpanel/README.md)

## Auth service

In this strategy, a microservice is created for the purpose of authentication. Authentication primarily involves issuing and verifying tokens. 
JWT (JSON Web Tokens) can be used to verify tokens without having to hit a database or other persistent storage. 
This means each service can verify requests on their own. Token issuing is done in the auth service, while verification is handled in every service where it’s required. 
A client library is usually used to share this verification functionality with all the services that need to perform authentication. 

![image](base.png)

When you take a JWT from the authorization server you can use it for communicating with another service, putting token inside a request header. Or you can use the SSL certificate between microservices and left the problem of security for DevOps.

## Message service

...

## Admin Panel service

![image](adminpanel/docs/adminflow2.png)

Exist a lot of ways how you can organize architecture to work with the admin panel.
I have experience working with four. Taking into consideration the first of the SOLID principles (Single Responsibility Principle) on the modules level I would take for realization approach №2.

## Verification service 
   
...

## Application

...   

## Performance Monitoring

...

#Useful links:

[Create an SSL Certificate on Nginx](https://www.digitalocean.com/community/tutorials/how-to-create-an-ssl-certificate-on-nginx-for-ubuntu-14-04)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)

