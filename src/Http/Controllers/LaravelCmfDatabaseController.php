<?php

namespace XADMIN\LaravelCmf\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use XADMIN\LaravelCmf\Database\DatabaseUpdater;
use XADMIN\LaravelCmf\Database\Schema\Column;
use XADMIN\LaravelCmf\Database\Schema\Identifier;
use XADMIN\LaravelCmf\Database\Schema\SchemaManager;
use XADMIN\LaravelCmf\Database\Schema\Table;
use XADMIN\LaravelCmf\Database\Types\Type;
use XADMIN\LaravelCmf\Events\TableAdded;
use XADMIN\LaravelCmf\Events\TableDeleted;
use XADMIN\LaravelCmf\Events\TableUpdated;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class LaravelCmfDatabaseController extends Controller
{
    public function index()
    {
        $this->authorize('browse_database');

        $dataTypes = LaravelCmf::model('DataType')->select('id', 'name', 'slug')->get()->keyBy('name')->toArray();

        $tables = array_map(function ($table) use ($dataTypes) {
            $table = [
                'name'       => $table,
                'slug'       => $dataTypes[$table]['slug'] ?? null,
                'dataTypeId' => $dataTypes[$table]['id'] ?? null,
            ];

            return (object) $table;
        }, SchemaManager::listTableNames());

        return LaravelCmf::view('laravel-cmf::tools.database.index')->with(compact('dataTypes', 'tables'));
    }

    /**
     * Create database table.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $this->authorize('browse_database');

        $db = $this->prepareDbManager('create');

        return LaravelCmf::view('laravel-cmf::tools.database.edit-add', compact('db'));
    }

    /**
     * Store new database table.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('browse_database');

        try {
            $conn = 'database.connections.'.config('database.default');
            Type::registerCustomPlatformTypes();

            $table = $request->table;
            if (!is_array($request->table)) {
                $table = json_decode($request->table, true);
            }
            $table['options']['collate'] = config($conn.'.collation', 'utf8mb4_unicode_ci');
            $table['options']['charset'] = config($conn.'.charset', 'utf8mb4');
            $table = Table::make($table);
            SchemaManager::createTable($table);

            if (isset($request->create_model) && $request->create_model == 'on') {
                $modelNamespace = config('laravel-cmf.models.namespace', app()->getNamespace());
                $params = [
                    'name' => $modelNamespace.Str::studly(Str::singular($table->name)),
                ];

                // if (in_array('deleted_at', $request->input('field.*'))) {
                //     $params['--softdelete'] = true;
                // }

                if (isset($request->create_migration) && $request->create_migration == 'on') {
                    $params['--migration'] = true;
                }

                Artisan::call('laravel-cmf:make:model', $params);
            } elseif (isset($request->create_migration) && $request->create_migration == 'on') {
                Artisan::call('make:migration', [
                    'name'    => 'create_'.$table->name.'_table',
                    '--table' => $table->name,
                ]);
            }

            event(new TableAdded($table));

            return redirect()
               ->route('laravel-cmf.database.index')
               ->with($this->alertSuccess(__('laravel-cmf::database.success_create_table', ['table' => $table->name])));
        } catch (Exception $e) {
            return back()->with($this->alertException($e))->withInput();
        }
    }

    /**
     * Edit database table.
     *
     * @param string $table
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($table)
    {
        $this->authorize('browse_database');

        if (!SchemaManager::tableExists($table)) {
            return redirect()
                ->route('laravel-cmf.database.index')
                ->with($this->alertError(__('laravel-cmf::database.edit_table_not_exist')));
        }

        $db = $this->prepareDbManager('update', $table);

        return LaravelCmf::view('laravel-cmf::tools.database.edit-add', compact('db'));
    }

    /**
     * Update database table.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->authorize('browse_database');

        $table = json_decode($request->table, true);

        try {
            DatabaseUpdater::update($table);
            // TODO: synch BREAD with Table
            // $this->cleanOldAndCreateNew($request->original_name, $request->name);
            event(new TableUpdated($table));
        } catch (Exception $e) {
            return back()->with($this->alertException($e))->withInput();
        }

        return redirect()
               ->route('laravel-cmf.database.index')
               ->with($this->alertSuccess(__('laravel-cmf::database.success_create_table', ['table' => $table['name']])));
    }

    protected function prepareDbManager($action, $table = '')
    {
        $db = new \stdClass();

        // Need to get the types first to register custom types
        $db->types = Type::getPlatformTypes();

        if ($action == 'update') {
            $db->table = SchemaManager::listTableDetails($table);
            $db->formAction = route('laravel-cmf.database.update', $table);
        } else {
            $db->table = new Table('New Table');

            // Add prefilled columns
            $db->table->addColumn('id', 'integer', [
                'unsigned'      => true,
                'notnull'       => true,
                'autoincrement' => true,
            ]);

            $db->table->setPrimaryKey(['id'], 'primary');

            $db->formAction = route('laravel-cmf.database.store');
        }

        $oldTable = old('table');
        $db->oldTable = $oldTable ? $oldTable : json_encode(null);
        $db->action = $action;
        $db->identifierRegex = Identifier::REGEX;
        $db->platform = SchemaManager::getDatabasePlatform()->getName();

        return $db;
    }

    public function cleanOldAndCreateNew($originalName, $tableName)
    {
        if (!empty($originalName) && $originalName != $tableName) {
            $dt = DB::table('data_types')->where('name', $originalName);
            if ($dt->get()) {
                $dt->delete();
            }

            $perm = DB::table('permissions')->where('table_name', $originalName);
            if ($perm->get()) {
                $perm->delete();
            }

            $params = ['name' => Str::studly(Str::singular($tableName))];
            Artisan::call('laravel-cmf:make:model', $params);
        }
    }

    public function reorder_column(Request $request)
    {
        $this->authorize('browse_database');

        if ($request->ajax()) {
            $table = $request->table;
            $column = $request->column;
            $after = $request->after;
            if ($after == null) {
                // SET COLUMN TO THE TOP
                DB::query("ALTER $table MyTable CHANGE COLUMN $column FIRST");
            }

            return 1;
        }

        return 0;
    }

    /**
     * Show table.
     *
     * @param string $table
     *
     * @return JSON
     */
    public function show($table)
    {
        $this->authorize('browse_database');

        $additional_attributes = [];
        $model_name = LaravelCmf::model('DataType')->where('name', $table)->pluck('model_name')->first();
        if (isset($model_name)) {
            $model = app($model_name);
            if (isset($model->additional_attributes)) {
                foreach ($model->additional_attributes as $attribute) {
                    $additional_attributes[$attribute] = [];
                }
            }
        }

        return response()->json(collect(SchemaManager::describeTable($table))->merge($additional_attributes));
    }

    /**
     * Destroy table.
     *
     * @param string $table
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($table)
    {
        $this->authorize('browse_database');

        try {
            SchemaManager::dropTable($table);
            event(new TableDeleted($table));

            return redirect()
                ->route('laravel-cmf.database.index')
                ->with($this->alertSuccess(__('laravel-cmf::database.success_delete_table', ['table' => $table])));
        } catch (Exception $e) {
            return back()->with($this->alertException($e));
        }
    }
}
