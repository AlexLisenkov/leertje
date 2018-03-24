# leertje
Back to basics

Uitvoeren doe je met: `php run.php`

Ik heb het alleen getest met php 7.2
#Installatiehandleiding
Verander de gegevens in `Database/Connection.php` naar jouw gegevens.

Bijvoorbeeld:
```php
    const DATABASE_NAME = 'leertje';
    const DATABASE_CONNECTION = '127.0.0.1';
    const DATABASE_USER = 'homestead';
    const DATABASE_PASSWORD = 'secret';
    const DATABASE_PORT = '3306';
```

Voer het sql bestandje uit op je database, het bestandje maakt ook een database genaamt `leerje` aan.
