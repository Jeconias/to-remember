<?php

namespace StreamData\infrastructure\persistence;

use Error;

class InDisk
{
    private static ?InDisk $instance = null;
    private $fileDir = null;
    private $fileRef = null;

    static function getInstance(?string $fileDir = '/app/.db.cache'): InDisk
    {
        if (self::$instance) return self::$instance;

        self::$instance = new InDisk($fileDir);

        return self::$instance;
    }

    private function __construct(?string $fileDir)
    {
        $this->fileDir = $fileDir ?? $this->fileDir;
        $this->openFile();
        if (!$this->fileRef) throw new Error("Error on open file");
    }

    private function openFile(string $mode = "a+")
    {
        if ($this->fileRef) return $this->fileRef;
        $this->fileRef = fopen($this->fileDir, $mode);
        return $this->fileRef;
    }

    private function closeFile()
    {
        fclose($this->fileRef);
        $this->fileRef = null;
    }

    function set(string $key, mixed $element)
    {
        $this->remove($key);

        $contents = $this->openFile();

        $data = sprintf("%s|%s\n", $key, json_encode($element));
        $result = fwrite($contents, $data, strlen($data));

        $this->closeFile();

        if (!$result) throw new Error('Error on write data');
    }

    function get(string $key)
    {
        $contents = $this->openFile();

        while (($line = fgets($contents)) !== false) {
            $lineContent = $this->getLineContent($line);

            if (strlen($line ?? "") !== 0 && $lineContent['key'] === $key) {
                $this->closeFile();
                return json_decode($lineContent['content'], true);
            };
        }

        $this->closeFile();

        return null;
    }

    function remove(string $key)
    {
        $exists = $this->get($key);
        if (!$exists) return;

        $lines = "";
        $contents = $this->openFile();

        while (($line = fgets($contents)) !== false) {
            $lineContent = $this->getLineContent($line);

            if (strlen($line ?? "") !== 0 && $lineContent['key'] !== $key) {
                $lines .= $line;
            };
        }

        $this->closeFile();

        $newFileRef = $this->openFile("w+");

        if (strlen($lines) === 0) {
            $this->closeFile();
            return true;
        }

        $result = fwrite($newFileRef, $lines, strlen($lines));

        if (!$result) throw new Error('Error on write data');

        $this->closeFile();

        return true;
    }

    private function getLineContent(string $line)
    {
        $parts = explode("|", $line, 2);
        if (count($parts) !== 2) return ['key' => '', 'content' => ''];

        return ['key' => $parts[0], 'content' => $parts[1]];
    }
}
