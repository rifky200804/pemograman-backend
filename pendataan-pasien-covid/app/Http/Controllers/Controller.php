<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
     * @OA\Info(
     *      version="1.0.0",
     *      title="API Patient Corona  Virus",
     *      description="API Patient Corona Virus",
     *      @OA\Contact(
     *          email="rifky@gmail.com",
     *          name="Rifky"
     *      ),
     *      @OA\License(
     *          name="Your License",
     *          url="http://your-license-url.com"
     *      )
     * )
     */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
