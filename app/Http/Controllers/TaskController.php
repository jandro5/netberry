<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysqli;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        
        // By Eloquent
        // $tasks = Task::with('categories')->get();

        // Otra Forma más "manual", pero implica cambiar el blade, ya que obtengo un objeto plano y hay que hacer bucles para iterar por las categorias
        $tasks = DB::table('tasks')
                    ->leftJoin('category_task', 'category_task.task_id', '=', 'tasks.id')
                    ->leftJoin('categories', 'categories.id', '=', 'category_task.category_id')
                    ->select('tasks.id', 'tasks.description', 'categories.name as category')
                    ->whereNull('tasks.deleted_at')
                    ->whereNull('categories.deleted_at')
                    ->get();

        /*
        By mysqli. Devuelve array. Habría que transformar los datos para poder tratarlos como objetos o cambiar la sintaxis del blade
        */
        // $conn = new mysqli(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_DATABASE'),env('DB_PORT'));
        // if ($conn->connect_error) { die("Error: " . $conn->connect_error); }
        // $sql = "SELECT t.id id, t.description description, categories.name as category
        //         FROM tasks t 
        //             INNER JOIN category_task ct ON ct.task_id = t.id 
        //             INNER JOIN categories c ON c.id = ct.category_id 
        //         WHERE t.deleted_at IS NULL AND c.deleted_at IS NULL";
        // $tasks = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        // $conn->close();

        /* 
        Otra forma de consultarlo en una consulta directamente y a mayores los datos relacionados concatenados. Habría que hacer un split del campo categorias para poder iterarlas.
        */
        // $tasks = DB::select("SELECT 
        //                         id, description,  GROUP_CONCAT(category SEPARATOR ',') AS categories
        //                     FROM (
        //                         SELECT t.id ID, t.description description, c.name Category
        //                     FROM tasks t 
        //                         INNER JOIN category_task ct ON ct.task_id = t.id 
        //                         INNER JOIN categories c ON c.id = ct.category_id 
        //                     WHERE t.deleted_at IS NULL AND c.deleted_at IS NULL) as A
        //                     GROUP BY id, description;");
 
        return view('task', compact('categories', 'tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Task::$rules);

        $task = Task::create($request->all());

        $task->categories()->attach($request->categories);
        
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $task = Task::find($request->id);

        if ($task) {
            return $task->delete();
        } else {
            return response('', 500);
        }
    }
}
