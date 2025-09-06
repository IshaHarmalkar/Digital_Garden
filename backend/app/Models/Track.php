<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'trackable_id', 'trackable_type'];

    protected $casts = [
        'day' => 'date',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }

    // scopes  for reporting

    public function scopeForDay($query, $date)
    {
        return $query->where('day', $date);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('day', [$startDate, $endDate]);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('day', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('day', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ]);
    }

    public function scopeWithType($query, $type)
    {
        return $query->where('trackable_type', $type);
    }

    // helper methods for reports
    public static function getDailyReport($date)
    {
        return self::forDay($date)->with('trackable')->get();
    }

    public static function getWeeklyReport($startDate = null, $endDate = null)
    {

        $startDate = $startDate ?? Carbon::now()->startOfWeek();
        $endDate = $endDate ?? Carbon::now()->endOfWeek();

        return self::forDateRange($startDate, $endDate)
            ->with('trackable')
            ->get()
            ->groupBy('day');
    }

    public static function getMonthlyReport($month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return self::forDateRange($startDate, $endDate)
            ->with('trackable')
            ->get()
            ->groupBy('day');
    }
}
