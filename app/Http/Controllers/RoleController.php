<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $roles = Role::all();
            return response()->json(["roles" => $roles, Response::HTTP_OK]);
        } catch (\Throwable $th) {
            return response()->json(["error" => $th, Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
    public function store(Request $request)

    {

        try {
            $validated = Validator::make($request->all(), [
                'role_name' => 'string|required|unique:roles',
                'status' => ['required', Rule::in("active", "deleted", "suspended")],
            ]);

            if ($validated->fails()) {
                return response()->json(["msg" => 'failed', "errors" => $validated->errors(), Response::HTTP_UNPROCESSABLE_ENTITY]);
            }

            $role = Role::create($validated->validated());
            return response()->json(['msg' => "success", "role" => $role, Response::HTTP_ACCEPTED]);
        } catch (\Throwable $th) {
            return response()->json(["error" => $th, Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
    public function show(Request $request, $id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json(['msg' => "Role not found"], Response::HTTP_NOT_FOUND);
            }
            event(new UserRegistered("sam"));

            return response()->json(['msg' => "success", "role" => $role, Response::HTTP_ACCEPTED]);
        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validated = Validator::make($request->all(), [
                'role_name' => 'string|required',
                'status' => ['required', Rule::in("active", "deleted", "suspended")],
            ]);

            if ($validated->fails()) {
                return response()->json(["msg" => 'failed', "errors" => $validated->errors(), Response::HTTP_UNPROCESSABLE_ENTITY]);
            }

            $role = Role::find($id);
            $role->role_name = $validated->validated()['role_name'];
            $role->status = $validated->validated()['status'];
            $role->save();

            return response()->json(['msg' => "success", "role" => $role, Response::HTTP_ACCEPTED]);
        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
    public function destroy(Request $request, $id)
    {
        try {
            $role = Role::find($id);
            $role->delete();
            return response()->json(['msg' => "success", Response::HTTP_NO_CONTENT]);
        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
