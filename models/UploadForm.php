<?php

namespace GromIT\Forms\Models;

use October\Rain\Database\Model;
use System\Models\File;

class UploadForm extends Model
{
    public $attachOne = [
        'file' => File::class
    ];
}
