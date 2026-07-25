<?php
$dir = new RecursiveDirectoryIterator('C:/laragon/www/surabayahype/resources/views/');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (preg_match('/@section\(\'sidebar\'\).*?@endsection/s', $content)) {
            $newContent = preg_replace('/@section\(\'sidebar\'\).*?@endsection\s*/s', '', $content);
            file_put_contents($file->getPathname(), $newContent);
            echo 'Cleaned ' . $file->getFilename() . "\n";
        }
    }
}
echo "Done.\n";
