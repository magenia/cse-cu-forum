<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

class TagAPIController extends Controller
{
    /**
     * Shows all tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllTags()
    {
        return Datatables::of(Tag::query())
            ->addColumn('action', function ($tag) {
                $attr = '<div class="btn-group" role="group" aria-label="Second group">';
                $attr .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" id="edit-tag" data-id="' . $tag->id . '"><i class="fa fa-edit"></i> Edit</a>';
                $attr .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" id="delete-tag" data-id="' . $tag->id . '"><i class="fa fa-trash"></i> Delete</a></div>';

                return $attr;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get tag details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTag(int $id)
    {
        $tag = Tag::findOrFail($id);

        return response($tag, 200);
    }

    public function addTag(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:tags']
        ]);
        
        $slug = str_slug($request->name);
        
        Tag::create([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return response(null, 201);
    }

    public function editTag(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $this->validate($request, [
            'name' => ['required', 'unique:tags']
        ]);
        
        $slug = str_slug($request->name);
            
        $tag->update([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return response(null, 200);
    }

    public function deleteTag($id)
    {
        $tag = Tag::findOrFail($id);

        $tag->delete();

        return response(null, 200);
    }
}