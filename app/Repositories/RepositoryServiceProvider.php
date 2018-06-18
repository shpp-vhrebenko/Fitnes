<?php
namespace App\Repositories;

use App\User;
use App\Category;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(UsersRepositoryInterface::class, function($app) {
            return new UsersModel(new User());
        });
        $this->app->bind(CategoriesRepositoryInterface::class, function($app) {
            return new CategoriesModel(new Category());
        });
    }

}