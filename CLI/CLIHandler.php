<?php
namespace CLI;

class CLIHandler
{
    public function ask(string $question): string
    {
        echo "{$question}\n>> ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        return trim($line);
    }

    public function say(string $message): void
    {
        echo $message. "\n";
    }

    public function quit(): void
    {
        echo "Quitting...\n";
        exit;
    }
}