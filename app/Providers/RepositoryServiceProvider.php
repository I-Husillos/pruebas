<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Termosalud\Web\Product\Domain\ProductRepository;
use Termosalud\Web\Product\Infrastructure\Persistence\EloquentProductRepository;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;
use Termosalud\Web\ProductCategory\Infrastructure\Persistence\EloquentProductCategoryRepository;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;
use Termosalud\Web\Article\Domain\ContentArticleRepository;
use Termosalud\Web\Page\Domain\PageRepository;
use Termosalud\Web\ArticleCategory\Infrastructure\Persistence\EloquentArticleCategoryRepository;
use Termosalud\Web\Article\Infrastructure\Persistence\EloquentContentArticleRepository;
use Termosalud\Web\Page\Infrastructure\Persistence\EloquentPageRepository;
use Termosalud\Web\Form\Domain\FormRepository;
use Termosalud\Web\Form\Infrastructure\Persistence\EloquentFormRepository;
use Termosalud\Web\Language\Domain\LanguageRepository;
use Termosalud\Web\Market\Domain\MarketRepository;
use Termosalud\Web\Language\Infrastructure\Persistence\EloquentLanguageRepository;
use Termosalud\Web\Market\Infrastructure\Persistence\EloquentMarketRepository;
use Termosalud\Web\Treatment\Domain\TreatmentRepository;
use Termosalud\Web\Treatment\Infrastructure\Persistence\EloquentTreatmentRepository;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;
use Termosalud\Web\TreatmentCategory\Infrastructure\Persistence\EloquentTreatmentCategoryRepository;
use Termosalud\Web\User\Domain\UserRepository;
use Termosalud\Web\User\Infrastructure\Persistence\EloquentUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Catalog
        $this->app->bind(
            ProductRepository::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            ProductCategoryRepository::class,
            EloquentProductCategoryRepository::class
        );

        // Content
        $this->app->bind(
            PageRepository::class,
            EloquentPageRepository::class
        );

        $this->app->bind(
            ArticleCategoryRepository::class,
            EloquentArticleCategoryRepository::class
        );

        $this->app->bind(
            ContentArticleRepository::class,
            EloquentContentArticleRepository::class
        );

        // Users
        $this->app->bind(
            UserRepository::class,
            EloquentUserRepository::class
        );

        // Forms
        $this->app->bind(
            FormRepository::class,
            EloquentFormRepository::class
        );

        // GeoTargeting
        $this->app->bind(
            MarketRepository::class,
            EloquentMarketRepository::class
        );

        $this->app->bind(
            LanguageRepository::class,
            EloquentLanguageRepository::class
        );

        // Treatments
        $this->app->bind(
            TreatmentRepository::class,
            EloquentTreatmentRepository::class
        );

        $this->app->bind(
            TreatmentCategoryRepository::class,
            EloquentTreatmentCategoryRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
