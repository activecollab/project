<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

require 'vendor/autoload.php';

$filesystem = new \ActiveCollab\FileSystem\FileSystem(new \ActiveCollab\FileSystem\Adapter\LocalAdapter(__DIR__));

$rename_utility = new class ($filesystem)
{
    /**
     * @var \ActiveCollab\FileSystem\FileSystemInterface
     */
    private $filesystem;

    /**
     * @param \ActiveCollab\FileSystem\FileSystemInterface $filesystem
     */
    public function __construct(\ActiveCollab\FileSystem\FileSystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param  string $path
     * @return string
     */
    public function getProjectName($path)
    {
        return ucfirst(basename($path));
    }

    /**
     * @param string $path
     * @param string $project_name
     */
    public function fixNamespaceInDir($path, $project_name)
    {
        if (empty($project_name)) {
            throw new RuntimeException('Project name is required');
        }

        foreach ($this->filesystem->subdirs($path) as $subdir) {
            $this->fixNamespaceInDir($subdir, $project_name);
        }

        foreach ($this->filesystem->files($path) as $file) {
            if ($this->filesystem->isFile($file) && pathinfo($this->filesystem->getFullPath($file), PATHINFO_EXTENSION) == 'php') {
                print $file . "\n";
            } else {
                print "Not php file $file\n";
            }

            $this->filesystem->replaceInFile($file, [
                'ActiveCollab\\App\\' => 'ActiveCollab\\' . $project_name . '\\',
                'ActiveCollab\\\\App\\\\' => 'ActiveCollab\\\\' . $project_name . '\\\\',
            ]);
        }
    }
 };

$project_name = $rename_utility->getProjectName(__DIR__);
$short_project_name = str_replace([' ', '_'], ['-', '-'], \Doctrine\Common\Inflector\Inflector::tableize($project_name));

print "Project name is: $project_name\n";
print "Namespace is ActiveCollab\\$project_name\n";

$filesystem->replaceInFile('composer.json', [
    '"name": "activecollab/project"' => '"name": "my-company/' . $short_project_name . '"',
]);

$filesystem->replaceInFile('app/bin/app.php', [
    '$application = new Application(\'App\'' => '$application = new Application(\'' . $project_name . '\'',
]);



$rename_utility->fixNamespaceInDir('app', $project_name);
$rename_utility->fixNamespaceInDir('test', $project_name);

$filesystem->replaceInFile('phpunit.xml', ['<testsuite name="App">' => '<testsuite name="' . htmlspecialchars($project_name) . '">']);
$filesystem->renameFile('app/bin/app.php', $short_project_name . '.php');
