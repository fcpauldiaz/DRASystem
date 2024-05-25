<?php

$databaseHost = getenv('MYSQL_HOST');
$databasePort = getenv('MYSQL_PORT');
$databaseName = getenv('MYSQL_NAME');
$databaseUser = getenv('MYSQL_USERNAME');
$databasePassword = getenv('MYSQL_PASSWORD');

error_log("Database Hosted: $databaseHost");
error_log("Database Port: $databasePort");
error_log("Database Name: $databaseName");
error_log("Database User: $databaseUser");

$container->setParameter('database_driver', 'pdo_mysql');
$container->setParameter('database_host', $databaseHost);
$container->setParameter('database_port', $databasePort);
$container->setParameter('database_name', $databaseName);
$container->setParameter('database_user', $databaseUser);
$container->setParameter('database_password', $databasePassword);
$container->setParameter('secret', getenv('SECRET'));
$container->setParameter('locale', 'es');
$container->setParameter('api_email_key', getenv('API_EMAIL'));
$container->setParameter('api_email_domain', getenv('API_EMAIL_DOMAIN'));
$container->setParameter('mailer_transport', 'smtp');
$container->setParameter('mailer_host', null);
$container->setParameter('mailer_user', getenv('EMAIL_USER'));
$container->setParameter('mailer_password', getenv('EMAIL_PASSWORD'));
