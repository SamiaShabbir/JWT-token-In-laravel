<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\JWTTokenRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\JWTTokenRepository;
use App\Services\UserService;
use App\Services\ForgotPasswordService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
          $this->app->bind(JWTTokenRepositoryInterface::class, JWTTokenRepository::class);
          $this->app->bind(UserService::class, function ($app) {
                      return new UserService($app->make(UserRepositoryInterface::class),$app->make(JWTTokenRepositoryInterface::class));
                  });
          $this->app->bind(ForgotPasswordService::class, function ($app) {
                      return new ForgotPasswordService($app->make(UserRepositoryInterface::class));
                  });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
