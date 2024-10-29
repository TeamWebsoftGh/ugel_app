<?php

namespace App\Models\Audit;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $table = 'log_activities';

    protected $guarded = ['id'];

    /**
     * Get the user responsible for the given activity.
     *
     */

    public function getuserAttribute()
    {
        if ($this->attributes['user_model'])
            return $this->attributes['user_model']::find($this->attributes['user_id']);
    }

    public function logAction()
    {
        return $this->belongsTo(LogAction::class)->withDefault();
    }

    /**
     * Get the subject of the activity.
     *
     * @return mixed
     */
    public function subject()
    {
        return $this->morphTo();
    }

    public function getSubjectAttribute()
    {
        $subject =$this->attributes['subject_type']::find($this->attributes['subject_id']);
        return $subject;
    }

    public function getTableNameAttribute()
    {
        return ucfirst(\Illuminate\Support\Str::singular($this->subject->getTable()));
    }

    /**
     * Get the latest activities on the site
     * @param int $limit
     * @return mixed
     */
    static public function getLatest($limit = 100)
    {
        return self::with('subject')->orderBy('created_at', 'DESC')->limit($limit)->get();
    }

    /**
     * Get the latest activities on the site
     * @param int $minutes
     * @return mixed
     */
    static public function getLatestMinutes(int $minutes = 24 * 60)
    {
        $date = Carbon::now()->subMinutes($minutes);

        return self::where('created_at', '>=', $date)->orderBy('created_at', 'DESC')->get();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format').' h:mA');
    }
}
