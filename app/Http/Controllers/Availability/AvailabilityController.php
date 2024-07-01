<?php

namespace App\Http\Controllers\Availability;

use App\Http\Controllers\Controller;
use App\Http\Requests\Availability\AvailabilityRequest;
use Illuminate\Http\Request;
use App\Models\Availability\Availability;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function setAvailability(AvailabilityRequest $request): \Illuminate\Http\JsonResponse
    {

        $user = auth()->user();

        // Clear existing availability
        Availability::where('user_id', $user->id)->delete();

        // Insert new availability
        foreach ($request->availability as $slot) {
            Availability::create([
                'user_id' => $user->id,
                'day' => $slot['day'],
                'start' => $slot['start'],
                'end' => $slot['end'],
            ]);
        }

        return response()->json(['message' => 'Availability set successfully'], 200);
    }

    public function getAvailability($userId, $encodedTimezone): \Illuminate\Http\JsonResponse
    {
        $timezone = decrypt($encodedTimezone);
        $user = User::findOrFail($userId);

        $availabilities = $user->availabilities()->get()->groupBy('day');

        $convertedAvailabilities = [];

        foreach ($availabilities as $day => $slots) {
            foreach ($slots as $slot) {
                // Convert start and end times
                $start = Carbon::createFromFormat('H:i:s', $slot->start, 'UTC')
                    ->setTimezone($timezone)
                    ->format('H:i');
                $end = Carbon::createFromFormat('H:i:s', $slot->end, 'UTC')
                    ->setTimezone($timezone)
                    ->format('H:i');

                $convertedAvailabilities[$day][] = ['start' => $start, 'end' => $end];
            }
        }


        return response()->json($convertedAvailabilities);
    }

}
