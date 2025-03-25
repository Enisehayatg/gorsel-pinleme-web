<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

trait Controller
{
    protected $model;

    protected $controllerName;

    protected $validationRules = [];

    protected $compact = [];

    protected $dataTableColumns = [];

    public function index()
    {
        try {
            $this->compact['records'] = $this->model::all();

            return $this->fly("{$this->controllerName}.index");
        } catch (\Exception $e) {
            Log::error('Failed to retrieve records: '.$e->getMessage());

            return $this->fly("{$this->controllerName}.index", [], 'Failed to retrieve records. Please try again later.', true);
        }
    }

    public function create()
    {
        return $this->fly("{$this->controllerName}.create");
    }

    public function store(Request $request)
    {

        foreach ($request->all() as $field => $rules) {

            if (strpos($rules, 'image') !== false || strpos($rules, 'file') !== false) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store("{$this->controllerName}", 'public');
                    $validated[$field] = $path;
                }
            }
        }

        DB::beginTransaction();
        try {

            $this->model::create($request->all());
            DB::commit();

            return $this->flyWithNotification("{$this->controllerName}.index", 'Kaynak başarıyla oluşturuldu.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create resource: '.$e->getMessage());

            return $this->flyWithNotification("{$this->controllerName}.create", 'Kaynak oluşturulamadı. Lütfen daha sonra tekrar deneyin.', 'error');
        }
    }

    public function show(string $id)
    {
        try {
            $this->compact['record'] = $this->model::findOrFail($id);

            if (! $this->compact['record']) {
                return $this->flyWithNotification("{$this->controllerName}.index", 'Resource not found.', 'error');
            }

            return $this->fly("{$this->controllerName}.show");
        } catch (\Exception $e) {
            Log::error('Failed to retrieve resource: '.$e->getMessage());

            return $this->flyWithNotification("{$this->controllerName}.index", 'Failed to retrieve resource. Please try again later.', 'error');
        }
    }

    public function edit(string $id)
    {
        try {
            $this->compact['record'] = $this->model::findOrFail($id);

            if (! $this->compact['record']) {
                return $this->flyWithNotification("{$this->controllerName}.index", 'Resource not found.', 'error');
            }

            return $this->fly("{$this->controllerName}.edit");
        } catch (\Exception $e) {
            Log::error('Failed to retrieve resource for editing: '.$e->getMessage());

            return $this->flyWithNotification("{$this->controllerName}.index", 'Failed to retrieve resource. Please try again later.', 'error');
        }
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate($this->validationRules());

        foreach ($this->validationRules as $field => $rules) {
            if (strpos($rules, 'image') !== false || strpos($rules, 'file') !== false) {
                if ($request->hasFile($field)) {
                    //                    $record = $this->model::findOrFail($id);
                    //                    if ($record->$field) {
                    //                        Storage::delete($record->$field);
                    //                    }

                    $path = $request->file($field)->store("{$this->controllerName}", 'public');
                    $validated[$field] = $path;
                } else {
                    unset($validated[$field]);
                }
            }
        }

        DB::beginTransaction();
        try {
            $record = $this->model::findOrFail($id);
            $record->update($validated);

            DB::commit();

            return $this->flyWithNotification("{$this->controllerName}.index", 'Kaynak başarıyla güncellendi.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update resource: '.$e->getMessage());

            return $this->flyWithNotification("{$this->controllerName}.edit", 'Kaynak güncellenemedi. Lütfen daha sonra tekrar deneyin.', 'error', ['id' => $id]);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $record = $this->model::findOrFail($id);

            $record->delete();

            DB::commit();

            return $this->flyWithNotification("{$this->controllerName}.index", 'Kaynak başarıyla silindi.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete resource: '.$e->getMessage());

            return $this->flyWithNotification("{$this->controllerName}.index", 'Kaynak silinemedi. Lütfen daha sonra tekrar deneyin.', 'error');
        }
    }

    protected function validationRules(): array
    {
        return $this->validationRules;
    }

    public function fetch(Request $request)
    {
        try {
            $query = $this->model::query();

            return $this->generateDataTable($query);
        } catch (\Exception $e) {
            Log::error('Failed to fetch resources: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to fetch resources. Please try again later.'], 500);
        }
    }

    protected function generateDataTable($query)
    {
        $dataTable = DataTables::of($query);

        foreach ($this->dataTableColumns as $column => $callback) {
            $dataTable->addColumn($column, function ($item) use ($callback) {
                try {
                    return $callback($item);
                } catch (\Exception $e) {
                    Log::error('Failed to process column'.$e->getMessage());

                    return '-';
                }
            });
        }

        $dataTable->addColumn('actions', function ($item) {
            try {
                return view('partials.actions', [
                    'item' => $item,
                    'controllerName' => $this->controllerName,
                ])->render();
            } catch (\Exception $e) {
                Log::error('Failed to render actions column: '.$e->getMessage());

                return '-';
            }
        });

        return $dataTable
            ->rawColumns(array_merge(array_keys($this->dataTableColumns), ['actions']))
            ->make(true);
    }

    protected function fly($view, $data = [])
    {
        return view($view, array_merge($this->compact, $data));
    }

    protected function flyWithNotification($route, $message, $type = 'success', $data = [])
    {
        if ($type === 'error') {
            return redirect()->route($route, $data)->with('error', $message);
        }

        return redirect()->route($route, $data)->with('success', $message);
    }
}
