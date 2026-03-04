<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Dba\DddSkeleton\Shared\Infrastructure\Bus\Command\LaravelCommandBus;
use Dba\DddSkeleton\Shared\Infrastructure\Bus\Query\LaravelQueryBus;

class BusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QueryBus::class, LaravelQueryBus::class);
        $this->app->singleton(CommandBus::class, LaravelCommandBus::class);

        $this->registerHandlers();

        $this->app->when(LaravelQueryBus::class)
            ->needs('$queryHandlers')
            ->giveTagged('query_handler');

        $this->app->when(LaravelCommandBus::class)
            ->needs('$commandHandlers')
            ->giveTagged('command_handler');
    }

    private function registerHandlers(): void
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in(base_path('src'))->name('*Handler.php');

        foreach ($finder as $file) {
            $namespace = 'Termosalud\\Web';
            $relativePath = str_replace('Web/', '', $file->getRelativePathname());
            $className = $namespace . '\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (str_ends_with($className, 'QueryHandler')) {
                $this->app->bind($className, $className);
                $this->app->tag($className, 'query_handler');
            }

            if (str_ends_with($className, 'CommandHandler')) {
                $this->app->bind($className, $className);
                $this->app->tag($className, 'command_handler');
            }
        }
    }
}
