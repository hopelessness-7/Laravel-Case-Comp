<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends MainController
{
    /**
     *
     * @OA\Get(
     *    path="/users",
     *    operationId="Withdrawal of all users",
     *    tags={"User"},
     *    summary="We output all users through a collection of resources",
     *    description="We output all users through a collection of resources",
     *     @OA\Response( response=200, description="All users",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="The request was successfully completed - Users were received."),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *              @OA\Property(property="data", ref="#/components/schemas/User"),
     *          ),
     *     ),
     *  )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(UserResource::collection(User::all()), 'The request was successfully completed - "Users" were received.');
    }

    /**
     *
     *    @OA\Post(
     *      path="/users",
     *      operationId="Create user",
     *      tags={"User"},
     *      summary="Store user in DB",
     *      description="Store user in DB",
     *      @OA\RequestBody( required=true,
     *         @OA\JsonContent(
     *            required={"name", "email", "password"},
     *            @OA\Property(property="name", type="string", format="string", example="Name"),
     *            @OA\Property(property="email", type="string", format="string", example="name@email.ru"),
     *            @OA\Property(property="password", type="string", format="string", example="password"),
     *         ),
     *      ),
     *     @OA\Response(response=200, description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="The request was successfully completed - User were received."),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *              @OA\Property(property="data", ref="#/components/schemas/User"),
     *          ),
     *      ),
     *      @OA\Response(response=409, description="request error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="Conflict when adding data to the database"),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="409"),
     *          ),
     *      )
     *  )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input,[
            'name' => 'required|max:50',
            'email' => 'required|max:255',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->sendResponse(new UserResource($newUser), 'The request was successfully completed - the "User" was updated.');
    }

    /**
     *
     * @OA\Get(
     *    path="/users/{id}",
     *    operationId="User output by id",
     *    tags={"User"},
     *    summary="Display the specified resource",
     *    description="Get User Detail",
     *      @OA\Parameter(name="id", in="path", description="Id of User", required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Response( response=200, description="User",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="The request was successfully completed - User were received."),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *              @OA\Property(property="data", ref="#/components/schemas/User"),
     *          ),
     *     ),
     *     @OA\Response(response=404, description="User not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="User not found"),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *          )
     *     )
     *    )
     *  )
     * )
     *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        if (is_null($user)) {
            return $this->sendError('The "User" does not exist.', 404);
        }

        return $this->sendResponse(new UserResource($user), 'The request was successfully completed - "User" were received.');
    }

    /**
     *
     *    @OA\Put(
     *      path="/users/{id}",
     *      operationId="Update user",
     *      tags={"User"},
     *      summary="Update the specified resource in storage.",
     *      description="Update the specified resource in storage.",
     *      @OA\Parameter(name="id", in="path", description="id", required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(name="name", in="path", description="name", required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(name="email", in="path", description="email", required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(name="password", in="path", description="password", required=false,
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Response( response=200, description="User show item",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="The request was successfully completed - User were received."),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *              @OA\Property(property="data", ref="#/components/schemas/User"),
     *          ),
     *     ),
     *     @OA\Response(response=404, description="User not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="User not found"),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *          )
     *     )
     *  )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $input = $request->all();

        $validator = Validator::make($input,[
            'name' => 'required|max:50',
            'email' => 'required|max:255',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->pasword == $request->password) {
            $user->password = $request->password;
        } else {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return $this->sendResponse(new UserResource($user), 'The request was successfully completed - the "User" was updated.');

    }

    /**
     *
     *    @OA\Delete(
     *      path="/users/{id}",
     *      operationId="Delete user",
     *      tags={"User"},
     *      summary="Remove the specified resource from storage.",
     *      description="Remove the specified resource from storage",
     *      @OA\Parameter(name="id", in="path", description="Id of User", required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Response( response=200, description="User show item",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="The request was successfully completed - the User was deleted"),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *          ),
     *     ),
     *     @OA\Response(response=404, description="User not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", readOnly="true", example="User not found"),
     *              @OA\Property(property="code", type="integer", readOnly="true", example="200"),
     *          )
     *     )
     *  )
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse([], 'The request was successfully completed - the "User" was deleted.');
    }
}
