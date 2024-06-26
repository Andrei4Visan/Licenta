<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Api\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search', false);
        $perPage = request('per_page', 10);
        $sortField = request('sort_field','updated_at');
        $sortDirection = request('sort_directions','desc');
        $query = User::query();
        $query->orderBy($sortField,$sortDirection);
        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        }

        return UserResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CreateUserRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(CreateUserRequest $request)
    {
       $data = $request->validated();
       $data['is_admin'] = $data['is_admin'] ?? 1;
       $data['email_verified_at'] = date('Y-m-d H-m-s');
       $data['password'] = Hash::make($data['password']);
       $data['created_by'] = $request->user()->id;
       $data['updated_by'] = $request->user()->id;
       ;
       $user = User::create($data);
       return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreateUserRequest  $request
     * @param  \App\Models\User  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if(!empty($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        $data['password'] = Hash::make($data['password']);
        $data['updated_by'] = $request->user()->id;


        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();

    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

}
