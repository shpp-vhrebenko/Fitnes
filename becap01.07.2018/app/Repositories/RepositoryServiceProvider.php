<?php
namespace App\Repositories;

use App\User;
use App\Category;
use App\Item;
use App\Result;
use App\Courses;
use App\Marathons;

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
        $this->app->bind(CoursesRepositoryInterface::class, function($app) {
            return new CoursesModel(new Courses());
        });
        $this->app->bind(MarathonsRepositoryInterface::class, function($app) {
            return new MarathonsModel(new Marathons());
        });
    }

}