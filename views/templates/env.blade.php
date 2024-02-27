# Standard Yaml format
---
debug: false
baseUrl: "{{ $proto }}{{ $uri }}"
databaseConfiguration:
  driver: mysql
  host: {{ $dbhost ?? 'localhost' }}
  port: 3306
  database: {{ $dbname }}
  username: {{ $dbuser }}
  password: {{ $dbpassword }}
  prefix: {{ $dbprefix }}
adminUser:
  username: "{{ $login ?? $user }}"
  password: '{{ $password }}'
  email: "{{ $email }}"
settings:
  forum_description: "A random forum for a random reason"