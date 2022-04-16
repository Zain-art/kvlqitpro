<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use URL;
use Illuminate\Support\ServiceProvider;
use View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class CompanyInfoServiceProvider extends ServiceProvider
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

        View::composer('*', function ($view) {
            $companyinfo = DB::table('companyinfo')->first();
            $companyinfo->logo = url('/') . $companyinfo->logo;
            $companyinfo->nav_logo = url('/') . $companyinfo->nav_logo;
            $companyinfo->report_logo = url('/') . $companyinfo->report_logo;


            // User Check 

            if (Auth::user()) {
                $userrights = unserialize(Auth::user()->userrights);
                $ids = [];
                if (!empty($userrights)) {
                    foreach ($userrights as $user) {
                        $ids[] = $user['id'];
                    }
                }
                if (Auth::user()->email !== 'info@quadacts.com') {
                    $chck = DB::table('menus')->where('route_path', request()->route()->uri())->whereIn('id', $ids)->get();
                    if (count($chck) <= 0) {
                        echo '<!DOCTYPE html>
                        <html lang="en">
                        
                        <head>
                            <meta charset="UTF-8">
                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Document</title>
                            <link rel="stylesheet" href="css/bootstrap.min.css">
                            <link rel="stylesheet" href="css/style.css">
                        </head>
                        <style>
                            * {
                            padding: 0%;
                            margin: 0%;
                            box-sizing: border-box;
                        }
                        
                        body {
                            height: 100vh;
                            display: grid;
                            place-items: center;
                        }
                        
                        .content {
                            text-align: center;
                        }
                        .btn{
                            background:#24a0ed;
                            text-decoration:none;
                            padding:1rem 2rem;
                            color:black;
                        }
                        .btn:hover{
                            background:#13a0ed;
                        }
                        .content .bg-h1 {
                            background-image: url("'. URL::to('/assets/images/Beautiful-Stars-Pic.jpg').'");
                            background-repeat: no-repeat;
                            background-position: center;
                            background-size: cover;
                            font-size: 10rem;
                            font-weight: bold;
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                            font-family: \'Steelfish Rg\', \'helvetica neue\', helvetica, arial, sans-serif;
                        }
                        
                        .content p:nth-child(2) {
                            font-weight: bold;
                            margin-top:1rem;
                            font-size: 1.5rem;
                        }
                        
                        .content p:nth-child(3) {
                            width: 50%;
                            margin: .5rem auto 2rem auto;
                        }
                        </style>
                        
                        <body>
                            <div class="content">
                                <h1 class="bg-h1">Oops!</h1>
                                <p>404 - PAGE NOT FOUND</p>
                                <p>The page you are looking for might have been removed had its name changed or temporarily unavailable</p>
                                <a href="#" onclick="redirect();" class="btn btn-primary" id="sendBack">Go to home page</a> 
                            </div>
                        
                        
                            <script>
                                function redirect(){
                                    window.location = "'. URL::to('/dashboard') .'";
                                }
                            </script>
                        </body>
                        
                        </html>';
                        exit;
                    }
                }
            }
            // Menus (sidebar)
            $allmodules = DB::table('modules')->get();
            $allMenus = array();


            foreach ($allmodules as $module) {

                $menus = DB::table('menus')->where('module', $module->id)->where('menu_type', 1)->get();
                $module->menus = $menus;
                $allMenus[] = $module;
            }

            $view->with(['companyinfo' => $companyinfo, 'allMenus' => $allMenus]);
        });
    }
}
