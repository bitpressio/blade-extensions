<?php

namespace BitPress\BladeExtension\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
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

    public function handle()
    {
        $directives = app('blade.compiler')->getCustomDirectives();
        $columns = ['Directive', 'Type', 'Scope', 'File', 'Lines'];
        $clazz = new \ReflectionClass(app('blade.compiler'));
        // dd($clazz->getTraits());
        $methods = collect($clazz->getMethods())->filter(function ($method) {
            return $method->getName() !== 'compile' && Str::startsWith($method->getName(), 'compile');
        });

        // dd($methods);

        // dd(app('blade.compiler')->getCompilers());
        $results = collect($directives)->map(function ($directive, $key) {
            return $this->getDirectiveData($directive, $key);
        });
        
        $this->table($columns, $results);
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
}
