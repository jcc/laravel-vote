<?php

/*
 * This file is part of the jcc/laravel-vote.
 *
 * (c) jcc <changejian@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jcc\LaravelVote;

use Illuminate\Support\ServiceProvider;

class VoteServiceProvider extends ServiceProvider
{
    /**
     * Application bootstrap event.
     */
    public function boot()
    {
	    $this->publishes([
		    \dirname(__DIR__) . '/config/vote.php' => config_path('vote.php'),
	    ], 'config');

        $this->publishes([
        	\dirname(__DIR__) . '/migrations/' => database_path('migrations'),
        ], 'migrations');

	    if ($this->app->runningInConsole()) {
		    $this->loadMigrationsFrom(\dirname(__DIR__) . '/migrations/');
	    }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
	    $this->mergeConfigFrom(
		    \dirname(__DIR__) . '/config/vote.php',
		    'vote'
	    );
    }
}