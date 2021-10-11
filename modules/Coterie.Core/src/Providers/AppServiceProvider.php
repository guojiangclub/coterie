<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Providers;

use iBrand\Component\User\Models\User as BaseUser;
use iBrand\Component\User\UserServiceProvider;
use iBrand\Coterie\Core\Auth\User;
use iBrand\Coterie\Core\Console\InstallCommand;
use iBrand\Coterie\Core\Models\Comment;
use iBrand\Coterie\Core\Models\Content;
use iBrand\Coterie\Core\Models\Coterie;
use iBrand\Coterie\Core\Models\Member;
use iBrand\Coterie\Core\Models\Order;
use iBrand\Coterie\Core\Models\Praise;
use iBrand\Coterie\Core\Models\Question;
use iBrand\Coterie\Core\Models\Reply;
use iBrand\Coterie\Core\Policies\CommentPolicy;
use iBrand\Coterie\Core\Policies\ContentPolicy;
use iBrand\Coterie\Core\Policies\CoteriePolicy;
use iBrand\Coterie\Core\Policies\MemberPolicy;
use iBrand\Coterie\Core\Policies\OrderPolicy;
use iBrand\Coterie\Core\Policies\PraisePolicy;
use iBrand\Coterie\Core\Policies\QuestionPolicy;
use iBrand\Coterie\Core\Policies\ReplyPolicy;
use iBrand\Coterie\Core\Repositories\CommentRepository;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\Eloquent\CommentRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\ContentRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\CoterieRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\InviteMemberRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\InviteRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\MemberRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\OrderRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\PraiseRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\QuestionRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\Eloquent\ReplyRepositoryEloquent;
use iBrand\Coterie\Core\Repositories\InviteMemberRepository;
use iBrand\Coterie\Core\Repositories\InviteRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\OrderRepository;
use iBrand\Coterie\Core\Repositories\PraiseRepository;
use iBrand\Coterie\Core\Repositories\QuestionRepository;
use iBrand\Coterie\Core\Repositories\ReplyRepository;
use iBrand\Coterie\Core\Services\CoteriePayNotifyService;
use Illuminate\Cache\RedisStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Coterie::class => CoteriePolicy::class,
        Comment::class => CommentPolicy::class,
        Content::class => ContentPolicy::class,
        Reply::class => ReplyPolicy::class,
        Member::class => MemberPolicy::class,
        Question::class => QuestionPolicy::class,
        Praise::class => PraisePolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (config('ibrand.coterie.secure')) {
            \URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        if (!class_exists('CreateCoterieTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../../migrations/create_coterie_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_coterie_tables.php",
            ], 'migrations');
        }

        $this->registerPolicies();

        $this->publishes([
            app_path().'/../vendor/laravel/passport/database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->commands([InstallCommand::class]);

        $this->commands([
            \iBrand\Coterie\Core\Console\DatabaseCreateCommand::class,
            \iBrand\Coterie\Core\Console\DatabaseDeleteCommand::class,
        ]);

        $this->setRedisTenancy();
    }

    public function register()
    {
        $this->registerComponent();

        $this->app->bind(BaseUser::class, User::class);

        $this->app->bind(CoterieRepository::class, CoterieRepositoryEloquent::class);

        $this->app->bind(MemberRepository::class, MemberRepositoryEloquent::class);

        $this->app->bind(ContentRepository::class, ContentRepositoryEloquent::class);

        $this->app->bind(CommentRepository::class, CommentRepositoryEloquent::class);

        $this->app->bind(QuestionRepository::class, QuestionRepositoryEloquent::class);

        $this->app->bind(ReplyRepository::class, ReplyRepositoryEloquent::class);

        $this->app->bind(PraiseRepository::class, PraiseRepositoryEloquent::class);

        $this->app->bind(OrderRepository::class, OrderRepositoryEloquent::class);

        $this->app->bind(InviteRepository::class, InviteRepositoryEloquent::class);

        $this->app->bind(InviteMemberRepository::class, InviteMemberRepositoryEloquent::class);

        $this->app->bind('ibrand.pay.notify.default', CoteriePayNotifyService::class);
    }

    protected function registerComponent()
    {
        $this->app->register(UserServiceProvider::class);
    }

    protected function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    protected function setRedisTenancy()
    {
//        config(['cache.default' => 'redis_tenancy']);
//
//        Cache::extend('redis_tenancy', function ($app) {
//
//            $uuid = client_id();
//
//            $res = Cache::repository(new RedisStore(
//                $app['redis'],
//                $uuid,
//                $app['config']['cache.stores.redis.connection']
//            ));
//
//            return $res;
//
//        });
    }
}
