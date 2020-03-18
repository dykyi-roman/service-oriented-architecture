Auth service
=======

![image](auth.jpeg)

This part of the tutorials covers how to perform Authentication and Authorization between microservices.

Authentication: Refers to verify who you are, so you need to use username and password for authentication.

Authorization: Refers to what you can do, for example access, edit or delete permissions to some documents, and this happens after verification passes.

JWT: (Json Web Token) is an open standard (RFC 7519) that defines the Token format, defines the Token content, encrypts it, and provides lib for various languages.

## Endpoints

| Path                    | Method | Scheme | Grant |
| ----------------------  | ------ | ------ | ----- |
| /api/user/registeration | POST   | ANY    | ALL   |
| /api/user/login         | GET    | ANY    | ALL   |
| /api/user/{id}          | ANY    | ANY    | ALL   |
| /api/token/refresh      | ANY    | ANY    | ALL   |

## Requirements

* PHP 7.4
* Symfony 5.0

## Tests

* Postman
* Functional(PHPUnit)

## Useful links

[Microservices Authentication and Authorization Solutions](https://medium.com/tech-tajawal/microservice-authentication-and-authorization-solutions-e0e5e74b248a)

[Securing Microservices: The API gateway, authentication and authorization](https://sdtimes.com/apis/securing-microservices-the-api-gateway-authentication-and-authorization/)

[Authentication and Authorization in Microservices](https://dzone.com/articles/authentication-and-authorization-in-microservices)

[Token-Based Authentication](https://gist.github.com/zmts/802dc9c3510d79fd40f9dc38a12bccfc)

[Lexik JWT Authentication](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md)

[JWT Refresh Token](https://github.com/markitosgv/JWTRefreshTokenBundle)

[JWT Debugger](https://jwt.io/)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)
