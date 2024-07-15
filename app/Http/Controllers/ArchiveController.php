<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\School;
use App\Services\QgisService;
use DB;

class ArchiveController extends Controller
{
    public function archiveSchool($id, $title)
    {

        $school = School::find($id);
        $sections = $school->sections;

        $json = [];
        foreach ($sections as $section) {
            $sectionJson = collect(QgisService::calculatePollutionAndCaloriesForTrips($section->trips))->flatten();
            $json[$section->id] = $sectionJson;
        }

        DB::transaction(function () use ($school, $json, $title) {

            Archive::create(
                [
                    'school_id' => $school->id,
                    'graph_data' => $json,
                    'title' => $title
                ]
            );

            $school->students()->delete();
        });

        return redirect()->back();
    }
}
