<?php
    $container->setParameter('aws_key',getenv('AWS_ACCESS_KEY_ID'));
    $container->setParameter('aws_secret_key',getenv('AWS_SECRET_ACCESS_KEY'));
