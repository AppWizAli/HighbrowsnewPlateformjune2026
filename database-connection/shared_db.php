<?php

if (!function_exists('highbrowsGetDbProfiles')) {
    function highbrowsGetDbProfiles(): array
    {
        return [
            'academic' => [
                'host' => getenv('HIGHBROWS_ACADEMIC_DB_HOST') ?: 'localhost',
                'dbname' => getenv('HIGHBROWS_ACADEMIC_DB_NAME') ?: 'u379508397_Acadamic_db',
                'user' => getenv('HIGHBROWS_ACADEMIC_DB_USER') ?: 'u379508397_Highbrowsian12',
                'pass' => getenv('HIGHBROWS_ACADEMIC_DB_PASS') ?: 'HighBrowsian92@#',
                'charset' => 'utf8mb4',
            ],
            'pafsoftware' => [
                'host' => getenv('HIGHBROWS_PAF_DB_HOST') ?: 'localhost',
                'dbname' => getenv('HIGHBROWS_PAF_DB_NAME') ?: 'u379508397_pafdb',
                'user' => getenv('HIGHBROWS_PAF_DB_USER') ?: 'u379508397_pafuser',
                'pass' => getenv('HIGHBROWS_PAF_DB_PASS') ?: 'Officer7837@.',
                'charset' => 'utf8mb4',
            ],
        ];
    }
}

if (!function_exists('highbrowsGetDbConfig')) {
    function highbrowsGetDbConfig(string $profile): array
    {
        $profiles = highbrowsGetDbProfiles();

        if (!isset($profiles[$profile])) {
            die('Unknown database profile: ' . htmlspecialchars($profile, ENT_QUOTES, 'UTF-8'));
        }

        return $profiles[$profile];
    }
}

if (!function_exists('highbrowsGetMysqliConnection')) {
    function highbrowsGetMysqliConnection(string $profile = 'academic'): mysqli
    {
        static $connections = [];

        if (isset($connections[$profile]) && @$connections[$profile]->ping()) {
            return $connections[$profile];
        }

        $config = highbrowsGetDbConfig($profile);

        $connection = new mysqli(
            $config['host'],
            $config['user'],
            $config['pass'],
            $config['dbname']
        );

        if ($connection->connect_error) {
            die('Database connection failed: ' . $connection->connect_error);
        }

        $connection->set_charset($config['charset']);
        $connections[$profile] = $connection;

        return $connection;
    }
}

if (!function_exists('highbrowsGetPdoConnection')) {
    function highbrowsGetPdoConnection(string $profile = 'academic'): PDO
    {
        static $connections = [];

        if (isset($connections[$profile])) {
            return $connections[$profile];
        }

        $config = highbrowsGetDbConfig($profile);

        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['charset']
            );

            $connection = new PDO($dsn, $config['user'], $config['pass']);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $connections[$profile] = $connection;

            return $connection;
        } catch (PDOException $exception) {
            die('Database connection failed: ' . $exception->getMessage());
        }
    }
}
