<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostingRequest extends Model
{
    protected $fillable = [
       'department',
    'personnel',
    'particulars',
    'date_to_be_posted',
    'doc_title',
    'attachment_link',
    'attachment_file', 
    'ctrl_no',
    'doc_no',
    'status',
    ];
}
