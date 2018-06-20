<?php
namespace App\Repositories;

use App\User;
use App\Category;
use App\Item;
use App\Result;

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
        $this->app->bind(ItemsRepositoryInterface::class, function($app) {
            return new ItemsModel(new Item());
        });
        $this->app->bind(ResultsRepositoryInterface::class, function($app) {
            return new ResultsModel(new Result());
        });
    }

}