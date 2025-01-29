<?php

namespace Kernel\Backend;
use Exception;

//use Kernel\Backend\DataBase\DB\DataBaseConnect;
use Kernel\Backend\Http\Kernel;
use Kernel\Backend\Http\Request;
use Kernel\Backend\Http\Response;
use Kernel\Backend\Routing\Router;
//use Kernel\Backend\Validation\Validation;
//use Kernel\Backend\Validation\Validator\Validator;

class App extends Container
{
    public function __construct(array $services = [])
    {
        //$this->loadHelpers();
        parent::__construct($services);
    }

    public function run(): void
    {
        $this->registerServices();
        //$this->initializeConfig();
        //$this->get('database')->connect();
        //$this->get('cache')->init();
        $this->createKernel()->run();
    }

    private function registerServices(): void
    {
        //$config = new Config($this->loadConfig());
        //$this->set('config', $config);
        $this->set('request', $this->services['request'] ?? Request::createFromGlobals());
        $this->set('response', $this->services['response'] ?? new Response);
        $this->set('router', $this->services['router'] ?? new Router);
//        $this->set('database', $this->services['database'] ?? new DataBaseConnect($this->get('config')));
//        $this->set('validator', $this->services['validator'] ?? new Validator);
//        $this->set('validation', $this->services['validation'] ?? new Validation($this->get('validator')));
    }

    private function createKernel(): Kernel
    {
        return new Kernel(
            $this->get('request'),
            $this->get('response')
        );
    }

    private function initializeConfig(): void
    {
        $this->loadConfig();
    }

    private function loadConfig(): array
    {
        $configPath = str_replace(DIRECTORY_SEPARATOR, '/', APP_ROOT.'/config/app.php');

        if (! file_exists($configPath)) {
            throw new Exception('Config file not found.');
        }

        $config = require $configPath;

        if (! is_array($config)) {
            throw new Exception('Config file is not an array.');
        }

        return $config;
    }

    private function loadHelpers(): void
    {
        $helpersPath = APP_ROOT.'/core/App/helpers.php';

        require_once $helpersPath;
    }
}