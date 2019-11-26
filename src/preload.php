<?php

class Preloader
{
    private $path;
    private $map = [];

    public function __construct(string $path, string $composer_path = null)
    {
        $map = $composer_path ? rtrim($composer_path, "/") . '/composer/autoload_classmap.php' : __DIR__ . "/vendor/composer/autoload_classmap.php";
        $this->path = $path;
        $this->map = require_once($map);
    }

    public function go() {
        $this->readDir($this->path);
    }

    private function readDir($path) {
        $dir = new DirectoryIterator($path);
        foreach($dir as $node) {
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

}

(new Preloader('/src'))->go();