<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

interface ClassPersister
{
    public function save(string $directory, string $className, string $content): void;

    public function moveClass(string $fromDir, string $toDir, string $className): void;
}
