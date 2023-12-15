<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Repositories\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    private $category;

    public function __construct()
    {
        $this->category = new Project(new Categories);
    }

    public function getCats(Request $request)
    {
        $id = $request->id;
        $cats = Categories::where("project_id", $request->id)
            ->select("id", "title", "project_id", DB::raw("0 as active"))
            ->with(["task" => function ($q) use ($id) {
                $q->where("project_id", $id);
            }])
            ->get();
        return response()->json(["categories" => $cats], 200);
    }

    public function categoryList(Request $request)
    {
        $cats = Categories::where("project_id", $request->id)->get();
        return response()->json(["category_list" => $cats]);
    }

    public function store(Request $request)
    {
        $this->category->create($request);
    }

    public function update(Request $request)
    {
        $this->category->update($request);
    }

    public function delete(Request $request)
    {
        $this->category->delete($request);
    }
}
