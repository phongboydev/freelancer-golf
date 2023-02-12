<?php

namespace InApps\IAModules\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class CreateModule extends Command
{
    protected $signature = "ia-modules:make {module_name} {root_folder_name?}";
    protected $description = 'Create new module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $status = false;

        $fileSystem = new Filesystem();
        $variable_name = $module_name = $this->argument('module_name');
        $root_folder_name = $this->argument('root_folder_name');
        $model_name = $this->convertStringToCamelCase($module_name, true);

        $this->info('Preparing...');
        //Start create package files
        $modules_path = base_path() . '/modules';
        $this->makeDir($modules_path);
        $core_module_path = base_path('vendor/inapps/inapps-modules/src/CoreModule');
        $new_module_path = $modules_path . '/' . (!empty($root_folder_name) ? $root_folder_name . '/' : '') . $model_name;

        //Clone example package folder to new package folder
        $this->info('Creating module ' . $model_name . '...');
        $create_module_folder_status = $this->cloneCoreModuleToNewModule($core_module_path, $new_module_path);
        if(!$create_module_folder_status) {
            return $status;
        }

        $all_files = $fileSystem->allFiles($new_module_path);

        //Start change new package File content
        foreach ($all_files as $key => $file) {
            // Rename file
            $new_file_path = $this->renameFile($file, $variable_name, $model_name);
            // Replace content file
            $status = $this->replaceContentFile($new_file_path, $variable_name, $model_name);
        }
        //create migrate file
        //$status = $this->runCommandCreateMigration($module_name, $new_module_path);
        return $status;
    }

    /**
     * Clone example package to new package
     * @param $core_module_path
     * @param $new_module_path
     * @return bool [boolean]                     [status]
     */
    private function cloneCoreModuleToNewModule($core_module_path, $new_module_path)
    {
        $fileSystem = new Filesystem();
        $status = true;

        // Check if core module exists
        if (!$fileSystem->exists($core_module_path)) {
            $this->info('Core module does not exist');
            $status = false;
        }

        // Check if new module already exists
        if ($fileSystem->exists($new_module_path)) {
            $this->info('Module already exists.');
            $status = false;
        }
        if ($status) {
            // Clone example package folder to new package folder
            $status = $fileSystem->copyDirectory($core_module_path, $new_module_path);
            if (!$status) {
                $this->info('Something goes wrong, can not create module');
            }
        }
        return $status;
    }

    /**
     * rename file
     * @param $file
     * @param $variable_name
     * @param $model_name
     * @return string
     */
    private function renameFile($file, $variable_name, $model_name)
    {
        $file_system = new Filesystem();

        $file_name = $file->getFilename();
        $file_path_name = $file->getPathname();
        $file_path = $file->getPath();

        //rename variableName
        $new_file_name = preg_replace(['/iacore/', '/IACore/'], [$variable_name, $model_name], $file_name);

        $new_file_path = $file_path . DIRECTORY_SEPARATOR . $new_file_name;

        $rename = $file_system->move($file_path_name, $new_file_path);
        if ($rename) {
            //$this->info('Renamed ' . $file_name . ' to ' . $new_file_name);
            return $new_file_path;
        }
//        $this->info('Cannot rename ' . $file_name . ' to ' . $new_file_name);
        return $file_path_name;
    }

    /**
     * replace file content
     * @param $filePath
     * @param $variable_name
     * @param $model_name
     * @return bool|int
     * @throws FileNotFoundException
     */
    private function replaceContentFile($filePath, $variable_name, $model_name)
    {
        $fileSystem = new Filesystem();
        $contents = $fileSystem->get($filePath);
        $newContents = preg_replace(['/iacore/', '/IACore/'], [$variable_name, $model_name], $contents);
        $status = $fileSystem->put($filePath, $newContents);
//        $this->info('Changed Content file ' . $fileSystem->name($filePath));
        return $status;
    }

    /**
     * create migrate file
     * @param $packageName
     * @param $packagePath
     * @return bool
     */
    private function runCommandCreateMigration($packageName, $packagePath)
    {
        $command = ['php artisan make:migration create_' . $packageName . 's_table --path="core/' . $packageName . '/database/migrations"'];

        $process = new Process($command);
        $process->setTimeout(null);

        $this->info('Run command "' . json_encode($command) . '"');

        $process->run();
        if ($process->isSuccessful()) {
            return true;
        } else {
            $this->info($process->getOutput());
            return false;
        }
    }

    /**
     * convert string to camecase
     * @param  [string]  $string                   [string need to convert]
     * @param boolean $capitalizeFirstCharacter
     * @return [string]                            [string after convert]
     */
    private function convertStringToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     * @param $path
     * @param string $mode
     * @return bool
     */
    public function makeDir($path, $mode = '0777')
    {
        if (!is_dir($path)) {
            $old = umask(0);
            mkdir($path,0777, TRUE);
            umask($old);
        }
        return true;
    }
}