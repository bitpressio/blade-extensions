<?php

namespace BitPress\BladeExtension\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use ReflectionClass;
use ReflectionFunction;

class BladeListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'blade:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List registered custom blade directives.';

    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    /**
     * @var \ReflectionClass
     */
    protected $compilerReflection;

    public function __construct(BladeCompiler $compiler)
    {
        parent::__construct();
        
        $this->compiler = $compiler;
    }

    public function handle()
    {       
        $this->table(
            ['Directive', 'Type', 'Scope', 'File', 'Lines'],
            collect($this->compiler->getCustomDirectives())->map(function ($directive, $key) {
                return $this->getDirectiveData($directive, $key);
            })
        );
    }

    protected function getDirectiveData($directive, $key)
    {
        if (is_array($directive)) {
            if (! is_callable($directive)) {
                throw new \Exception('Invalid callable');
            }
            $reflectCallable = new \ReflectionClass($directive[0]);
            $symbol = $reflectCallable->getMethod($directive[1])->isStatic() ? '::' : '->';
            return [
                $key,
                $reflectCallable->getName(),
                $reflectCallable->getName() . $symbol . $reflectCallable->getMethod($directive[1])->getName() . '()',
                '.' . str_replace(base_path(), '', $reflectCallable->getFileName()),
                sprintf('%d to %d', $reflectCallable->getMethod($directive[1])->getStartLine(), $reflectCallable->getMethod($directive[1])->getEndLine()),
            ];
        } else {
            $reflectDirective = new ReflectionFunction($directive);    
            return [
                $key,
                get_class($directive),
                get_class($reflectDirective->getClosureThis()),
                '.' . str_replace(base_path(), '', $reflectDirective->getFileName()),
                sprintf('%d to %d', $reflectDirective->getStartLine(), $reflectDirective->getEndLine()),
            ];
        }
    }

    protected function getInternalCompilerDefinitions()
    {
        // @todo Get the compile* methods
        $methods = $this->getCompilerMethods();
        // @todo Determine where they are defined (i.e., in a trait or on the class)
    }

    private function getCompilerMethods()
    {
        return collect($this->reflectCompiler()->getMethods())->filter(function ($method) {
            return $method->getName() !== 'compile' && Str::startsWith($method->getName(), 'compile');
        });
    }

    private function reflectCompiler()
    {
        if ($this->compilerReflection) {
            return $this->compilerReflection;
        }

        return $this->compilerReflection = new ReflectionClass($this->compiler);
    }
}
