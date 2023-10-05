<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Settings\List\PaginatorResource;
use App\Settings\SettingManager;

class SettingController extends Controller
{
    public function index(
        SettingManager $manager
    ): PaginatorResource {
        return new PaginatorResource(
            $manager->getSettings()
        );
    }
}
