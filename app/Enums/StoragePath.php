<?php

namespace App\Enums;

enum StoragePath: string
{
    case PROFILE_IMAGE = "profile_image";
    case RESUME = "resume";
    case PROJECT_IMAGES = "project_images";
}
