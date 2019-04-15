<?php

namespace App\Http\Controllers\Kaoshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kaoshi;

class KaoshiController extends Controller
{
    //
    public function getAccesstoken()
    {
        $token=Kaoshi::getAccesstoken();
        echo $token;
    }


}
