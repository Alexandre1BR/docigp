<?php

use App\Data\Models\File;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

//$factory->define(File::class, function () {
//    return [
//        'hash' => sha1(Str::random(30)),
//        'drive' => 'documents',
//        'path' => '/documents',
//        'mime_type' => 'application/pdf',
//    ];
//});
class FileFactory extends Factory{

    protected $model = File::class;

    public function definition(){
            return [
        'hash' => sha1(Str::random(30)),
        'drive' => 'documents',
        'path' => '/documents',
        'mime_type' => 'application/pdf',
    ];
    }
}
