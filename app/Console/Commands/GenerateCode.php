<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:code {model} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create code for API';

    protected $model;
    protected $module;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $model = $this->argument('model');
        $module = $this->argument('module');

        $this->model = $model;
        $this->module = $module;

        $this->createModel();
        $this->createMigration();
        $this->createController();
        $this->createModelFilter();
        $this->createRequest();
    }

    protected function createController()
    {
        $path = app_path('Http/Controllers/Api/').$this->module;

        $fileName = $this->model . 'Controller.php';

        $templateData = view('stubs.controller', [
            'modelName' => $this->model,
            'moduleName' => $this->module
        ]);

        $this->createFile($path.'/'.$fileName, $templateData);
        $this->info('API Controller created: '.$fileName);
    }

    protected function createModel()
    {
        $path = app_path('Models/');
        $fileName = $this->model . '.php';

        $templateData = view('stubs.model', [
            'modelName' => $this->model,
        ]);

        $this->createFile($path.'/'.$fileName, $templateData);
        $this->info('Model created: '.$fileName);
    }

    protected function createModelFilter()
    {
        $path = app_path('ModelFilters/');
        $fileName = $this->model . 'Filter.php';

        $templateData = view('stubs.modelFilter', [
            'modelName' => $this->model,
        ]);

        $this->createFile($path.'/'.$fileName, $templateData);
        $this->info('ModelFilter created: '.$fileName);
    }

    protected function createRequest()
    {
        $path = app_path('Http/Requests/').$this->module;

        $fileName = 'Create'. $this->model . 'Request.php';

        $templateData = view('stubs.request', [
            'modelName' => $this->model,
            'moduleName' => $this->module
        ]);

        $this->createFile($path.'/'.$fileName, $templateData);
        $this->info('Request created: '.$fileName);
    }

    protected function createMigration()
    {
        $path = base_path('database/migrations/');
        $tableName = Str::plural(Str::snake($this->model));
        $fileName = date('Y_m_d_His').'_create_'.$tableName.'_table.php';
        $className = 'Create'.Str::plural($this->model).'Table';

        $templateData = view('stubs.migration', [
            'className' => $className,
            'modelName' => $this->model,
            'tableName' => $tableName
        ]);

        $this->createFile($path.'/'.$fileName, $templateData);
        $this->info('Migration created: '.$fileName);
    }

    protected function createFile(string $file, string $contents)
    {
        $path = dirname($file);

        if (!empty($path) && !file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (!file_exists($file)) {
            return file_put_contents($file, $contents);

        }
    }
}
