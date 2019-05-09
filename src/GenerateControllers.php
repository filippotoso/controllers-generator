<?php

namespace FilippoToso\ControllersGenerator;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;

class GenerateController extends Command
{
    protected const OPEN_ROW = '<' . '?php' . "\n\n";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:controller
                            {controller : The name of the controller to be generated. It can include parent folders (i.e. Backend\\DashboardController)}
                            {model : The name of the model that will be used in the CRUD operations}
                            {--overwrite= : Overwrite already generated code. Available options: all, controller, test.}
                            {--protected : When set the generator adds code to protect the routes from users without the required permissions}
                            {--owned : When both this option and --protected are set, the generator ads checks for ownership before updating and deleting resources}
                            {--routes : When set the generator will add the required routes to the routes/web.php file}
                            {--base-controller= : Specify the base controller that the generated controller will extend}
                            {--test : When set the generator creates also a test suite for the controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controllers with CRUD capabilities';

    /**
     * Specify if overwrite existing generated models
     *
     * @var boolean
     */
    protected $overwrite = false;

    /**
     * Specify if generate a test suite
     *
     * @var boolean
     */
    protected $test = false;

    /**
     * Specify if add the routes to the router
     *
     * @var boolean
     */
    protected $routes = false;

    /**
     * Specify if add a Laratrust protection to the controller methods
     *
     * @var boolean
     */
    protected $protected = false;

    /**
     * Specify if add an ownership check on the controller methods (used only with --protected)
     *
     * @var boolean
     */
    protected $owned = false;

    /**
     * The name of the controller to be generated
     *
     * @var boolean
     */
    protected $controller = null;

    /**
     * The class of the controller that will be extended
     *
     * @var boolean
     */
    protected $baseController = null;

    /**
     * The model that will be used for the CRUD operations
     *
     * @var boolean
     */
    protected $model = null;

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
     */
    public function handle()
    {
        $this->model = $this->argument('model');
        $this->controller = str_finish($this->argument('controller'), 'Controller');
        $this->overwrite = $this->option('overwrite');
        $this->routes = $this->option('routes');
        $this->protected = $this->option('protected');
        $this->owned = $this->option('owned');
        $this->test = $this->option('test');
        $this->baseController = $this->option('base-controller');

        $this->info('Controller generation started.');

        $this->generate();

        $this->info('Controller successfully generated!');
    }

    protected function generate()
    {
        $this->generateController();

        if ($this->test) {
            $this->generateTest();
        }

        if ($this->routes) {
            try {
                $this->generateRoutes();
            } catch (\Exception $e) {
                $this->error("I wasn't able to add the required routes!");
            }
        }
    }

    protected function generateController()
    {

        $controllerPath = str_replace('\\', '/', $this->controller);

        $controllerFile = app_path('Http/Controllers/' . $controllerPath);

        $filename = $controllerFile . '.php';

        if (!file_exists($controllerFile) || in_array($this->overwrite, ['all', 'controller'])) {

            $controllerClass = 'App\\Http\\Controllers\\' . $this->controller;

            $controllerName = basename($controllerClass);

            $this->comment(sprintf('Generating controller "%s".', $controllerName));

            $baseController = $this->baseController ? 'App\\Http\\Controllers\\' . $this->baseController : 'App\\Http\\Controllers\\Controller';

            $modelClass = \str_start($this->model, 'App\\');
            $modelName = basename($modelClass);

            $items = str_plural(lcfirst($modelName));

            $params = [
                'protected' => $this->protected,
                'owned' => $this->owned,

                'namespace' => dirname($controllerClass),
                'controllerName' => $controllerName,
                'baseController' => $baseController,

                'modelClass' => $modelClass,
                'model' => $modelName,

                'indexRoute' => $this->routeName($modelName) . '.index',

                'viewPath' => $this->viewPath($modelName),

                'createPermission' => 'create-' . kebab_case($items),
                'readPermission' => 'read-' . kebab_case($items),
                'updatePermission' => 'update-' . kebab_case($items),
                'deletePermission' => 'delete-' . kebab_case($items),

                'object' => '$' . lcfirst($modelName),
                'objects' => '$' . $items,

                'item' => lcfirst($modelName),
                'items' => $items,

                'name' => strtolower(str_replace('-', ' ', kebab_case($modelName))),
            ];

            $content = self::OPEN_ROW . View::make('controllers-generator::generated-controller', $params)->render();

            $directory = dirname($filename);

            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            file_put_contents($filename, $content);


            $this->info(sprintf('Controller "%s" successfully generated!', $this->controller));
        } else {
            $this->error(sprintf('Controller "%s" already exists (and no overwrite requested), skipping.', $this->controller));
        }

    }

    protected function routeName($modelName)
    {
        $format = config('controllers-generator.route');
        return $this->translateConfiguration($modelName, $format);
    }

    protected function viewPath($modelName)
    {
        $format = config('controllers-generator.view');
        return $this->translateConfiguration($modelName, $format);
    }

    protected function generateTest()
    {
        $testName = $this->controller . 'Test';

        $testPath = str_replace('\\', '/', $testName);

        $testFile = base_path('tests/Feature/' . $testPath);

        $filename = $testFile . '.php';

        if (!file_exists($testFile) || in_array($this->overwrite, ['all', 'test'])) {

            $testClass = 'Tests\\Feature\\' . $this->controller . 'Test';

            $modelClass = \str_start($this->model, 'App\\');
            $modelName = basename($modelClass);

            $items = str_plural(lcfirst($modelName));

            $params = [
                'protected' => $this->protected,
                'owned' => $this->owned,

                'namespace' => dirname($testClass),

                'testName' => basename($testClass),

                'tableName' => str_plural(basename($modelName)),

                'createPermission' => 'create-' . kebab_case($items),
                'readPermission' => 'read-' . kebab_case($items),
                'updatePermission' => 'update-' . kebab_case($items),
                'deletePermission' => 'delete-' . kebab_case($items),

                'url' => $this->baseUrl($modelName),

                'modelClass' => $modelClass,

                'object' => '$' . lcfirst($modelName),
                'items' => $items,

                'useSoftDeletes' => $this->useSoftDeletes($modelClass),
            ];

            $content = self::OPEN_ROW . View::make('controllers-generator::generated-test', $params)->render();

            $directory = dirname($filename);

            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            file_put_contents($filename, $content);


            $this->info(sprintf('Test "%s" successfully generated!', $testName));
        } else {
            $this->error(sprintf('Test "%s" already exists (and no overwrite requested), skipping.', $testName));
        }

    }

    protected function generateRoutes()
    {
        $modelClass = \str_start($this->model, 'App\\');
        $modelName = basename($modelClass);

        $params = [
            'protected' => $this->protected,
            'owned' => $this->owned,

            'url' => $this->baseUrl($modelName),

            'controller' => $this->controller,

            'item' => lcfirst($modelName),

            'route' => $this->routeName(basename($this->model)),
        ];

        $content = "\n\n" . View::make('controllers-generator::generated-routes', $params)->render() . "\n";

        $filename = base_path('routes/web.php');
        file_put_contents($filename, $content, FILE_APPEND);

        $this->info('Routes successfully added!');
    }

    protected function baseUrl($modelName)
    {
        $format = config('controllers-generator.url');
        return $this->translateConfiguration($modelName, $format);
    }

    protected function translateConfiguration($modelName, $format)
    {
        $model = lcfirst($modelName);
        $models = str_plural($model);

        return str_replace(['{model}', '{models}', '{kebab-model}', '{kebab-models}', ], [$model, $models, kebab_case($model), kebab_case($models)], $format);
    }

    protected function useSoftDeletes($object)
    {
        $object = is_object($object) ? $object : new $object();
        return method_exists($object, 'bootSoftDeletes');
    }

}
