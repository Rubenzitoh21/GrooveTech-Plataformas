<?php
return [
    [
        'id' => 1,
        'username' => 'admin',
        'auth_key' => 'test100key',
        'password_hash' => Yii::$app->security->generatePasswordHash('admin1234'),
        'email' => 'admin@example.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
    ],
];
