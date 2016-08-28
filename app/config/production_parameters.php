<?php

    $container->setParameter('database_driver', 'pdo_mysql');
    $container->setParameter('database_host', getenv('MYSQL_HOST'));
    $container->setParameter('database_port', 3306);
    $container->setParameter('database_name', getenv('MYSQL_NAME'));
    $container->setParameter('database_user', getenv('MYSQL_USERNAME'));
    $container->setParameter('database_password', getenv('MYSQL_PASSWORD'));
    $container->setParameter('secret', getenv('SECRET'));
    $container->setParameter('locale', 'es');
    $container->setParameter('api_email_key',getenv('API_EMAIL'));
    $container->setParameter('mailer_transport', gmail);
    $container->setParameter('mailer_host', null);
    $container->setParameter('mailer_user', getenv('EMAIL_USER'));
    $container->setParameter('mailer_password', getenv('EMAIL_PASSWORD'));
