<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SubjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/subject",
     *     tags={"Subject"},
     *     summary="Subject list",
     *     description="List Subject",
     *     operationId="subjectGet",
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
        $data = Subject::with('course')->get();
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
     *     path="/api/subject",
     *     tags={"Subject"},
     *     summary="Post Subject data",
     *     description="Insert Subject data",
     *     operationId="SubjectPost",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name","description","course_id"},
     *                 @OA\Property(property="name",type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="course_id", type="string")
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
            'course_id' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {

            $subject = Subject::create([
                "name" => $request->name,
                "description" => $request->description,
                "course_id" => $request->course_id
            ]);

            return response([
                'message' => 'success',
                'data' => $subject,
                'code' => 200
            ]);
        }
    }
}
