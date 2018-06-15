<?php
namespace App\Repositories;

use App\User;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(UsersRepositoryInterface::class, function($app) {
            return new UsersModel(new User());
        });
    }

}