<?php

namespace App\Http\Controllers;

use App\Http\Requests\Folder\MoveFolderRequest;
use App\Http\Requests\Folder\StoreFolderRequest;
use App\Http\Requests\Folder\UpdateFolderRequest;
use App\Services\FolderService;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class FolderController extends Controller{
    public function __construct(private FolderService $folderService) {}

    public function index() {
        $user = Auth::guard('api')->user();

        $folders = $this->folderService->getAllForUser($user);

        return response()->json([
            'data' => $folders,
        ]);
    }

    public function show(int $id) {
        $user = Auth::guard('api')->user();

        $folder = $this->folderService->findForUser($user, $id);

        if (!$folder) {
            return response()->json([
                'message' => 'Folder not found.',
            ], 404);
        }

        return response()->json([
            'data' => $folder,
        ]);
    }

    public function store(StoreFolderRequest $request) {
        $user = Auth::guard('api')->user();

        try {

            $folder = $this->folderService->create($user, $request->validated());

            return response()->json([
                'message' => 'Folder created successfully.',
                'data' => $folder,
            ], 201);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);

        }
    }

    public function workspaceFolders(int $workspaceId) {
        $user = Auth::guard('api')->user();

        $folders = $this->folderService->getAllForWorkspace($user, $workspaceId);

        return response()->json([
            'data' => $folders,
        ]);
    }

    public function tree(int $workspaceId) {
        $user = Auth::guard('api')->user();

        $tree = $this->folderService->getTree($user, $workspaceId);

        return response()->json([
            'data' => $tree,
        ]);
    }

    public function update(UpdateFolderRequest $request, int $id) {
        $user = Auth::guard('api')->user();

        try {

            $folder = $this->folderService->update($user, $id, $request->validated());

            if (!$folder) {
                return response()->json([
                    'message' => 'Folder not found.',
                ], 404);
            }

            return response()->json([
                'message' => 'Folder updated successfully.',
                'data' => $folder,
            ]);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);

        }
    }

    public function move(MoveFolderRequest $request, int $id) {
        $user = Auth::guard('api')->user();

        try {

            $folder = $this->folderService->move($user, $id, $request->validated()['parent_id']);

            if (!$folder) {
                return response()->json([
                    'message' => 'Folder not found.',
                ], 404);
            }

            return response()->json([
                'message' => 'Folder moved successfully.',
                'data' => $folder,
            ]);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);

        }
    }

    public function destroy(int $id) {
        $user = Auth::guard('api')->user();

        try {

            $deleted = $this->folderService->delete($user, $id);

            if (!$deleted) {
                return response()->json([
                    'message' => 'Folder not found.',
                ], 404);
            }

            return response()->json([
                'message' => 'Folder deleted successfully.',
            ]);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
            
        }
    }
}