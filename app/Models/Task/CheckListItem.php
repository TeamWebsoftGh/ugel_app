<?php

namespace App\Models\Task;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckListItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'task_id', 'company_id'];
}
