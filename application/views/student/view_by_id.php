<?php  $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#roll").autocomplete({
            source: function(request, response) {
               $.ajax({
		    url: base_url + "student/roll_load",
		    data: {
		        parameter: request.term,
		        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(data) {
		        response(data);
		    }
		});

            },
            select: function(event, ui) {
                $('#roll').val(ui.item.label);
                return false;
            }
        });
    });
</script>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
				
				<?php endif; ?>
					
					<div class="col-md-4 mb-sm">
						<div class="form-group">
							<label class="control-label">Student ID</label>
							<input placeholder="Type student ID.." type="text" name="roll" id="roll" class="form-control">
						</div>
					</div>
					
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="search" value="1" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>

		<?php if (isset($students)):?>
		<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
			<header class="panel-heading">
			<?php if (get_permission('student', 'is_delete')): ?>
				<div class="panel-btn">
					<button class="btn btn-default btn-circle" id="student_bulk_delete" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-trash-alt"></i> <?=translate('bulk_delete')?>
					</button>
				</div>
			<?php endif; ?>
				<h4 class="panel-title"><i class="fas fa-user-graduate"></i> <?php echo translate('student_list');?></h4>
			</header>
			<div class="panel-body mb-md">
				<table class="table table-bordered table-condensed table-hover table-export">
					<thead>
						<tr>
							<th width="10" class="no-sort">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
								</div>
							</th>
							<th class="no-sort"><?=translate('photo')?></th>
							<th><?=translate('name')?></th>
							<th>Batch</th>
					<th>Online/Offline</th>
							<th><?=translate('student_id')?></th>
						
							<th><?=translate('subject_name')?></th>
							
						<?php
						$show_custom_fields = custom_form_table('student', $branch_id);
						if (count($show_custom_fields)) {
							foreach ($show_custom_fields as $fields) {
						?>
							<th><?=$fields['field_label']?></th>
						<?php } } ?>
							<th class="no-sort"><?=translate('fees_progress')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach($students as $row):
							$fee_progress = $this->student_model->getFeeProgress($row['student_id']);
						?>
						<tr>
							<td class="checked-area">
								<div class="checkbox-replace">
									<label class="i-checks">
										<input type="checkbox" class="cb_bulkdelete" id="<?=$row['student_id']?>"><i></i>
									</label>
								</div>
							</td>
							<td class="center"><img src="<?php echo get_image_url('student', $row['photo']); ?>" height="50"></td>
							<td ><?php echo $row['fullname'];?></td>
							<td><?php echo $row['class_name'];?></td>
							<td><?php echo $row['is_online_offline'];?></td>
							<td><?php echo $row['roll'];?></td>
							<td><?php echo $row['subject_name'];?></td>
							
							
						
							<td>
								<div class="progress progress-xl m-none prb-mw">
									<div class="progress-bar text-dark" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=$fee_progress?>%;"><?=$fee_progress?>%</div>
								</div>
							</td>
							<td class="action">
								<!-- quick view -->
								<a href="javascript:void(0);" onclick="studentQuickView('<?=$row['id']?>');" class="btn btn-default btn-circle icon" data-toggle="tooltip"
								data-original-title="<?=translate('quick_view')?>">
									<i class="fas fa-qrcode"></i>
								</a>
							<?php if (get_permission('student', 'is_edit')): ?>
								<!-- update link -->
								<a href="<?php echo base_url('student/profile/' . $row['student_id']);?>" class="btn btn-default btn-circle icon" data-toggle="tooltip"
								data-original-title="<?=translate('details')?>">
									<i class="far fa-arrow-alt-circle-right"></i>
								</a>
							<?php endif; if (get_permission('student', 'is_delete')): ?>
								<!-- delete link -->
								<?php echo btn_delete('student/delete_data/' . $row['id'] . '/' . $row['student_id']);?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</section>
		<?php endif;?>
	</div>
</div>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="quickView">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-user-circle"></i> <?=translate('quick_view')?>
			</h4>
		</header>
		<div class="panel-body">
			<div class="quick_image">
				<img alt="" class="user-img-circle" id="quick_image" src="<?=base_url('uploads/app_image/defualt.png')?>" width="120" height="120">
			</div>
			<div class="text-center">
				<h4 class="text-weight-semibold mb-xs" id="quick_full_name"></h4>
				<p><?=translate('student')?> / <span id="quick_category"></p>
			</div>
			<div class="table-responsive mt-md mb-md">
				<table class="table table-striped table-bordered table-condensed mb-none">
					<tbody>
						<tr>
							<th><?=translate('register_no')?></th>
							<td><span id="quick_register_no"></span></td>
							<th><?=translate('roll')?></th>
							<td><span id="quick_roll"></span></td>
						</tr>
						<tr>
							<th><?=translate('admission_date')?></th>
							<td><span id="quick_admission_date"></span></td>
							<th><?=translate('date_of_birth')?></th>
							<td><span id="quick_date_of_birth"></span></td>
						</tr>
						<tr>
							<th><?=translate('blood_group')?></th>
							<td><span id="quick_blood_group"></span></td>
							<th><?=translate('religion')?></th>
							<td><span id="quick_religion"></span></td>
						</tr>
						<tr>
							<th><?=translate('email')?></th>
							<td colspan="3"><span id="quick_email"></span></td>
						</tr>
						<tr>
							<th><?=translate('mobile_no')?></th>
							<td><span id="quick_mobile_no"></span></td>
							<th><?=translate('state')?></th>
							<td><span id="quick_state"></span></td>
						</tr>
						<tr class="quick-address">
							<th><?=translate('address')?></th>
							<td colspan="3" height="80px;"><span id="quick_address"></span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss"><?=translate('close')?></button>
				</div>
			</div>
		</footer>
	</section>
</div>
<?php if (get_permission('student', 'is_delete')): ?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#student_bulk_delete').on('click', function() {
			var btn = $(this);
			var arrayID = [];
			$("input[type='checkbox'].cb_bulkdelete").each(function (index) {
				if(this.checked) {
					arrayID.push($(this).attr('id'));
				}
			});
			if (arrayID.length != 0) {
				swal({
					title: "<?php echo translate('are_you_sure')?>",
					text: "<?php echo translate('delete_this_information')?>",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn btn-default swal2-btn-default",
					cancelButtonClass: "btn btn-default swal2-btn-default",
					confirmButtonText: "<?php echo translate('yes_continue')?>",
					cancelButtonText: "<?php echo translate('cancel')?>",
					buttonsStyling: false,
					footer: "<?php echo translate('deleted_note')?>"
				}).then((result) => {
					if (result.value) {
						$.ajax({
							url: base_url + "student/bulk_delete",
							type: "POST",
							dataType: "JSON",
							data: { array_id : arrayID },
							success:function(data) {
								swal({
								title: "<?php echo translate('deleted')?>",
								text: data.message,
								buttonsStyling: false,
								showCloseButton: true,
								focusConfirm: false,
								confirmButtonClass: "btn btn-default swal2-btn-default",
								type: data.status
								}).then((result) => {
									if (result.value) {
										location.reload();
									}
								});
							}
						});
					}
				});
			}
		});
	});
</script>
<?php endif; ?>
 <script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>assets/js/autocomplete_js.js"></script>