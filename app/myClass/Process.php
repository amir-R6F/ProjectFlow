<?php


namespace App\myClass;


use App\Models\User;

class Process
{
    public function allFriendAndRequest($all)
    {
        $allFriends = [];
        $allRequest = [];

        foreach ($all as $a) {
            if ($a->user_id_2 == auth()->user()->id && $a->state == null) { //request
                array_push($allRequest, User::select('id', 'name')->firstWhere('id', $a->user_id_1));
            } else if ($a->state == 1) { //friend
                if ($a->user_id_1 == auth()->user()->id) {
                    array_push($allFriends, User::select('id', 'name')->firstWhere("id", $a->user_id_2));
                } else {
                    array_push($allFriends, User::select('id', 'name')->firstWhere("id", $a->user_id_1));
                }
            }
        }

        return ['friends' => $allFriends, 'requests' => $allRequest];
    }

    public function getDocuments($document)
    {
        $projects = $document;
        foreach ($projects as $project) {
            foreach ($project->document as $key => $doc) {
                $file = base_path('public/storage/documents/myDOC/' . $doc->system_name);
                $project->document[$key]->mime = mime_content_type($file);
            }
        }
        return $projects;
    }
}
