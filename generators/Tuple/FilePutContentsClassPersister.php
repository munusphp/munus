<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

class FilePutContentsClassPersister implements ClassPersister
{
    public function __construct(private string $sourcePath)
    {
    }

    public function save(string $directory, string $className, string $content): void
    {
        $filePath = $this->sourcePath.$directory.'/'.$className.'.php';
        file_put_contents($filePath, $content);
    }

    public function moveClass(string $fromDir, string $toDir, string $className): void
    {
        copy(sprintf('%s/%s.php', $fromDir, $className), sprintf('%s/%s.php', $toDir, $className));
    }
}
