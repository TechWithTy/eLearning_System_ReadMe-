<?php

namespace App\Http\Controllers;

use App\Models\BoardMessage;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Feedback;
use App\Models\GroupProject;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\Session;
use App\Models\StudentProject;
use App\Models\Task;
use App\Models\TaskSubmission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class TeacherController extends Controller
{

    public function getTeacherCourses(){
        try{
            $user=Auth::id();
            $courses=Course::where('teacher_id',$user)->get();
            return response()->json([
                'status' => '200',
                'courses' => $courses
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function addCourseSchedule(Request $request){

        try{
            $schedule=new Schedule;
            $schedule->course_id=$request->course_id;
            $schedule->name=$request->name;
            $schedule->start_date=$request->start_date;
            $schedule->end_date=$request->end_date;
            $schedule->save();
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }

    
    public function removeCourseSchedule($schedule_id){

        try{
            Schedule::where([['id','=',$schedule_id]])->delete();

            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
        
    }
    public function addScheduleMaterial(Request $request){
    try{
        $material=new CourseMaterial;
        $material->schedule_id=$request->schedule_id;
        $material->course_id=$request->course_id;
        $material->teacher_id=Auth::id();
        $material->title=$request->title;
        $material->content=$request->content;

        $base64Image=$request->input('file');
        $binaryData=base64_decode($base64Image);
        $originalFileName = $request->file_name;

        //create temp file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'temp_base64');
        file_put_contents($tempFilePath, $binaryData);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFilePath,
            $originalFileName, // Provide a fallback original filename
            mime_content_type($tempFilePath), // Guess the MIME type
            null,
            true // Delete the file after it's used
        );
    
        //get file extension
        $fileExtension = $uploadedFile->getClientOriginalExtension();
        unlink($tempFilePath);
        $fileName = uniqid() . '.'.$fileExtension;

        Storage::disk('public')->put('files/' . $fileName, $binaryData);
        $publicUrl = Storage::disk('public')->url('files/' . $fileName);
        $material->file_path=$publicUrl;
        $material->save();
        return response()->json([
            'status' => '200',
        ]);
    } catch(Exception $e){
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    } 
    }

    
    public function removeScheduleMaterial($material_id){
        try{
            CourseMaterial::where([['id','=',$material_id]])->delete();

            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }

    public function addScheduleTask(Request $request){
        try{
            $task=new Task;
            $task->title=$request->title;
            $task->description=$request->description;
            $task->due_date=$request->due_date;
            $task->course_id=$request->course_id;
            $task->schedule_id=$request->schedule_id;
            $task->teacher_id=Auth::id();
            $task->task_type=$request->task_type;
            $task->save();
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }

    public function removeScheduleTask($task_id){
        try{
            Task::where([['id','=',$task_id]])->delete();

            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }


    public function addScheduleSession(Request $request){
        
        try{
            $session=new Session;
            $session->date=$request->date;
            $session->course_id=$request->course_id;
            $session->schedule_id=$request->schedule_id;

            $session->save();
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }

    public function removeScheduleSession($session_id){
        try{
            Session::where([['id','=',$session_id]])->delete();

            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }




    // public function getSessionAttendance($session_id){

    //     try{
    //         $session=Session::where([['id','=',$session_id]])->first();
    //         $attendances=$session->attendances;
    //         return response()->json([
    //             'status' => '200',
    //             'attendances'=>$attendances
    //         ]);
    //     } catch(Exception $e){
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ]);
    //     } 

    // }
    // public function addSessionAttendance(Request $request){
        
    // }

    public function addCourseProject(Request $request){
        try{
            $project=new GroupProject;
            $project->course_id=$request->course_id;
            $project->submission_date=$request->submission_date;
            $project->status=$request->status;

            $base64Image=$request->input('file');
            $binaryData=base64_decode($base64Image);
            $originalFileName = $request->file_name;

            //create temp file
            $tempFilePath = tempnam(sys_get_temp_dir(), 'temp_base64');
            file_put_contents($tempFilePath, $binaryData);

            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tempFilePath,
                $originalFileName, // Provide a fallback original filename
                mime_content_type($tempFilePath), // Guess the MIME type
                null,
                true // Delete the file after it's used
            );
        
            //get file extension
            $fileExtension = $uploadedFile->getClientOriginalExtension();
            unlink($tempFilePath);
            $fileName = uniqid() . '.'.$fileExtension;

            Storage::disk('public')->put('files/' . $fileName, $binaryData);
            $publicUrl = Storage::disk('public')->url('files/' . $fileName);
            $project->file_path=$publicUrl;
            $project->save();
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }
    public function addProjectMembers(Request $request){
        try{
            $member=new StudentProject;
            $member->student_id=$request->student_id;
            $member->project_id=$request->project_id;
            $member->save();
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }

    public function modifyTaskGrade(Request $request){
        try{
            $submission_id=$request->submission_id;
            $grade=$request->grade;
            TaskSubmission::where([['id','=',$submission_id]])->update(['grade' => $grade]);
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }

    public function modifyProjectGrade(Request $request){
        try{
            $project_id=$request->project_id;
            $grade=$request->grade;
            GroupProject::where([['id','=',$project_id]])->update(['grade' => $grade]);
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }

    public function addFeedback(Request $request){

        try{
            $feedback=new Feedback;
            $feedback->teacher_id=Auth::id();
            $feedback->student_id=$request->student_id;
            $feedback->course_id=$request->course_id;
            $feedback->rating=$request->rating;
            $feedback->comment=$request->comment;
            $feedback->save();
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }


    public function addNotification(Request $request){

        try{
            $notification=new Notification;
            $notification->course_id=$request->course_id;
            $notification->notification=$request->notification;
            $notification->save();
            return response()->json([
                'status' => '200',
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } 
    }    
}
