Graylog
=======

# Graylog

Graylog is a leading centralized log management solution built to open standards for capturing, storing, and enabling real-time analysis of terabytes of machine data.

# Installation

Start all services with exposed data directories:

```
docker-compose up
```

## Configuration

Graylog [open](http://127.0.0.1:9000) and use login and password:

```
user: admin
password: admin
```

Now you are not sending data to Graylog, so you need to configure an [input](https://docs.graylog.org/en/2.0/pages/getting_started/config_input.html#configure-graylog-input). This will tell Graylog to accept the log messages.
Go back to the Graylog console open in your browser and click System -> Inputs. Then select Syslog UDP and click Launch new input. Fill out the circles with the values in the screen shown below.

```
allow_override_date: true
bind_address: 0.0.0.0
expand_structured_data: false
force_rdns: false
number_worker_threads: 8
override_source: <empty>
port: 12201
recv_buffer_size: 262144
store_full_message: false
```

## Possible problems

you can have a situation when after receiving a message in Graylog your message code will be broken. For fix this

```
x�%�A � ���Z�L��2�bJ�2q�=����wC��)��a�
���r�\+��r̥.1��:�.;5��k5u���v�����C�����

```
The reason is collector_sidecar output into the wrong INPUT format(syslog type).
Create a new type INPUT(GELF) and change collector_sidecar output to the new INPUT,everything is OK!

## Resources:

[Graylog documenttation](https://docs.graylog.org/en/3.2/index.html)

[gelf-php](https://github.com/bzikarsky/gelf-php)

[Messy code in messages](https://community.graylog.org/t/messy-code-in-messages/1437)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)

