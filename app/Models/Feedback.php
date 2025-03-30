<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visitor_id',
        'office_id',
        'date_of_visit',
        'time_of_visit',
        'client_type',
        'sex',
        'region_of_residence',
        'services_availed',
        'served_by',
        'cc1',
        'cc2',
        'cc3',
        'responsiveness',
        'reliability',
        'access_facilities',
        'communication',
        'costs',
        'integrity',
        'assurance',
        'outcome',
        'commendations',
        'suggestions',
        'exported_by',
        'exported_at',
    ];

    protected $dates = [
        'deleted_at',
        'exported_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_of_visit' => 'date',
        'time_of_visit' => 'string',
        'cc1' => 'integer',
        'cc2' => 'integer',
        'cc3' => 'integer',
        'responsiveness' => 'integer',
        'reliability' => 'integer',
        'access_facilities' => 'integer',
        'communication' => 'integer',
        'costs' => 'integer',
        'integrity' => 'integer',
        'assurance' => 'integer',
        'outcome' => 'integer',
    ];

    /**
     * Get the visitor that owns the feedback.
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    /**
     * Get the office that received the feedback.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(User::class, 'office_id');
    }

    /**
     * Get the user who exported this feedback.
     */
    public function exportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by');
    }

    /**
     * Get the average rating for this feedback.
     */
    public function getAverageRatingAttribute(): float
    {
        $ratings = [
            $this->responsiveness,
            $this->reliability,
            $this->access_facilities,
            $this->communication,
            $this->costs,
            $this->integrity,
            $this->assurance,
            $this->outcome,
        ];

        $validRatings = array_filter($ratings, fn($rating) => $rating !== null && $rating > 0);
        
        if (empty($validRatings)) {
            return 0;
        }

        return round(array_sum($validRatings) / count($validRatings), 2);
    }
}