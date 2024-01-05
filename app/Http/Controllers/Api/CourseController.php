<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/course",
     *     tags={"Course"},
     *     summary="Login for user",
     *     description="Login api for users",
     *     operationId="courseGet",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index()
    {
        $data = Course::all();
        if ($data) {
            return response([
                'message' => 'success',
                'data' => $data,
                'code' => 200
            ]);
        } else {
            return response([
                'message' => 'Falied to Fectch data',
                'code' => 404
            ]);
        }
    }
}
