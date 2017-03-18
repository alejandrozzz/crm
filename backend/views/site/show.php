<?php use backend\models\Module; 
use backend\helpers\DupaHelper;?>
<div id="page-content" class="profile2">
	<?php if(isset($module['is_gen']) && $module['is_gen']) : ?>
	<div class="bg-success clearfix">
	<?php else :?>
	<div class="bg-danger clearfix">
	<?php endif; ?>
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-3">
					<!--<img class="profile-image" src="{{ asset('/img/avatar5.png') }}" alt="">-->
					<div class="profile-icon text-primary"><i class="fa <?php $module['fa_icon'] ?>"></i></div>
				</div>
				<div class="col-md-9">
					<a class="text-white" href=""><h4 data-toggle="tooltip" data-placement="left" title="Open <?php $module['model'] ?> Module" class="name"><?php $module['label'];?></h4></a>
					<div class="row stats">
						<div class="col-md-12"><?php Module::itemCount($module['name']) ?> Items</div>
					</div>
					<p class="desc"><?php if(isset($module['is_gen']) && $module['is_gen']) : ?> <div class="label2 success">Module Generated</div> <?php else : ?> <div class="label2 danger" style="border:solid 1px #FFF;">Module not Generated</div> <?php endif; ?></p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="dats1" data-toggle="tooltip" data-placement="left" title="Controller"><i class="fa fa-anchor"></i> <?php $module['controller'] ?></div>
			<div class="dats1" data-toggle="tooltip" data-placement="left" title="Model"><i class="fa fa-database"></i> <?php $module['model'] ?></div>
			<div class="dats1" data-toggle="tooltip" data-placement="left" title="View Column Name"><i class="fa fa-eye"></i>
				<?php if($module['view_col']!="") : ?>
					<?php $module['view_col'] ?>
				<?php else : ?>
 					Not Set
				<?php endif?>
			</div>
		</div>
		
		<div class="col-md-4">
			<?php if($module['view_col'] != "") : ?>
				<?php if(isset($module['is_gen']) && $module['is_gen']) : ?>
					<div class="dats1 text-center"><a data-toggle="tooltip" data-placement="left" title="Update Module" class="btn btn-sm btn-success" style="border-color:#FFF;" id="generate_update" href="#"><i class="fa fa-refresh"></i> Update Module</a></div>
					<div class="dats1 text-center"><a data-toggle="tooltip" data-placement="left" title="Update Migration File" class="btn btn-sm btn-success" style="border-color:#FFF;" id="update_migr" href="#"><i class="fa fa-database"></i> Update Migration</a></div>
				<?php else : ?>
					<div class="dats1 text-center"><a data-toggle="tooltip" data-placement="left" title="Generate Migration + CRUD + Module" class="btn btn-sm btn-success" style="border-color:#FFF;" id="generate_migr_crud" href="#"><i class="fa fa-cube"></i> Generate Migration + CRUD</a></div>
					
 					<div class="dats1 text-center"><a data-toggle="tooltip" data-placement="left" title="Generate Migration File" class="btn btn-sm btn-success" style="border-color:#FFF;" id="generate_migr" href="#"><i class="fa fa-database"></i> Generate Migration</a></div>
				<?php endif; ?>
			<?php else : ?>
				<div class="dats1 text-center">To generate Migration or CRUD, set the view column using the <i class='fa fa-eye'></i> icon next to a column</div>
			<?php endif; ?>
		</div>
		
		<div class="col-md-1 actions">
			<button module_name="{{ $module->name }}" module_id="{{ $module->id }}" class="btn btn-default btn-delete btn-xs delete_module"><i class="fa fa-times"></i></button>
		</div>
	</div>

	<ul id="module-tabs" data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/modules') }}" data-toggle="tooltip" data-placement="right" title="Back to Modules"> <i class="fa fa-chevron-left"></i>&nbsp;</a></li>
		
		<li class="tab-pane" id="fields">
			<a id="tab_fields" role="tab" data-toggle="tab" class="tab_info" href="#fields" data-target="#tab-info"><i class="fa fa-bars"></i> Module Fields</a>
		</li>
		
		<li class="tab-pane" id="access">
			<a id="tab_access" role="tab" data-toggle="tab"  class="tab_info " href="#access" data-target="#tab-access"><i class="fa fa-key"></i> Access</a>
		</li>
		
		<li class="tab-pane" id="sort">
			<a id="tab_sort" role="tab" data-toggle="tab"  class="tab_info " href="#sort" data-target="#tab-sort"><i class="fa fa-sort"></i> Sort</a>
		</li>
		
		<a data-toggle="modal" data-target="#AddFieldModal" class="btn btn-success btn-sm pull-right btn-add-field" style="margin-top:10px;margin-right:10px;">Add Field</a>
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel">
					<!--<div class="panel-default panel-heading">
						<h4>Module Fields</h4>
					</div>-->
					<div class="panel-body">
						<table id="dt_module_fields" class="table table-bordered" style="width:100% !important;">
						<thead>
						<tr class="success">
							<th style="display:none;"></th>
							<th>#</th>
							<th>Label</th>
							<th>Column</th>
							<th>Type</th>
							<th>Unique</th>
							<th>Default</th>
							<th>Min</th>
							<th>Max</th>
							<th>Required</th>
							<th>Listing</th>
							<th style="max-width:300px;">Values</th>
							<th style="min-width:60px;"><i class="fa fa-cogs"></i></th>
						</tr>
						</thead>
						<tbody>														
							<?php foreach ($module['fields'] as $field) : ?>
								<tr>
									<td style="display:none;">{{ $field['sort'] }}</td>
									<td><?php $field['id'] ?></td>
									<td><?php $field['label'] ?></td>
									<td><?php $field['colname'] ?></td>
									<td><?php $ftypes[$field['field_type']] ?></td>
									<td><?php if($field['unique']) : ?> <span class="text-danger">True</span><?php endif; ?> </td>
									<td><?php $field['defaultvalue'] ?></td>
									<td><?php $field['minlength'] ?></td>
									<td><?php $field['maxlength'] ?></td>
									<td><?php if($field['required']) : ?> <span class="text-danger">True</span><?php endif; ?> </td>
									<td>
										<form id="listing_view_cal" action="{{ url(config('laraadmin.adminRoute') . '/module_field_listing_show') }}">
											<input name="ref_{!! $field['id'] !!}" type="checkbox" @if($field['listing_col'] == 1) checked="checked" @endif>
											<div class="Switch Ajax Round @if($field['listing_col'] == 1) On @else Off @endif" listid="{{ $field['id'] }}">
												<div class="Toggle"></div>
											</div>
										</form>
									</td>
									<td style="max-width:300px;"><?php echo DupaHelper::parseValues($field['popup_vals']) ?></td>
									<td style="min-width:60px;">
										<a href="{{ url(config('laraadmin.adminRoute') . '/module_fields/'.$field['id'].'/edit') }}" class="btn btn-edit-field btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="edit_{{ $field['colname'] }}"><i class="fa fa-edit"></i></a>
										<a href="{{ url(config('laraadmin.adminRoute') . '/module_fields/'.$field['id'].'/delete') }}" class="btn btn-edit-field btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="delete_{{ $field['colname'] }}"><i class="fa fa-trash"></i></a>
										<?php if($field['colname'] != $module['view_col']) : ?>
											<a href="{{ url(config('laraadmin.adminRoute') . '/modules/'.$module->id.'/set_view_col/'.$field['colname']) }}" class="btn btn-edit-field btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="view_col_{{ $field['colname'] }}"><i class="fa fa-eye"></i></a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-access">
			<div class="guide1">
				<span class="pull-left">Module Access for Roles</span>
				<i class="fa fa-circle gray"></i> Invisible <i class="fa fa-circle orange"></i> Read-Only <i class="fa fa-circle green"></i> Write
			</div>
			<form action="{{ url(config('laraadmin.adminRoute') . '/save_role_module_permissions/'.$module->id) }}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<table class="table table-bordered dataTable no-footer table-access">
					<thead>
						<tr class="blockHeader">
							<th width="14%">
								<input class="alignTop" type="checkbox" id="role_select_all" >&nbsp; Roles
							</th>
							<th width="14%">
								<input type="checkbox" id="view_all" >&nbsp; View
							</th>
							<th width="14%">
								<input type="checkbox" id="create_all" >&nbsp; Create
							</th>
							<th width="14%">
								<input type="checkbox" id="edit_all" >&nbsp; Edit
							</th>
							<th width="14%">
								<input class="alignTop" type="checkbox" id="delete_all" >&nbsp; Delete
							</th>
							<th width="14%">Field Privileges</th>
						</tr>
					</thead>
					<?php foreach($roles as $role) : ?>
						<tr class="tr-access-basic" role_id="<?php $role['id'] ?>">
							<td><input class="role_checkb" type="checkbox" name="module_{{ $role->id }}" id="module_{{ $role->id }}" checked="checked"> {{ $role->name }}</td>
							
							<td><input class="view_checkb" type="checkbox" name="module_view_{{$role->id}}" id="module_view_{{$role->id}}" <?php if($role->view == 1) { echo 'checked="checked"'; } ?> ></td>
							<td><input class="create_checkb" type="checkbox" name="module_create_{{$role->id}}" id="module_create_{{$role->id}}" <?php if($role->create == 1) { echo 'checked="checked"'; } ?> ></td>
							<td><input class="edit_checkb" type="checkbox" name="module_edit_{{$role->id}}" id="module_edit_{{$role->id}}" <?php if($role->edit == 1) { echo 'checked="checked"'; } ?> ></td>
							<td><input class="delete_checkb" type="checkbox" name="module_delete_{{$role->id}}" id="module_delete_{{$role->id}}" <?php if($role->delete == 1) { echo 'checked="checked"'; } ?> ></td>
							<td>
								<a role_id="{{ $role->id }}" class="toggle-adv-access btn btn-default btn-sm hide_row"><i class="fa fa-chevron-down"></i></a>
							</td>
						</tr>
						<tr class="tr-access-adv module_fields_{{ $role->id }} hide" role_id="{{ $role->id }}" >
							<td colspan=6>
								<table class="table table-bordered">
								<?php foreach (array_chunk($module->fields, 3, true) as $fields) : ?>
									<tr>
										<?php foreach ($fields as $field) : ?>
											<td><div class="col-md-3"><input type="text" name="{{ $field['colname'] }}_{{ $role->id }}" value="{{ $role->fields[$field['id']]['access'] }}" data-slider-value="{{ $role->fields[$field['id']]['access'] }}" class="slider form-control" data-slider-min="0" data-slider-max="2" data-slider-step="1" data-slider-orientation="horizontal"  data-slider-id="{{ $field['colname'] }}_{{ $role->id }}"></div> {{ $field['label'] }} </td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach;?>
								</table>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				<center><input class="btn btn-success" type="submit" name="Save"></center>
			</form>
		<!--<div class="text-center p30"><i class="fa fa-list-alt" style="font-size: 100px;"></i> <br> No posts to show</div>-->
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-sort">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3">
					<ul id="sortable_module_fields">
						@foreach ($module->fields as $field)
							<li class="ui-field" field_id="{{ $field['id'] }}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{ $field['label'] }}
								@if($field['colname'] == $module->view_col)
									<i class="fa fa-eye pull-right" style="margin-top:3px;"></i>
								@endif
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
<div class="modal fade" id="AddFieldModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add {{ $module->model }} Field</h4>
			</div>
			{!! Form::open(['route' => config('laraadmin.adminRoute') . '.module_fields.store', 'id' => 'field-form']) !!}
			{{ Form::hidden("module_id", $module->id) }}
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="label">Field Label :</label>
						{{ Form::text("label", null, ['class'=>'form-control', 'placeholder'=>'Field Label', 'data-rule-minlength' => 2, 'data-rule-maxlength'=>20, 'required' => 'required']) }}
					</div>
					
					<div class="form-group">
						<label for="colname">Column Name :</label>
						{{ Form::text("colname", null, ['class'=>'form-control', 'placeholder'=>'Column Name (lowercase)', 'data-rule-minlength' => 2, 'data-rule-maxlength'=>20, 'data-rule-banned-words' => 'true', 'required' => 'required']) }}
					</div>
					
					<div class="form-group">
						<label for="field_type">UI Type:</label>
						{{ Form::select("field_type", $ftypes, null, ['class'=>'form-control', 'required' => 'required']) }}
					</div>
					
					<div id="unique_val">
						<div class="form-group">
							<label for="unique">Unique:</label>
							{{ Form::checkbox("unique", "unique", false, []) }}
							<div class="Switch Round Off" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div>
						</div>
					</div>	
					
					<div id="default_val">
						<div class="form-group">
							<label for="defaultvalue">Default Value :</label>
							{{ Form::text("defaultvalue", null, ['class'=>'form-control', 'placeholder'=>'Default Value']) }}
						</div>
					</div>

					<div id="length_div">
						<div class="form-group">
							<label for="minlength">Minimum :</label>
							{{ Form::number("minlength", null, ['class'=>'form-control', 'placeholder'=>'Minimum Value']) }}
						</div>
						
						<div class="form-group">
							<label for="maxlength">Maximum :</label>
							{{ Form::number("maxlength", null, ['class'=>'form-control', 'placeholder'=>'Maximum Value']) }}
						</div>
					</div>
					
					<div class="form-group">
						<label for="required">Required:</label>
						{{ Form::checkbox("required", "required", false, []) }}
						<div class="Switch Round Off" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div>
					</div>
					
					<div class="form-group">
						<label for="listing_col">Show in Index Listing:</label>
						{{ Form::checkbox("listing_col", "listing_col", false, []) }}
						<div class="Switch Round Off" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div>
					</div>
					<!--
					<div class="form-group">
						<label for="popup_vals">Values :</label>
						{{-- Form::text("popup_vals", null, ['class'=>'form-control', 'placeholder'=>'Popup Values (Only for Radio, Dropdown, Multiselect, Taginput)']) --}}
					</div>
					-->
					
					<div class="form-group values">
						<label for="popup_vals">Values :</label>
						<div class="radio" style="margin-bottom:20px;">
							<label>{{ Form::radio("popup_value_type", "table", true) }} From Table</label>
							<label>{{ Form::radio("popup_value_type", "list", false) }} From List</label>
						</div>
						{{ Form::select("popup_vals_table", $tables, "", ['id'=>'popup_vals_table', 'class'=>'form-control', 'rel' => '']) }}
						
						<select id="popup_vals_list" class="form-control popup_vals_list" rel="taginput" multiple="1" data-placeholder="Add Multiple values (Press Enter to add)" name="popup_vals_list[]">
							@if(env('APP_ENV') == "testing")
								<option>Bloomsbury</option>
								<option>Marvel</option>
								<option>Universal</option>
							@endif
						</select>
					</div>
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>