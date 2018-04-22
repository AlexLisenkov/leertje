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

    public function options(...$options): string
    {
        echo "Please select an option\n";
        foreach ($options as $i => $option) {
            echo "[{$i}] {$option}\n";
        }
        echo "\n>> ";
        $handle = fopen ("php://stdin","r");
        $index = fgets($handle);

        try{
            $option = $options[(int)$index];
        } catch (\Exception $e){
            echo "{$index} is not valid";
            return $this->options(...$options);
        }

        return $option;
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