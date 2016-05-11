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
            $this->filesystem->replaceInFile($file, [
                'ActiveCollab\\App\\' => 'ActiveCollab\\' . $project_name . '\\',
                'ActiveCollab\\\\App\\\\' => 'ActiveCollab\\\\' . $project_name . '\\\\',
            ]);
        }
    }
 };

$project_name = $rename_utility->getProjectName(__DIR__);

if (!ctype_alnum($project_name)) {
    print "Project name can be alphanum only\n";
    exit(1);
}

$underscore_project_name = \Doctrine\Common\Inflector\Inflector::tableize($project_name);
$short_project_name = str_replace([' ', '_'], ['-', '-'], $underscore_project_name);
$env_variable_prefix = strtoupper($underscore_project_name) . '_';

print "Project name is: $project_name\n";
print "Namespace is ActiveCollab\\$project_name\n";

print "Updating composer.json\n";
$filesystem->replaceInFile('composer.json', [
    '"name": "activecollab/project"' => '"name": "my-company/' . $short_project_name . '"',
    '"keywords": ["app"]' => '"keywords": ["' . $project_name . '"]',
]);

$filesystem->replaceInFile('app/bin/app.php', [
    '$application = new Application(\'App\'' => '$application = new Application(\'' . $project_name . '\'',
]);

print "Prepare environment variables and configuration options\n";
$filesystem->replaceInFile('config/.env.sample', [
    'APP_' => $env_variable_prefix,
    'APP_MYSQL_NAME="app"' => 'APP_MYSQL_NAME="' . $underscore_project_name . '"',
]);
$filesystem->replaceInFile('app/config.php', ['APP_' => $env_variable_prefix]);
$filesystem->replaceInFile('app/dependencies.php', ['APP_' => $env_variable_prefix]);

print "Update class namespaces\n";
$rename_utility->fixNamespaceInDir('app', $project_name);
$rename_utility->fixNamespaceInDir('test', $project_name);

print "Prepare CLI tools\n";
$filesystem->replaceInFile('phpunit.xml', ['<testsuite name="App">' => '<testsuite name="' . htmlspecialchars($project_name) . '">']);
$filesystem->renameFile('app/bin/app.php', $short_project_name . '.php');

print "All set, project has been renamed to $project_name\n";
