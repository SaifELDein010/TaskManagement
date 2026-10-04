<?php

namespace App\Providers;

use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Workspace\WorkspaceRepository;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepository;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Folder\FolderRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            WorkspaceRepositoryInterface::class,
            WorkspaceRepository::class
        );

        $this->app->bind(
            WorkspaceMemberRepositoryInterface::class,
            WorkspaceMemberRepository::class
        );

        $this->app->bind(
            FolderRepositoryInterface::class,
            FolderRepository::class
        );
    }

    public function boot(): void {
        
    }
}