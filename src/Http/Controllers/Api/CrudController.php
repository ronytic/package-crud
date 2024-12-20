<?php

namespace ProcessMaker\Package\PackageCrud\Http\Controllers\Api;

use URL;
use RBAC;
use Illuminate\Http\Request;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackageCrud\Models\PsCrud;
use ProcessMaker\Package\PackageCrud\Http\Resources\CrudResource;
use ProcessMaker\Package\PackageCrud\Http\Resources\CrudCollection;


class CrudController extends Controller
{

    public function index(Request $request)
    {
        $query = PsCrud::query();

        $filter = $request->input('filter', '');
        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $query->where(function ($query) use ($filter) {
                $query->Where('name', 'like', $filter);
            });
        }

        $order_by = $request->has('order_by') ? $order_by = $request->get('order_by') : 'name';
        $order_direction = $request->has('order_direction') ? $request->get('order_direction') : 'ASC';

        $response =
            $query->orderBy(
                $request->input('order_by', $order_by),
                $request->input('order_direction', $order_direction)
            )->paginate($request->input('per_page', 10));

        //return new ApiCollection($response);
        return new CrudCollection($response);
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $this->validate($request, [
                'name' => 'required',
                'description' => 'string|nullable',
                'code' => 'nullable',
                'status' => 'required|in:active,inactive'
            ]);

            // Create the record
            $crud = new PsCrud();
            $crud->name = $request->get('name');
            $crud->description = $request->get('description');
            $crud->code = $request->get('code');
            $crud->status = $request->get('status');
            $crud->saveOrFail();

            // Return the created record
            return new CrudResource($crud, 200);
        } catch (\Exception | \Throwable | \Error $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $uuid)
    {
        try {
            // Validate the request
            $this->validate($request, [
                'name' => 'required',
                'description' => 'string|nullable',
                'code' => 'nullable',
                'status' => 'required|in:active,inactive'
            ]);

            // Find the record
            $crud = PsCrud::where('uuid', $uuid)->first();
            if (!$crud) {
                return response()->json([
                    'message' => 'Record not found'
                ], 404);
            }
            // Update the record
            $crud->name = $request->get("name");
            $crud->description = $request->get("description");
            $crud->code = $request->get("code");
            $crud->status = $request->get("status");
            $crud->saveOrFail();
            // Return the updated record
            return new CrudResource($crud, 200);
        } catch (\Exception | \Throwable | \Error $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($uuid)
    {
        try {
            // Find the record
            $crud = PsCrud::where('uuid', $uuid)->first();
            if (!$crud) {
                return response()->json([
                    'message' => 'Record not found'
                ], 404);
            }
            // Delete the record
            $crud->delete();
            // Return a 204 response
            return response([], 204);
        } catch (\Exception | \Throwable | \Error $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function generate($license_generator) {}
}
