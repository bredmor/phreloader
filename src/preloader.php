<?php

class Preloader
{
    private string $base_path;
    private array $map;
    private array $ignored;

    public function __construct(string $path, string $composer_path = null)
    {
        $map = $composer_path ? rtrim($composer_path, "/") . '/composer/autoload_classmap.php' : __DIR__ . "../composer/autoload_classmap.php";
        $this->loadIgnoreList();
        $this->base_path = $path;
        $this->map = require_once($map);
    }

    public function go(): void {
        $this->readDir($this->base_path);
    }

    private function readDir($path): void {
        $dir = new DirectoryIterator($path);
        foreach($dir as $node) {
            if($this->isIgnored($node->getPath())) continue;

            if($node->isDir()) {
                if($node->isDot()) continue;

                $this->readDir($node->getPath());
            } else {
                $this->loadFile($node->getPath());
            }
        }
    }

    private function loadFile(string $path): void
    {
        $class = array_search($path, $this->map);

        if($class) require_once($path);
    }

    private function loadIgnoreList(): void {
        $this->ignored = file($this->base_path . '.nopreload', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    private function isIgnored(string $path): bool {
        $filedef = str_replace($this->base_path, '', $path);
        if(in_array(ltrim($filedef, '/'), $this->ignored)) return true;

        return false;
    }

}

(new Preloader('/src'))->go();