<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/course",
     *     tags={"Course"},
     *     summary="Course list",
     *     description="List Courses",
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
        if (!empty($data)) {
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


     /**
     * @OA\Post(
     *     path="/api/course",
     *     tags={"Course"},
     *     summary="Post course data",
     *     description="Insert course data",
     *     operationId="postCourse",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name","description","fee","max_student","total_duration_days"},
     *                 @OA\Property(property="name",type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="fee", type="string"),
     *                 @OA\Property(property="max_student", type="string"),
     *                 @OA\Property(property="total_duration_days", type="string")
     *             )
     *         )
     *     ),
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
    public function create(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'description' => 'required',
            'fee' => 'required',
            'max_student' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {

            $course = Course::create([
                "name" => $request->name,
                "description" => $request->description,
                "fee" => $request->fee,
                "max_student" => $request->max_student,
                "total_duration_days" => $request->total_duration_days,
            ]);

            return response([
                'message' => 'success',
                'data' => $course,
                'code' => 200
            ]);
        }
    }
}
