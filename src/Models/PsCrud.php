<?php

namespace ProcessMaker\Package\PackageCrud\Models;

use ProcessMaker\Models\ProcessMakerModel;
use ProcessMaker\Traits\HasUuids;

class PsCrud extends ProcessMakerModel
{
    use HasUuids;

    protected $table = 'ps_crud';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'code',
        'status',
    ];
}
