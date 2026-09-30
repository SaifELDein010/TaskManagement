<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveListRequest;
use App\Http\Requests\StoreListRequest;
use App\Http\Requests\UpdateListRequest;
use App\Services\ListService;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use App\Http\Requests\UpdateListWorkflowRequest;
use App\Services\ListWorkflowService;

class ListsController extends Controller{
    public function __construct( private ListService $listService,
    private ListWorkflowService $listWorkflowService) {}

    public function index() {
        $user = Auth::guard('api')->user();

        $lists = $this->listService->getAll($user);

        return response()->json([
            'data' => $lists,
        ]);
    }

    public function store(StoreListRequest $request) {
        $user = Auth::guard('api')->user();

        try {

            $list = $this->listService->create($user, $request->validated());

            return response()->json([
                'message' => 'List created successfully.',
                'data' => $list,
            ], 201);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);

        }
    }

    public function show(int $id) {
        $user = Auth::guard('api')->user();

        $list = $this->listService->getOne($user, $id);

        if (!$list) {
            return response()->json([
                'message' => 'List not found.',
            ], 404);
        }

        return response()->json([
            'data' => $list,
        ]);
    }

    public function update(UpdateListRequest $request, int $id) {
        $user = Auth::guard('api')->user();

        $list = $this->listService->update($user, $id, $request->validated());

        if (!$list) {
            return response()->json([
                'message' => 'List not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'List updated successfully.',
            'data' => $list,
        ]);
    }

    public function move(MoveListRequest $request, int $id) {
        $user = Auth::guard('api')->user();

        try {

            $data = $request->validated();

            $list = $this->listService->move($user, $id, $data['folder_id'] ?? null);

            if (!$list) {
                return response()->json([
                    'message' => 'List not found.',
                ], 404);
            }

            return response()->json([
                'message' => 'List moved successfully.',
                'data' => $list,
            ]);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);

        }
    }

    public function destroy(int $id) {
        $user = Auth::guard('api')->user();

        $result = $this->listService->delete($user, $id);

        if ($result === null) {
            return response()->json([
                'message' => 'List not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'List deleted successfully.',
        ]);
    }

    public function restore(int $id) {
        $user = Auth::guard('api')->user();

        $list = $this->listService->restore($user, $id);

        if (!$list) {
            return response()->json([
                'message' => 'List not found or user is not allowed to restore it.',
            ], 404);
        }

        return response()->json([
            'message' => 'List restored successfully.',
            'data' => $list,
        ]);
    }

    public function updateWorkflow(UpdateListWorkflowRequest $request, int $id) {
        $user = Auth::guard('api')->user();

        try {

            $list = $this->listWorkflowService->update($user, $id, $request->validated());

            if (!$list) {
                return response()->json([
                    'message' => 'List not found.',
                ],404);
            }

            return response()->json([
                'message' => 'List workflow updated successfully.',
                'data' => $list,
            ]);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ],422);
            
        }
    }
}