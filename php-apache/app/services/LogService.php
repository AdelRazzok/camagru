<?php

namespace services;

class LogService
{
    public const LEVEL_INFO = 'INFO';
    public const LEVEL_WARNING = 'WARNING';
    public const LEVEL_ERROR = 'ERROR';

    private string $logDir;

    public function __construct()
    {
        $this->logDir = dirname(__DIR__) . '/logs/';

        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    public function log(string $level, string $message, string $channel): void
    {
        $logFile = $this->logDir . $channel . '.log';

        $date = date('Y-m-d H:i:s');
        $formattedMessage = "[{$date}] [{$level}] {$message}" . PHP_EOL;

        file_put_contents($logFile, $formattedMessage, FILE_APPEND | LOCK_EX);
    }

    public function info(string $message, string $channel = 'app'): void
    {
        $this->log(self::LEVEL_INFO, $message, $channel);
    }

    public function warning(string $message, string $channel = 'app'): void
    {
        $this->log(self::LEVEL_WARNING, $message, $channel);
    }

    public function error(string $message, string $channel = 'app'): void
    {
        $this->log(self::LEVEL_ERROR, $message, $channel);
    }
}
