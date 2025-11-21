<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        
        $fixedYear = 2025;
        $date = request()->query('date');
        
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->setYear($fixedYear)->toDateString();
        } else {
            $date = \Carbon\Carbon::now()->setYear($fixedYear)->toDateString();
        }

        $selectedImage = request()->query('image');
        $imageData = request()->query('image_data') ? json_decode(request()->query('image_data'), true) : null;

        $todos = Todo::where('user_id', auth()->id())
            ->whereDate('due_date', $date)
            ->get();

        return view('todo', [
            'todos' => $todos,
            'selectedDate' => $date,
            'selectedImage' => $selectedImage,
            'imageData' => $imageData,
            'fixedYear' => $fixedYear
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'image_data' => 'nullable|array'
        ]);

        $todo = new Todo();
        $todo->user_id = auth()->id();
        $todo->title = $validatedData['title'];
        $todo->due_date = $validatedData['due_date'];
        
        if ($request->has('image_data')) {
            $todo->image_data = $validatedData['image_data'];
        }
        
        $todo->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => '/todo?date=' . $validatedData['due_date']
            ]);
        }

        return redirect()->route('todo.index', ['date' => $validatedData['due_date']]);
    }

    public function update(Request $request, string $id)
    {
        $todo = Todo::findOrFail($id);
        
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'completed' => 'sometimes|required|boolean'
        ]);

        $todo->update($validatedData);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request, string $id)
    {
        $todo = Todo::findOrFail($id);
        
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'image' => 'required|image|max:5120' // max 5MB
        ]);

        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $fileName);

        $imageData = [
            'url' => '/uploads/' . $fileName,
            'alt' => $file->getClientOriginalName(),
            'photographer' => 'Kullanıcı Yüklemesi'
        ];

        $todo->update([
            'image_data' => $imageData
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $todo = Todo::findOrFail($id);
        
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $todo->delete();
        return response()->json(['success' => true]);
    }
} 