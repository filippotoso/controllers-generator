<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flash messages
    |--------------------------------------------------------------------------
    |
    | Add flash confirmation and error messages.
    | Requires laracasts/flash to work.
    |
 */

    'flash' => false,
   
    /*
    |--------------------------------------------------------------------------
    | View path format
    |--------------------------------------------------------------------------
    |
    | Define the format of the path for the generated views. You can use 
    | {model}, {models}, {kebab-mode} or {kebab-models} to include the 
    | model name in singular or plural version.
    |
     */

    'view' => 'backend.{models}',
   
    /*
    |--------------------------------------------------------------------------
    | Url format
    |--------------------------------------------------------------------------
    |
    | Define the format of the url for the generated controller endpoint.
    | This option is used when generating the routes. You can use {model}, 
    | {models}, {kebab-mode} or {kebab-models} to include the model name 
    | in singular or plural version. For actions and methods, laravel's 
    | conventions will be used.
    |
     */

    'url' => '/backend/{models}',

    /*
    |--------------------------------------------------------------------------
    | Routes format
    |--------------------------------------------------------------------------
    |
    | Define the format of the routes. You can use {model}, {models},
    | {kebab-mode} or {kebab-models} to include the model name 
    | in singular or plural version. 
    |
     */

    'route' => 'backend.{kebab-models}',

];
