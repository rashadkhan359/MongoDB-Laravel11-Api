<?php

namespace App\Http\Controllers\v1\Admin;

use App\Filters\BlogFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\BlogIndexRequest;
use App\Http\Requests\v1\Admin\BlogStoreRequest;
use App\Http\Resources\v1\BlogCollection;
use App\Http\Resources\v1\BlogResource;
use App\Http\Responses\v1\ApiResponse;
use App\Models\Blog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogIndexRequest $request)
    {
        $filterParams = $request->validated();

        $query = Blog::query();

        $filter = new BlogFilter();

        $query = $filter->apply($query, $filterParams);

        $perPage = $request->input('per_page', 15);

        $page = $request->input('page', 1);

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::success(new BlogCollection($users));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogStoreRequest $request)
    {
        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        ApiResponse::success([], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog  = Blog::findOrFail($id);
        return ApiResponse::success(new BlogResource($blog));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return ApiResponse::noContent();
    }
}
