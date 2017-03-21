<?php

use App\Models\NewModule;

class NewModuleController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the NewModule.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::getModule('NewModule');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('site/index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('NewModule'),
                'module' => $module
            ]);
        //} else {
       //     return redirect(config('laraadmin.adminRoute') . "/");
       // }
    }
    
    /**
     * Show the form for creating a new newmodule.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created newmodule in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("NewModule", "create")) {
            
            $rules = Module::validateRules("NewModule", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("NewModule", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.newmodule.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified newmodule.
     *
     * @param int $id newmodule ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("NewModule", "view")) {
            
            $newmodule = NewModule::find($id);
            if(isset($newmodule->id)) {
                $module = Module::get('NewModule');
                $module->row = $newmodule;
                
                return view('la.newmodule.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('newmodule', $newmodule);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("newmodule"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified newmodule.
     *
     * @param int $id newmodule ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("NewModule", "edit")) {
            $newmodule = NewModule::find($id);
            if(isset($newmodule->id)) {
                $module = Module::get('NewModule');
                
                $module->row = $newmodule;
                
                return view('la.newmodule.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('newmodule', $newmodule);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("newmodule"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified newmodule in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id newmodule ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("NewModule", "edit")) {
            
            $rules = Module::validateRules("NewModule", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("NewModule", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.newmodule.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified newmodule from storage.
     *
     * @param int $id newmodule ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("NewModule", "delete")) {
            NewModule::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.newmodule.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax(Request $request)
    {
        $module = Module::get('NewModule');
        $listing_cols = Module::getListingColumns('NewModule');
        
        $values = DB::table('newmodule')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('NewModule');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/newmodule/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("NewModule", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/newmodule/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("NewModule", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.newmodule.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
}