<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // try {
        //     \Storage::extend('google', function($app, $config) {
        //         $options = [
        //             'teamDriveId' => '1XM5xJPg-EKsQPE9n4PdB5hr8YuxAN2JM'
        //     ];
        //         $client = new \Google\Client();
        //         $client->setClientId($config['clientId']);
        //         $client->setClientSecret($config['clientSecret']);
        //         $client->refreshToken($config['refreshToken']);
        //         $service = new \Google\Service\Drive($client);
        //         $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, null, $options);
        //         $driver = new \League\Flysystem\Filesystem($adapter, [\League\Flysystem\Config::OPTION_VISIBILITY => \League\Flysystem\Visibility::PRIVATE]);
        //         return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
        //     });
        // } catch(\Exception $e) {
        //     dd($e);
        // }
        try {
            \Storage::extend('google', function($app, $config) {
                $client = new \Google_Client();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);
                $service = new \Google_Service_Drive($client);

                $options = [];
                if(isset($config['teamDriveId'])) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folder'] ?? '/', $options);
                $driver = new \League\Flysystem\Filesystem($adapter);
                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch(\Exception $e) {
            dd($e);
        }
    }
}
