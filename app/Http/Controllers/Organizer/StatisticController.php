<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        // Get all events with their participant count
        $events = Event::withCount('participants')
            ->orderBy('start_date', 'desc')
            ->get();

        // Calculate trends (comparing to previous event)
        $events->each(function ($event, $key) use ($events) {
            $event->trend = 0; // 0: no change, >0: increase, <0: decrease
            $event->trend_percentage = 0;
            
            // Previous event is the next one in the collection (since it's ordered by start_time desc)
            if (isset($events[$key + 1])) {
                $previousCount = $events[$key + 1]->participants_count;
                $currentCount = $event->participants_count;
                
                if ($previousCount > 0) {
                    $event->trend = $currentCount - $previousCount;
                    $event->trend_percentage = round(($event->trend / $previousCount) * 100, 1);
                } else if ($currentCount > 0) {
                    $event->trend = $currentCount;
                    $event->trend_percentage = 100;
                }
            }
        });

        // Prepare data for chart
        $chartData = $events->reverse()->map(function ($event) {
            return [
                'name' => mb_strimwidth($event->name, 0, 20, '...'),
                'count' => $event->participants_count
            ];
        })->values();

        return view('organizer.statistic', compact('events', 'chartData'));
    }
}
