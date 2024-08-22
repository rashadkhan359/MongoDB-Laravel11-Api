<?php
namespace App\Http\Controllers\V1\Admin;

use App\Filters\UserFilter;
use App\Http\Requests\v1\Admin\UserIndexRequest;
use App\Http\Resources\v1\UserCollection;
use App\Http\Responses\v1\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController{
    public function index(UserIndexRequest $request){
        $filterParams = $request->validated();

        $query = User::query();

        $filter = new UserFilter();

        $query = $filter->apply($query, $filterParams);

        $perPage = $request->input('per_page', 15);

        $page = $request->input('page', 1);

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::success(new UserCollection($users));
    }
}
