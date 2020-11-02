<?php
namespace victorycto\ImageStore\Console;

use Illuminate\Console\Command;
use victorycto\ImageStore\ImageStoreServiceProvider;

class InstallImageStore extends Command
{
    protected $signature = 'image-store:install';
    protected $description = 'Install ImageStore package';

    public function handle()
    {
        $this->info('Installing Image Store...');
        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "victorycto\ImageStore\ImageStoreServiceProvider",
        ]);

        $this->info('Finished install Image Store.');
    }
}
