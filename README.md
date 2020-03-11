Docker project
=======

## Compose

+ nginx
+ php-fpm
+ postgresql
+ mongodb
+ mysql
+ adminer
+ vault
+ redis
+ elasticsearch

## Install

+ Install docker-compose
+ docker login

## Run

+ docker-compose up -d

The script from the official PHP image from Docker Hub makes it easy to install the required extensions

[docker-php-ext-install](https://github.com/docker-library/php/blob/master/docker-php-ext-install)

## Command

+ `docker images`  - List images
+ `docker ps` - List containers  
+ `docker exec -it <process-hash> bash` - Run a command in a running container
+ `docker commit -m "added comment text" <process-hash> <my-login/repo:tag-name>` - Create a new image from a container’s changes
+ `docker push <my-login/repo:tag-name` - Push an image or a repository to a registry    

Full comansd list you cant find [here](https://docs.docker.com/engine/reference/commandline/) 

## Nginx responds with 403 

To fix this I have to make the mounted directory and its contents publicly readable:
+ `chmod -R o+rX /tmp/webs/my-website`

## Mysql Table '*.*' doesn't exist fix
+ `docker exec -it <container_id> bash -c "mysql_upgrade -uroot -proot"`

## How To Remove Docker Images, Containers, and Volumes

[Link](https://www.digitalocean.com/community/tutorials/how-to-remove-docker-images-containers-and-volumes)

## Error Logs

To look for an error why the container does not start, you need to run it without options `-d`
Example:

+ `docker-compose up` 
+ `sudo cocker logs <container_id>`

And look for an error in the console.

## Bash script for clear docker container and images

Well, Docker rebuilds the layers that have changed. The rest is what was. So better remove all and run again if something not work ;) 

+ Use command `sudo sh clear.sh`

## Image Update 

If you want update your exist image you can install package inside container `docker-php-ext-install <package_name>`

Example:

+ `docker ps`
+ `docker exec -it <process-hash> bash`
+ `docker-php-ext-install pdo_mysql`
+ `docker commit -m "added comment text" <process-hash> <my-login/repo:tag-name>`
+ `docker push <my-login/repo:tag-name>`
+ `docker images`
+ `docker rmi <image_id>`

## Adminer connect to data base

![image](https://github.com/dykyi-roman/docker-project/blob/master/images/adminer.png)
    
## Docker Multiple Websites    
    
1) Usage [nginx-proxy](https://github.com/jwilder/nginx-proxy)
2) Or have all the sites in the one container set?
 + Website 1: (nginx,phpfpm,mysql)
 + Website 2: (nginx,phpfpm,mysql)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)
