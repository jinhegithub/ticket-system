<?php

$ui->assign('_application_menu', 'support');
$ui->assign('_title', 'Tickets'.' - '. $config['CompanyName']);
$ui->assign('_st', $_L['Tickets']);
$user = User::_info();
$ui->assign('user', $user);

$action = route(2);

if (!has_access($user->roleid, 'support', 'view')) {
    permissionDenied();
}

switch ($action){


    case 'departments':

	    $app->emit('tickets/admin/departments');

        $ui->assign('xheader', Asset::css(array('modal')));

        $ui->assign('xfooter',
            Asset::js(array('modal', 'tickets/departments'))
        );

        $ds = ORM::for_table('sys_ticketdepartments')->order_by_asc('sorder')->find_array();

        $ui->assign('ds',$ds);

        view('tickets_departments');


        break;

    case 'departments_post':

        $msg = '';

        $dname = _post('department_name');
        $email = _post('email');
        //  $description = $_POST['description'];

        if($dname == ''){
            $msg .= 'Department Name is Required';
        }

        if($email != '' && filter_var($email, FILTER_VALIDATE_EMAIL) != true){

            $msg .= 'Invalid Email Address';

        }

        if($msg == ''){
            $d = ORM::for_table('sys_ticketdepartments')->create();
            $d->dname = $dname;
            $d->email = $email;
            $d->hidden = _post('hidden','0');
            $d->host = _post('host');
            $d->port = _post('port');
            $d->username = $email;
            $d->password = _post('password');
            $d->encryption = _post('encryption','no');
            $d->delete_after_import = _post('delete_after_import','0');
            $d->sorder = 1;
            $d->save();

            _msglog('s','Department Added Successfully');

            echo $d->id();
        }
        else{
            echo $msg;
        }

        break;

    case 'delete_department':

        $id = route(3);

        $id = str_replace('d','',$id);

        $d = ORM::for_table('sys_ticketdepartments')->find_one($id);

        if($d){

            $d->delete();

            r2(U.'tickets/admin/departments/','s','Deleted Successfully');

        }


        break;

    case 'edit_department':

        $id = route(3);

        $id = str_replace('e','',$id);

        $d = ORM::for_table('sys_ticketdepartments')->find_one($id);

        if($d){

            echo '<form id="edit_form"><div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Edit Department</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                   <div class="form-group">
                        <label for="department_name">Department Name</label>
                        <input type="text" name="department_name" class="form-control" id="department_name" value="'.$d->dname.'">
                    </div>

                    <div class="form-group">
                        <label for="email">Default Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="'.$d->email.'">
                    </div>

                    <div class="form-group">
                        <label for="host">IMAP Host</label>
                        <input type="text" class="form-control" id="host" name="host" value="'.$d->host.'">
                    </div>

                    <div class="form-group">
                        <label for="port">IMAP Port</label>
                        <input type="text" class="form-control" id="port" name="port" value="'.$d->port.'">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="'.$d->password.'">
                    </div>

                    <div class="form-group">
                        <label for="encryption">Encryption</label>
                        <label class="radio-inline">
                            <input type="radio" name="encryption" value="tls" '.(($d->encryption == 'tls')?'checked':"").'> TLS
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="encryption" value="ssl" '.(($d->encryption == 'ssl')?'checked':"").'> SSL
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="encryption" value="no" '.(($d->encryption == '')?'checked':"").'> No Encryption
                        </label>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"  name="hidden" id="hidden" value="1" '.(($d->hidden == '1')?'checked':"").'> Hide from client?
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="delete_after_import" id="delete_after_import" value="1" '.(($d->delete_after_import == '1')?'checked':"").'> Delete mail after import?
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <button class="btn btn-primary test_imap">Test IMAP Connection</button>
                
                </div>
            </div>
            <div class="modal-footer">
            <input type="hidden" name="edit_dep" id="edit_dep" value="'.$d->id.'">
            <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
            <button type="button" id="btn_modal_edit_action" class="btn btn-primary edit_submit">Save</button>
    
            </div></form>';

        }


        break;

    case 'departments_edit':

        $msg = '';

        $edit_dep = _post('edit_dep');

        $d = ORM::for_table('sys_ticketdepartments')->find_one($edit_dep);

        if($d){
            $dname = _post('department_name');
            $email = _post('email');


            if($dname == ''){
                $msg .= 'Department Name is Required';
            }

            if($email != '' && filter_var($email, FILTER_VALIDATE_EMAIL) != true){

                $msg .= 'Invalid Email Address';

            }

            if($msg == ''){
                $d->dname = $dname;

                $d->email = $email;
                $d->hidden = _post('hidden','0');
                $d->host = _post('host');
                $d->port = _post('port');
                $d->username = $email;
                $d->password = _post('password');
                $d->encryption = _post('encryption','no');
                $d->delete_after_import = _post('delete_after_import','0');
                $d->save();

                _msglog('s','Department Edited Successfully');

                echo $edit_dep;
            }
            else{
                echo $msg;
            }
        }
        else{
            echo $edit_dep.'dd';
        }


        break;

    case 'departments_reorder':

        $d = ORM::for_table('sys_ticketdepartments')->order_by_asc('sorder')->find_array();
        $ui->assign('ritem','Support Ticket Departments');
        $ui->assign('d',$d);
        $ui->assign('xheader', '
            <link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
            ');
        $ui->assign('display_name','dname');
        $ui->assign('xjq', Reorder::js('sys_ticketdepartments'));
        $ui->display('reorder.tpl');

        break;

    case 'predefined_replies':

        $ui->assign('xheader', Asset::css(array('modal','redactor/redactor')));

        $ui->assign('xfooter',
            Asset::js(array('modal','redactor/redactor.min'))
        );

        $ui->assign('replies',db_find_array('sys_canned_responses',array('id','title'),'asc:sorder'));

        view('tickets_predefined_replies');


        break;

    case 'predefined_replies_post':


        $data = ib_get_posted_data();

        $ret = Tickets::addPredefinedReply($data);

        if($ret['success'] == 'Yes'){
            echo $ret['id'];
        }
        else{
            echo $ret['msg'];
        }

        break;

    case 'predefined_replies_reorder':

        $d = ORM::for_table('sys_canned_responses')->order_by_asc('sorder')->find_array();
        $ui->assign('ritem','Predefined Replies');
        $ui->assign('d',$d);
        $ui->assign('xheader', '
            <link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
        ');
        $ui->assign('display_name','title');
        $ui->assign('xjq', Reorder::js('sys_canned_responses'));
        $ui->display('reorder.tpl');

        break;

    case 'predefined_replies_delete':

        $id = route(3);

        $id = str_replace('d','',$id);


        Tickets::deletePredefinedReply($id);

        r2(U.'tickets/admin/predefined_replies/','s','Deleted Successfully');

        break;

    case 'predefined_reply_edit':


        $id = route(3);


        $reply = TicketPredefinedReply::find($id);

        if($reply)
        {
            $ui->assign('xheader', Asset::css(array('modal','redactor/redactor')));

            $ui->assign('xfooter',
                Asset::js(array('modal','redactor/redactor.min'))
            );

            view('predefined_reply_edit',[
                'reply' => $reply
            ]);
        }

        break;

    case 'predefined_reply_edit_post':

        $id = _post('id');

        $reply = TicketPredefinedReply::find($id);

        $title = _post('title');

        $message = $_POST['message'];

        if($reply)
        {
            if($title == '' || $message == '')
            {
                r2(U.'tickets/admin/predefined_reply_edit/'.$id,'e',$_L['All Fields are Required']);
            }

            $reply->title = $title;
            $reply->message = $message;
            $reply->save();
            r2(U.'tickets/admin/predefined_reply_edit/'.$id,'s',$_L['Data Updated']);
        }

        break;

    case 'create':

        $app->emit('tickets/admin/create');

        if (isset($routes['3']) AND ($routes['3'] != '')) {
            $p_cid = $routes['3'];
            $p_d = ORM::for_table('crm_accounts')->find_one($p_cid);
            if ($p_d) {
                $ui->assign('p_cid', $p_cid);
            }
        } else {
            $ui->assign('p_cid', '');
        }

        $customers = ORM::for_table('crm_accounts')->select('id')->select('account')->select('company')->select('email')->order_by_desc('id')->find_array();
        $ui->assign('customers', $customers);

        $ui->assign('xheader', Asset::css(array('s2/css/select2.min','dropzone/dropzone','modal')));

        $ui->assign('xfooter',
            Asset::js(array('modal','dropzone/dropzone','s2/js/select2.min','s2/js/i18n/'.lan(),'tinymce/tinymce.min','js/editor','tickets/admin_open'))
        );

        $ui->assign('jsvar','var files = [];');

        $deps = ORM::for_table('sys_ticketdepartments')->order_by_asc('sorder')->find_array();

        $ui->assign('deps',$deps);

        view('tickets_admin_create',[

        ]);
        break;

    case 'upload_file':

        $uploader   =   new Uploader();
        $uploader->setDir('storage/tickets/');
        $uploader->sameName(false);
        $uploader->setExtensions(array('zip','jpg','jpeg','png','gif','pdf','doc','docx'));  //allowed extensions list//
        if($uploader->uploadFile('file')){   //txtFile is the filebrowse element name //
            $uploaded  =   $uploader->getUploadName(); //get uploaded file name, renames on upload//

            $file = $uploaded;
            $msg = 'Uploaded Successfully';
            $success = 'Yes';

        }else{//upload failed
            $file = '';
            $msg = $uploader->getMessage();
            $success = 'No';
        }

        $a = array(
            'success' => $success,
            'msg' =>$msg,
            'file' =>$file
        );

        header('Content-Type: application/json');

        echo json_encode($a);


        break;

    case 'add_post':


        header('Content-Type: application/json');
        $cid = _post('cid');

        if($cid == ''){
            echo json_encode(array(
                "success" => "No",
                "msg" => 'Please Select Customer'
            ));

            exit;
        }

        $tickets = new Tickets();

        $t = $tickets->create($cid,$user->id);

        echo json_encode($t);


        break;

    case 'view':

        $id = route(3);

        $tab = route(4)?:'details';

	    $app->emit('tickets/admin/view',[
		    'tid' => $id
	    ]);

        if(has_access($user->roleid,'sales','edit')){

            $can_edit_sales = true;

        }
        else{

            $can_edit_sales = false;

        }



        $d = ORM::for_table('sys_tickets')->find_one($id);


        if($d){

            if($d->ttotal == '')
            {
                $timeSpent = 0;

                $hh = '00';
                $mm = '00';
            }
            else{
                $timeSpent = strtotime($d->ttotal) - strtotime('TODAY');
                $timeSpent = (int) $timeSpent;

                $hhmmss = $d->ttotal;
                $hhmmss_split = explode(':',$hhmmss);
                $hh = $hhmmss_split[0];
                $mm = $hhmmss_split[1];
            }



            $ui->assign('_st', $_L['Tickets'].' #'.$d->tid);

            $ui->assign('d',$d);

            $c = ORM::for_table('crm_accounts')->find_one($d->userid);

            $ui->assign('c',$c);

            if($d->admin != '0'){
                $a = db_find_one('sys_users',$d->admin);
            }
            else{
                $a = false;
            }

            $ui->assign('a',$a);

            // find all replies for this ticket

            $replies = ORM::for_table('sys_ticketreplies')->where('tid',$d->id)->find_array();
            $ui->assign('replies',$replies);

            $departments = ORM::for_table('sys_ticketdepartments')->select('id')->select('dname')->find_array();

            $ui->assign('departments',$departments);

            $deps = array();
            $d_x = 0;
            foreach ($departments as $dep){
                $deps[$d_x]['value'] = $dep['id'];
                $deps[$d_x]['text'] = $dep['dname'];
                $d_x++;
            }


            $jed = json_encode($deps);

            $ads = ORM::for_table('sys_users')->select('id')->select('fullname')->where_not_equal('user_type', 'Reviewer')->find_array();
            $ui->assign('ads',$ads);

            $aas = array();
            $a_x = 0;
            foreach ($ads as $ad){
                $aas[$a_x]['value'] = $ad['id'];
                $aas[$a_x]['text'] = $ad['fullname'];
                $a_x++;
            }

            $jaa = json_encode($aas);

            $reviewers = ORM::for_table('sys_users')->select('id')->select('fullname')->where_not_equal('user_type', 'Employee')->find_array();
            $ui->assign('reviewers', $reviewers);

            $dd = ORM::for_table('sys_ticketdepartments')->select('dname')->find_one($d->did);

            if($dd){
                $department = $dd->dname;
            }
            else{
                $department = '';
            }

            $ui->assign('department',$department);

           
            $o_tickets = ORM::for_table('sys_tickets')->where('email',$d->email)->select('status')->select('subject')->select('urgency')->select('created_at')->select('id')->find_array();
            $ui->assign('o_tickets',$o_tickets);


            // check invoice exist for this ticket

            $invoice = Invoice::where('ticket_id',$d->id)->first();

            $predefined_replies = TicketPredefinedReply::orderBy('sorder','asc')
                ->select(['id','title'])->get();


            $upload_files = array();
            $download_files = array();
            $internal_files = array();

            $ticket_files = $d->attachments;
            if ($ticket_files) {
                $ticket_file_array = explode(',', $ticket_files);
                foreach ($ticket_file_array as $key => $tf) {
                    $t = explode('.', $tf);
                    if ($key != 0) {
                        $message = 'First Submitted File [' . $key . ']';
                    } else {
                        $message = 'First Submitted File';
                    }
                    $attachment_file = array(
                        "id" => $d['id'],
                        "userid" => $d['userid'],
                        "account" => $d['account'],
                        "created_at" => $d['created_at'],
                        'message' => $message,
                        "replied_by" => '',
                        "attachment" => $tf,
                        "file_mime_type" => $t[1]
                    );
                    $download_files[] = $attachment_file;
                }
            }

            foreach ($replies as $rep) {
                if ($rep['attachments'] != '') {
                    $attach_array = explode(',', $rep['attachments']);
                    foreach ($attach_array as $key => $a) {
                        $f = explode('.', $a);
                        if ($key != 0) {
                            $message = $rep['message'] . '[' . $key . ']';
                        } else {
                            $message = $rep['message'];
                        }
                        $attachment_file = array(
                            "id" => $rep['id'],
                            "userid" => $rep['userid'],
                            "account" => $rep['account'],
                            "created_at" => $rep['created_at'],
                            'message' => $message,
                            "replied_by" => $rep['replied_by'],
                            "attachment" => $a,
                            "file_mime_type" => $f[1]
                        );

                        if($rep['admin'] == 0 || $rep['reply_type'] == 'admin_attachement'){
                            $download_files[] = $attachment_file;
                        }elseif($rep['admin'] != 0 && $rep['reply_type'] == 'public' ){
                            $upload_files[] = $attachment_file;
                        }elseif($rep['admin'] != 0 && $rep['reply_type'] == 'internal' ){
                            $internal_files[] = $attachment_file;
                        }
                    }
                }
            }

            $ui->assign('upload_files', $upload_files);
            $ui->assign('download_files', $download_files);
            $ui->assign('internal_files', $internal_files);

            $attachment_path = APP_URL . '/storage/tickets/'.$d->tid.'/';
            $ui->assign('attachment_path', $attachment_path);


            // Task management Start

            $mdate = date('Y-m-d');
            $ui->assign('mdate', $mdate);

            $contacts = Contact::select(['id', 'account'])->get()->groupBy('id')->all();
            $tickets = Ticket::select(['id', 'tid'])->get()->groupBy('id')->all();

            $credential = $user['user_type'];

            // $tasks = ORM::for_table('sys_tasks')->select('title')->select('aid')->select('status')->select('id')->find_array();
            $tasks_not_started = ORM::for_table('sys_tasks')
                ->select('title')
                ->select('aid')
                ->select('cid')
                ->select('tid')
                ->select('priority')
                ->where('status', 'Not Started')
                ->where('tid', $id)
                ->select('id')
                ->select('created_at')
                ->select('due_date')
                ->select('created_by');
            if ($credential != 'Admin') {
                $tasks_not_started->where('aid', $user['id']);
            }
            $tasks_not_started_array = $tasks_not_started->order_by_desc('id')->find_array();
            $ui->assign('tasks_not_started', $tasks_not_started_array);
            // ==================================================================

            $tasks_in_progress = ORM::for_table('sys_tasks')
                ->select('title')
                ->select('aid')
                ->select('cid')
                ->select('tid')
                ->select('priority')
                ->where('status', 'In Progress')
                ->where('tid', $id)
                ->select('id')
                ->select('created_at')
                ->select('due_date')
                ->select('created_by');
            if ($credential != 'Admin') {
                $tasks_in_progress->where('aid', $user['id']);
            }
            $tasks_in_progress_array = $tasks_in_progress->order_by_desc('id')->find_array();
            $ui->assign('tasks_in_progress', $tasks_in_progress_array);
            // ==================================================================

            $tasks_completed = ORM::for_table('sys_tasks')
                ->select('title')
                ->select('aid')
                ->select('cid')
                ->select('tid')
                ->select('priority')
                ->where('status', 'Completed')
                ->where('tid', $id)
                ->select('id')
                ->select('created_at')
                ->select('due_date')
                ->select('created_by');
            if ($credential != 'Admin') {
                $tasks_completed->where('aid', $user['id']);
            }
            $tasks_completed_array = $tasks_completed->order_by_desc('id')->find_array();
            $ui->assign('tasks_completed', $tasks_completed_array);
            // ==================================================================

            $tasks_deferred = ORM::for_table('sys_tasks')
                ->select('title')
                ->select('aid')
                ->select('cid')
                ->select('tid')
                ->select('priority')
                ->where('status', 'Deferred')
                ->where('tid', $id)
                ->select('id')->select('created_at')
                ->select('due_date')
                ->select('created_by');
            if ($credential != 'Admin') {
                $tasks_deferred->where('aid', $user['id']);
            }
            $tasks_deferred_array = $tasks_deferred->order_by_desc('id')->find_array();

            $ui->assign('tasks_deferred', $tasks_deferred_array);
            // ==================================================================

            $tasks_waiting = ORM::for_table('sys_tasks')
                ->select('title')
                ->select('aid')
                ->select('cid')
                ->select('tid')
                ->select('priority')
                ->where('status', 'Waiting')
                ->where('tid', $id)
                ->select('id')
                ->select('created_at')
                ->select('due_date')
                ->select('created_by');
            if ($credential != 'Admin') {
                $tasks_waiting->where('aid', $user['id']);
            }
            $tasks_waiting_array = $tasks_waiting->order_by_desc('id')->find_array();
            $ui->assign('tasks_waiting', $tasks_waiting_array);
            // ==================================================================

            $tasks_proofreading = ORM::for_table('sys_tasks')
                ->select('title')
                ->select('aid')
                ->select('cid')
                ->select('tid')
                ->select('priority')
                ->where('status','Under Proofreading')
                ->where('tid', $id)
                ->select('id')
                ->select('created_at')
                ->select('due_date')
                ->select('created_by');
            if ($credential != 'Admin') {
                $tasks_proofreading->where('aid', $user['id']);
            }
            $tasks_proofreading_array = $tasks_proofreading->order_by_desc('id')->find_array();
            $ui->assign('tasks_proofreading', $tasks_proofreading_array);


            // Task management End


            $ui->assign('xjq', '
        
            $( ".mmnt" ).each(function() {
                    //   alert($( this ).html());
                    var ut = $( this ).html();
                    $( this ).html(moment.unix(ut).fromNow());
                });
        
            ');


            $lang_code = Ib_I18n::get_code($config['language']);

            $ui->assign('jsvar', '
            var tid = ' . $d->id . ';
            var departments = ' . $jed . ';
            var agents = ' . $jaa . ';
            var files = [];_L[\'are_you_sure\'] = \'' . $_L['are_you_sure'] . '\';
            var ib_lang = \'' . $lang_code . '\';
            var ib_rtl = false;
            var ib_calendar_first_day = 0;
            var ib_date_format_picker = \'' . ib_js_date_format($config['df'], 'picker') . '\';
            var ib_date_format_moment = \'' . ib_js_date_format($config['df']) . '\';
            ');


            $ui->assign('xheader', Asset::css(array('redactor/redactor', 'dropzone/dropzone', 'modal', 'select/select.min', 's2/css/select2.min', 'datetime', 'dragula/dragula', 'css/kanban', 'daterangepicker/daterangepicker')));
            $ui->assign('xfooter', Asset::js(array('redactor/redactor', 'modal', 'dropzone/dropzone', 'tinymce/tinymce.min', 'js/editor', 'select/select.min', 's2/js/select2.min', 's2/js/i18n/'.lan(), 'datetime', 'dragula/dragula', 'daterangepicker/daterangepicker')));


            view('tickets_admin_view',[
                'tab' => $tab,
                'invoice' => $invoice,
                'ticket' => $d,
                'timeSpent' => $timeSpent,
                'can_edit_sales' => $can_edit_sales,
                'predefined_replies' => $predefined_replies,
                'contacts' => $contacts,
                'tickets' => $tickets,
                'hh' => $hh,
                'mm' => $mm
            ]);



        }
        else{

            echo 'Ticket not found';

        }

        break;

    case 'imap_test':

        $host = _post('host');
        $port = _post('port');
        $username = _post('email');
        $password = _post('password');
        $enc = _post('encryption');

        $imap = imap_open('{'.$host.':'.$port.'/imap/'.$enc.'}INBOX', $username, $password);

        if($imap){

            echo 1;

        }
        else{
            echo imap_last_error();
        }

        break;

    case 'list':


        $admin_id = route(3)?:'';
        $ui->assign('admin_id', $admin_id);

        
        $ui->assign('xheader',Asset::css(array('popover/popover','select/select.min','s2/css/select2.min','dt/dt')));


        $ui->assign('xfooter',Asset::js(array('popover/popover','js/redirect','select/select.min','s2/js/select2.min','s2/js/i18n/'.lan(),'dt/dt'))
        );

        $ui->assign('jsvar', '_L[\'are_you_sure\'] = \''.$_L['are_you_sure'].'\';');

        view('tickets_admin_list');

        break;

    case 'add_reply':


        $tickets = new Tickets();

       // $cid = _post('cid');

        $t = $tickets->add_reply($user->id);


        header('Content-Type: application/json');

        echo json_encode($t);


        break;

    case 'make_public':

        $id = _post('id');

        $reply = ORM::for_table('sys_ticketreplies')->find_one($id);

        if($reply){
            $reply->reply_type = 'public';
            $reply->save();
        }

        echo $id;

        break;

    case 'note_review':

        $questions_list = [
            "Is the title suitable for its content?",
            "Is the abstract is informative, including main finding and significance?",
            "Is the introduction part contain sufficient?",
            "Is the introduction part Informative?",
            "Are the materials and methods clear?",
            "Are the materials and methods adequate?",
            "Are the materials and methods ethical?",
            "Are the results efficient?",
            "Are the results satisfactory with statistical analysis?",
            "Are the results well presented?",
            "Are the tables Satisfactory?",
            "Are the tables clear?",
            "Are the tables necessary?",
            "Are the tables adequate in number?",
            "Are the figures satisfactory?",
            "Are the figures clear/in good quality of art?",
            "Are the figures necessary?",
            "Are the figures adequate in number?",
            "Does the discussion part include other relevant studies?",
            "Are the references suitable?",
            "Are the references sufficient?",
            "Are the references up to date?",
            "Are the references adequate in number?",
            "Would you suggest reduction in any part of the manuscript?",
            "Would you suggest addition in any part of the manuscript?",
            "Is the quality of scientific language satisfactory?",
            "Is the acknowledgement included?",
            "Is the ethical consideration included?",
            "Is the funding information included?",
            "Is the conflict of interest expressed?",
        ];

        $general_remark = "General remarks and recommendations to Author:";
        $final_recommendation = "Final Recommendations:";
        $reason_rejection = "Reasons for Rejection:";

        $response_string = "<p style='font-weight: 600'>Reviewer Comments - (to be revised if response stands \"No\")</p>";
        $question_index = 0;

        $post_reviews = $_POST['questions'];

        foreach($questions_list as $index=>$q){
            $question_index++;
            $response_string .= "<p>".$question_index.". ".$q." <span style='font-weight: 600'>".$post_reviews[$index]."</span></p>";
        }

        // Textarea Posts
        $post_general_remark = _post('general_remark')?_post('general_remark'): "";
        $response_string .= "<p>".($question_index+1).". ".$general_remark." ".$post_general_remark."</p>";

        $post_final_recommendation = _post('final_recommendation')?_post('final_recommendation'): "";
        $response_string .= "<p>".($question_index+2).". ".$final_recommendation." ".$post_final_recommendation."</p>";
        
        $post_reason_rejection = _post('reason_rejection')?_post('reason_rejection'): "";
        $response_string .= "<p>".($question_index+3).". ".$reason_rejection." ".$post_reason_rejection."</p>";


        echo $response_string;

        break;

    case 'save_note':

        $tid = _post('tid');

        $notes = $_POST['notes'];

        $ticket = db_find_one('sys_tickets',$tid);

        if($ticket){
            $ticket->notes = $notes;
            $ticket->save();
        }


        break;

    case 'delete':


        $tid = route(3);
        $tid = str_replace('t','',$tid);

        $ticket = db_find_one('sys_tickets',$tid);

        if($ticket){

            $ticket->delete();


        }

        // delete all related reply

        $replies = ORM::for_table('sys_ticketreplies')->where('tid',$tid)->find_many();

        foreach ($replies as $reply){
            $reply->delete();
        }

        r2(U.'tickets/admin/list/','s',$_L['delete_successful']);

        break;

    case 'view_modal':


       view('tickets_admin_view_modal');



        break;

    case 'edit_modal':

        $tid = route(3);
        $tid = str_replace('et','',$tid);
        $tid = str_replace('er','',$tid);

        $type = route(4);

        if($type == 'reply'){

            $ui->assign('type','reply');

            $ticket = db_find_one('sys_ticketreplies',$tid);


        }
        else{

            $ui->assign('type','ticket');

            $ticket = db_find_one('sys_tickets',$tid);

        }


        if($ticket){

            $ui->assign('ticket',$ticket);

            view('tickets_admin_edit_modal');


        }


        break;

    case 'edit_modal_post':

        $tid = _post('tid');

        $type = _post('type');

        $message = $_POST['message'];

        if($type == 'reply'){
            $ticket = db_find_one('sys_ticketreplies',$tid);
        }
        else{
            $ticket = db_find_one('sys_tickets',$tid);

        }

        if($ticket){

            $ticket->message = $message;
            $ticket->save();

            echo '1';

        }
        else{
            echo 'Ticket Not Found';
        }


        break;

    case 'delete_reply':

        $tid = route(3);
        $tid = str_replace('dr','',$tid);

        $ticket = db_find_one('sys_ticketreplies',$tid);

        if($ticket){

            $t = $ticket->tid;

            $ticket->delete();
            r2(U.'tickets/admin/view/'.$t,'s',$_L['delete_successful']);

        }

        break;

    case 'json_list':


        $columns = array();

        $columns[] = 'id';
        // $columns[] = 'img';
        $columns[] = 'subject';
        $columns[] = 'account';
        $columns[] = 'admin';
        $columns[] = 'assigned_at';
        $columns[] = 'status';
        $columns[] = 'tasks';
        $columns[] = 'created_at';


        $order_by = $_POST['order'];
        $o_c_id = $order_by[0]['column'];
        $o_type = $order_by[0]['dir'];

        $a_order_by = $columns[$o_c_id];


        $d = ORM::for_table('sys_tickets');

        $d->select('id');
        $d->select('tid');
        $d->select('userid');
        $d->select('account');
        $d->select('subject');
        $d->select('status');
        $d->select('aid');
        $d->select('assigned_at');
        $d->select('created_at');


        $staffs = User::all()->keyBy('id')->all();

        $tid = _post('id');
        if($tid != ''){
            $d->where('tid', $tid);
        }


        $account = _post('account');

        if($account != ''){

            $d->where_like('account',"%$account%");

        }


        $email = _post('email');

        if($email != ''){

            $d->where_like('email',"%$email%");

        }


        $subject = _post('subject');

        if($subject != ''){

            $d->where_like('subject',"%$subject%");

        }


        $company = _post('company');

        if($company != ''){

            $d->where_like('company',"%$company%");

        }

        $status = _post('status');
        
        if($status != ''){

            $d->where_like('status',"%$status%");

        }

        $activation = _post('activation');

        if($activation != ''){

            $d->where_like('activation', "%$activation%");

        }


        $admin_id = _post('admin_id');

        if($admin_id != ''){
            
            $d->where('admin', $admin_id);

        }
        else{
//            if (!has_access($user->roleid, 'transactions', 'all_data')) {
//                $d->where('aid', $user->id);
//            }
            if($user->user_type == 'Employee'){
                $d->where('aid', $user->id);
            }elseif($user->user_type == 'Reviewer'){
                $d->where('rid', $user->id);
            }
        }



        $x = $d->find_array();



        $iTotalRecords =  $d->count();

        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;


        if($o_type == 'desc'){
            $d->order_by_desc($a_order_by);
        }
        else{
            $d->order_by_asc($a_order_by);
        }


        

        $d->limit($iDisplayLength);
        $d->offset($iDisplayStart);
        $x = $d->find_array();

        $i = $iDisplayStart;

        $colors = Colors::colorNames();



        foreach ($x as $xs){

            $full_name = $xs['account'];

            $css_bg = $colors[array_rand($colors)];



            $full_name_e = explode(' ',$full_name);

            $first_name = $full_name_e[0];

            $first_name_letter = $first_name[0];

            if(isset($full_name_e[1])){
                $last_name = $full_name_e[1];
                $last_name_letter = $last_name[0];
            }
            else{
                $last_name_letter = '';
            }

            $img = '<span class="ib_avatar ib_bg_'.$css_bg.'">'.$first_name_letter.$last_name_letter.'</span>';

            $staff_name = '';

            if(isset($staffs[$xs['aid']]))
            {
            	$staff_name = $staffs[$xs['aid']]->fullname;
            }

            $status_string = '';
            switch ($xs['status']){
                case 'New':
                case 'Accepted':
                case 'Published':
                case 'Under Layout Editing':
                case 'Under Galley Correction':
                    $status_string = '<span class="label label-success inline-block"> ' . $xs['status'] . ' </span>';
                    break;
                case 'In Progress':
                case 'Awaiting Publication':
                case 'Under Plagiarism Check':
                case 'Under Peer-Review':
                    $status_string = '<span class="label label-primary inline-block"> ' . $xs['status'] . ' </span>';
                    break;
                case 'Rejected':
                case 'Withdrawn':
                    $status_string = '<span class="label label-danger inline-block"> ' . $xs['status'] . ' </span>';
                    break;
                case 'Scheduled for Current Issue':
                case 'Scheduled for Next Issue':
                case 'Scheduled for Special Issue':
                    $status_string = '<span class="label label-warning inline-block"> ' . $xs['status'] . ' </span>';
                    break;
                default: 
                    $status_string = '<span class="label label-primary inline-block"> ' . $xs['status'] . ' </span>';
            }

            $payment_status_string = "";

            switch ($xs['payment_status']){
                case 'Not generated':
                    $payment_status_string .= "<p class='label-primary' style='border: 0px; font-size: 1.5rem; margin: 12px 2px;'>Not generated</p>";
                    break;
                case 'Paid':
                    $payment_status_string .= "<p class='label-success' style='border: 0px; font-size: 1.5rem; margin: 12px 2px;'>Paid</p>";
                    break;
                case 'Unpaid':
                    $payment_status_string .= "<p class='label-danger' style='border: 0px; font-size: 1.5rem; margin: 12px 2px;'>Unpaid</p>";
                    break;
                default:
                     $payment_status_string .= "<p class='label-primary' style='border: 0px; font-size: 1.5rem; margin: 12px 2px;'>Not generated</p>";
            }


            $tasks_string = "";
            $created_date = "";
            $assigned_tasks = ORM::for_table('sys_tasks')->where('tid', $xs['id'])->find_array();
            foreach($assigned_tasks as $at){
                $status_class = "";
                switch ($at['status']) {
                    case 'Not Started':
                        $status_class = "display:inline-block; color:#f44336; font-weight: 600; padding: 3px 8px; font-size:80%";
                        break;
                    case 'In Progress':
                        $status_class = "display:inline-block; color:#2196f3; font-weight: 600; padding: 3px 8px;  font-size:80%";
                        break;
                    case 'Deferred':
                    case 'Waiting for editor approval':
                        $status_class = "display:inline-block; color:#3949ab; font-weight: 600; padding: 3px 8px;  font-size:80%";
                        break;
                    default:
                        $status_class = "display:inline-block; color:#2196f3; font-weight: 600; padding: 3px 8px;  font-size:80%";
                }

                if($at['status'] != 'Completed'){
                    $tasks_string .= "<div style='margin-bottom:10px'><span style='font-weight:600'>".$at['title']."</span><br/><span style='".$status_class."'>(".$at['status'].")</span></div>";
                    $created_date .= "<div style='margin-bottom:10px'><span style='font-weight:600'>".date('Y-m-d', strtotime($at['created_at']))."</span><br/><span style='display:inline-block'>&nbsp;</span></div>";
                }
            }


            $assigned_date ="";
            if($xs['assigned_at']){
                $assigned_date = date('Y-m-d', strtotime($xs['assigned_at']));
            }


            $records["data"][] = array(
                0 => $xs['tid'],
            //    1 => '<a href="'.U.'contacts/view/'.$xs['id'].'">'.$img.'</a>',
                1 => $xs['subject'],
                2 => $xs['account'],
                3 => $staff_name,
                4 => $assigned_date,
                5 => $status_string.$payment_status_string,
                6 => $tasks_string,
                7 => $created_date,
                8 => $xs['id'],
                9 => $xs['userid'],

                "DT_RowId" => 'row_'.$xs['id']


            );

        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        api_response($records);

        break;

    case 'update_title':
        $id = _post('id');
        $title = _post('title');

        $d = db_find_one('sys_tickets', $id);

        if($title == ''){
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Can not use empty title'
            ));
        }else {
            $check_subject = ORM::for_table('sys_tickets')->where('subject', $title)->find_one();
            if ($check_subject) {
                echo json_encode(array(
                   'status' => 'error',
                   'message' => 'Can not use same title'
                ));
            }elseif($d){
                $d->subject = $title;
                $successed = $d->save();
                if($successed){
                    echo json_encode(array(
                        'status' => 'success',
                        'message' => $title
                    ));
                }else{
                    echo json_encode(array(
                        'status' => 'error',
                        'message' => 'Something error occured'
                    ));
                }
            }
        }

        break;

    case 'update_cc':

        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');

        if($value != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            i_close($_L['Invalid Email']);
        }

        if($d){

            $d->cc = $value;
            $d->save();

        }

        echo '1';


        break;

    case 'update_hour':

        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');

        if(!is_numeric($value))
        {
            exit('Invalid data');
        }

        if($d){

            $hh = '00';
            $mm = '00';
            $ss = '00';

            if($d->ttotal != '')
            {
                $hhmmss = $d->ttotal;
                $hhmmss_split = explode(':',$hhmmss);
                $hh = $hhmmss_split[0];
                $mm = $hhmmss_split[1];
                $ss = $hhmmss_split[2];
            }



            $new_hh = $value;

            $new_total = $new_hh.':'.$mm.':'.$ss;

            $d->ttotal = $new_total;

            $d->save();

        }

        echo '1';


        break;

    case 'update_minute':

        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');

        if(!is_numeric($value))
        {
            exit('Invalid data');
        }

        if($d){

            $hh = '00';
            $mm = '00';
            $ss = '00';

            if($d->ttotal != '')
            {
                $hhmmss = $d->ttotal;
                $hhmmss_split = explode(':',$hhmmss);
                $hh = $hhmmss_split[0];
                $mm = $hhmmss_split[1];
                $ss = $hhmmss_split[2];
            }



            $new_mm = $value;

            $new_total = $hh.':'.$new_mm.':'.$ss;

            $d->ttotal = $new_total;

            $d->save();

        }

        echo '1';


        break;

    case 'update_bcc':


        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');


        if($value != '' && !filter_var($value, FILTER_VALIDATE_EMAIL)){
            i_close($_L['Invalid Email']);
        }

        if($d){
            $d->bcc = $value;
            $d->save();

        }

        echo '1';

        break;

    case 'update_status':

        global $config;

        $id = _post('id');
        $d = db_find_one('sys_tickets',$id);
        $value =  _post('value');

        if($d){
            $d->status = $value;
            $d->save();

        }

        // Emailing

        $t = ORM::for_table('sys_tickets')->find_one($id);

        // $dname = Ticket::get_department($t->did);

        $eml = ORM::for_table('sys_email_templates')->where('tplname', 'Ticket Status - Client')->where('send', 'Yes')->find_one();

        $email = $t->email;

        if ($eml) {

            $client_view_link = U . 'client/tickets/view/' . $t->id . '/';

            $eml_subject = new Template($eml->subject);
            $eml_subject->set('business_name', $config['CompanyName']);
            $eml_subject->set('subject', $t->subject);
            $eml_subject->set('ticket_subject', $t->subject);
            $eml_subject->set('ticket_id', '#' . $t->tid);
            $subj = $eml_subject->output();

            $eml_message = new Template($eml->message);
            $eml_message->set('client_name', $t->account);
            $eml_message->set('client_email', $email);
            $eml_message->set('priority', $t->urgency);
            $eml_message->set('urgency', $t->urgency);
            $eml_message->set('ticket_subject', $t->subject);
            $eml_message->set('status', $t->urgency);
            $eml_message->set('ticket_status', $t->status);
            $eml_message->set('ticket_urgency', $t->urgency);
            $eml_message->set('ticket_priority', $t->urgency);
            $eml_message->set('ticket_id', $t->tid);
            $eml_message->set('ticket_message', $t->message);
            $eml_message->set('business_name', $config['CompanyName']);
            $eml_message->set('ticket_link', $client_view_link);
            $eml_message->set('department', $t->dname);
            // $eml_message->set('processing', $urgency);
            $message_o = $eml_message->output();

            // if ($reply_type != 'internal') {
                Notify_Email::_send($t->account, $email, $subj, $message_o, $cid = $t->userid);

                // SMS 

                $user_data = ORM::for_table('crm_accounts')->find_one($t->userid);
                $client_phone_number = $user_data->phone;

                if ($client_phone_number != '') {
                    require 'system/lib/misc/smsdriver.php';

                    $tpl = SMSTemplate::where('tpl', 'Ticket Status: Client Notification')->first();

                    if ($tpl) {
                        $message = new Template($tpl->sms);
                        $message->set('ticket_id', $t->tid);
                        $message->set('ticket_status', $t->status);
                        $message_o = $message->output();
                        spSendSMS($client_phone_number, $message_o, 'PSCOPE', 0, 'text', 4);
                    }
                }
            // }



        }



        echo '1';

        break;

    case 'admin_read':

        $id = _post('id');
        $tab = _post('tab');
        $user_type = $user['user_type'];
        $user_id = $user['id'];

        if($user_type == 'Admin'){
            $ticket_replies = ORM::for_table('sys_ticketreplies')
                ->where('tid', $id)
                ->where_not_equal('admin_read', 'yes')
                ->where_not_equal('admin', $user_id);


            if($tab == 'downloads'){
                $ticket_replies = $ticket_replies->where_not_equal('attachments','');
            }
            if($tab == 'comments'){
                $ticket_replies = $ticket_replies->where('attachments','');
            }

            $ticket_replies = $ticket_replies->find_many();
            if($ticket_replies){
                foreach ($ticket_replies as $t) {
                    $t->admin_read = 'yes';
                    $t->save();
                }
            }

        }else if($user_type == 'Employee') {
            $ticket_replies = ORM::for_table('sys_ticketreplies')
                ->where('tid', $id)
                ->where_not_equal('staff_read', 'yes')
                ->where_not_equal('admin',$user_id);

            if($tab == 'downloads'){
                $ticket_replies = $ticket_replies->where_not_equal('attachments','');
            }
            if($tab == 'comments'){
                $ticket_replies = $ticket_replies->where('attachments','');
            }
            $ticket_replies = $ticket_replies->find_many();

            if($ticket_replies){
                foreach ($ticket_replies as $t) {
                    $t->staff_read = 'yes';
                    $t->admin_read = 'yes';
                    $t->save();
                }
            }

        }

        echo '1';

        break;

    case 'update_department':


        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');



        if($d){
            $d->did = $value;
            $d->save();

        }

        echo '1';

        break;

    case 'update_ttype':

        $id = _post('id');

        $d = db_find_one('sys_tickets', $id);

        $value = _post('value');

        if($d){
            $d->ttype = $value;
            $d->save();
        }
        
        echo '1';

        break;

    case 'update_activation':

        $id = _post('id');

        $d = db_find_one('sys_tickets', $id);

        $value =  _post('value');

        if ($d) {
            $d->activation = $value;
            $d->save();
        }

        echo $value;

        break;

    case 'update_priority':
        
        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');

        if($d){
            $d->urgency = $value;
            $d->save();

        }



        // Emailing

        $t = ORM::for_table('sys_tickets')->find_one($id);

        // $dname = Ticket:: get_department($t->did);

        $eml = ORM::for_table('sys_email_templates')->where('tplname', 'Ticket Priority - Client')->where('send', 'Yes')->find_one();

        $email = $t->email;

        if ($eml) {

            $client_view_link = U . 'client/tickets/view/' . $t->id . '/';

            $eml_subject = new Template($eml->subject);
            $eml_subject->set('business_name', $config['CompanyName']);
            $eml_subject->set('subject', $t->subject);
            $eml_subject->set('ticket_subject', $t->subject);
            $eml_subject->set('ticket_id', '#' . $t->tid);
            $subj = $eml_subject->output();

            $eml_message = new Template($eml->message);
            $eml_message->set('client_name', $t->account);
            $eml_message->set('client_email', $email);
            $eml_message->set('priority', $t->urgency);
            $eml_message->set('urgency', $t->urgency);
            $eml_message->set('ticket_subject', $t->subject);
            $eml_message->set('status', $t->urgency);
            $eml_message->set('ticket_status', $t->status);
            $eml_message->set('ticket_urgency', $t->urgency);
            $eml_message->set('ticket_priority', $t->urgency);
            $eml_message->set('ticket_id', $t->tid);
            $eml_message->set('ticket_message', $t->message);
            $eml_message->set('business_name', $config['CompanyName']);
            $eml_message->set('ticket_link', $client_view_link);
            $eml_message->set('department', $t->dname);
            // $eml_message->set('processing', $urgency);
            $message_o = $eml_message->output();

            // if ($reply_type != 'internal') {
                Notify_Email::_send($t->account, $email, $subj, $message_o, $cid = $t->userid);


                // SMS 
                /*
                $user_data = ORM::for_table('crm_accounts')->find_one($t->userid);
                $client_phone_number = $user_data->phone;

                if ($client_phone_number != '') {
                    require 'system/lib/misc/smsdriver.php';

                    $tpl = SMSTemplate::where('tpl', 'Ticket Status: Client Notification')->first();

                    if ($tpl) {
                        $message = new Template($tpl->sms);
                        $message->set('ticket_id', $t->tid);
                        $message->set('ticket_status', $t->status);
                        $message_o = $message->output();
                        spSendSMS($client_phone_number, $message_o, 'PSCOPE', 0, 'text', 4);
                    }
                }

                */
            // }
        }


        echo $value;

        break;

    case 'update_processing_for':

        $id = _post('id');

        $d = db_find_one('sys_tickets', $id);

        $value = _post('value');

        if ($d) {
            $d->processing_for = $value;
            $d->save();
        }

        echo $value;

        break;

    case 'update_payment_status':
        $id = _post('id');

        $d = db_find_one('sys_tickets', $id);

        $value = _post('value');

        if($d) {
            $d->payment_status = $value;
            $d->save();
        }
        echo $value;
        break;

    case 'update_assigned_reviewer':
        $id = _post('id');

        $d = db_find_one('sys_tickets', $id);

        $value = _post('value');

        if(!is_numeric($value)){
            $value = 0;
        }

        $staff = User::find($value);

        if($staff)
        {
            // send email

            // Assign task to this staff

            Notify_Email::_send('', $staff->username, 'Submission assigned: '.$d->tid, 'Submission is assigned for review. View submission URL- '.U.'tickets/admin/view/'.$d->id);

            // Send sms notification when ticket is assigned

            if(isset($config['tickets_assigned_sms_notification']) && $config['tickets_assigned_sms_notification'] == 1 && $staff->phonenumber != '')
            {
                require 'system/lib/misc/smsdriver.php';

                $tpl = SMSTemplate::where('tpl','Ticket Assigned: Admin Notification')->first();

                if($tpl)
                {
                    $message = new Template($tpl->sms);
                    $message->set('ticket_id', $d->tid);
                    $message_o = $message->output();
                    spSendSMS($staff->phonenumber,$message_o);
                }
            }

            //
        }

        if($d) {
            $d->rid = $value;
            $d->save();
        }

        jsonResponse([
            'id' => $d->id,
            //'fullname' => $staff->fullname,
            'success' => true
        ]);

        break;

    case 'update_assigned_to':


        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');

        if(!is_numeric($value)){
            $value = 0;
        }

        // Find the user

        $staff = User::find($value);

        if($staff)
        {
            // send email

            // Assign task to this staff

            // check tasks already exist for this staff

            $task = Task::where('aid',$staff->id)
                ->where('tid',$d->id)
                ->first();

            // if(!$task)
            // {

            //     Tasks::create([
            //         'title' => $d->tid,
            //         'rel_type' => 'Ticket',
            //         'rel_id' => $d->id,
            //         'aid' => $staff->id,
            //         'tid' => $d->id,
            //     ]);
            // }

            Notify_Email::_send('', $staff->username, 'Submission assigned: '.$d->tid, 'Submission is assigned to you. View submission URL- '.U.'tickets/admin/view/'.$d->id);

            // Send sms notification when ticket is assigned

            if(isset($config['tickets_assigned_sms_notification']) && $config['tickets_assigned_sms_notification'] == 1 && $staff->phonenumber != '')
            {
                require 'system/lib/misc/smsdriver.php';

                $tpl = SMSTemplate::where('tpl','Ticket Assigned: Admin Notification')->first();

                if($tpl)
                {
                    $message = new Template($tpl->sms);
                    $message->set('ticket_id', $d->tid);
                    $message_o = $message->output();
                    spSendSMS($staff->phonenumber,$message_o);
                }
            }

            //
        }

        if($d){
            $d->aid = $value;
            if($value != 'None'){
                $d->assigned_at = date('Y-m-d H:i:s');
            }
            $d->save();

            jsonResponse([
            	'id' => $d->id,
	            // 'fullname' => $staff->fullname,
	            'success' => true
            ]);

        }

        break;

    case 'update_email':

        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        $value =  _post('value');



        if($d && filter_var($value, FILTER_VALIDATE_EMAIL)){
            $d->email = $value;
            $d->save();
            echo '1';

        }
        else{
            echo 'Invalid Email';
        }




        break;

    case 'reply_make_public':

        global $config;
        $id = route(3);
        $id = str_replace('rp','',$id);

        $d = db_find_one('sys_ticketreplies',$id);

        if($d){
            $prev_reply_type = $d->reply_type;
            $d->reply_type = 'public';
            $d->save();

            if($prev_reply_type == 'review'){

                $tid = $d->tid;
                $message = $d->message;

                $eml = ORM::for_table('sys_email_templates')->where('tplname', 'Tickets:Admin Response')->where('send', 'Yes')->find_one();
                $t = ORM::for_table('sys_tickets')->find_one($tid);

                if($t){

                    if ($eml) {

                        $client_view_link = U . 'client/tickets/view/' . $tid . '/';

                        $eml_subject = new Template($eml->subject);
                        $eml_subject->set('business_name', $config['CompanyName']);
                        $eml_subject->set('subject', $t->subject);
                        $eml_subject->set('ticket_subject', $t->subject);
                        $eml_subject->set('ticket_id', '#' . $t->tid);
                        $subj = $eml_subject->output();

                        $eml_message = new Template($eml->message);
                        $eml_message->set('client_name', $t->account);
                        $eml_message->set('client_email', $t->email);
                        $eml_message->set('priority', $t->urgency);
                        $eml_message->set('urgency', $t->urgency);
                        $eml_message->set('ticket_subject', $t->subject);
                        $eml_message->set('status', $t->urgency);
                        $eml_message->set('ticket_status', $t->status);
                        $eml_message->set('ticket_urgency', $t->urgency);
                        $eml_message->set('ticket_priority', $t->urgency);
                        $eml_message->set('ticket_id', $t->tid);
                        $eml_message->set('ticket_message', $message);
                        $eml_message->set('business_name', $config['CompanyName']);
                        $eml_message->set('ticket_link', $client_view_link);
                        $eml_message->set('department', $t->dname);
                        // $eml_message->set('processing', $urgency);
                        $message_o = $eml_message->output();

                        Notify_Email::_send($t->account, $t->email, $subj, $message_o, $t->userid);

                    }
                }

            }

            // Tickets::sendReplyNotification($d->tid, $d->message);

            r2(U.'tickets/admin/view/'.$d->tid,'s','Updated Successfully');

        }



        break;

    case 'tasks_list':

        $tid = route(3);
        
        $tasks = ORM::for_table('sys_tasks')
            ->left_outer_join('sys_users', array('sys_tasks.aid', '=', 'sys_users.id'))
            ->where('rel_type','Ticket')
            ->where('rel_id',$tid)
            ->select('sys_tasks.title')
            ->select('sys_tasks.id')
            ->select('sys_tasks.status')
            ->select('sys_users.fullname')
            ->order_by_desc('sys_tasks.id')
            ->find_array();
        
        $table_data = "<table class='table table-bordered table-hover sys_table'>
                        <thead>
                            <tr>
                                <th width='30px'>#</th>
                                <th width='50%'>Task Name</th>";
                                if($user['user_type'] == 'Admin' || $user['user_type'] == 'Manager'){
                                    $table_data .= '<th>Assigned to</th>';
                                }
        $table_data .="<th style='text-align:center'> Status</th>
                            </tr>
                        </thead>
                        <tbody>";

        foreach ($tasks as $key => $task) {
            if ($task['status'] == 'Completed') {
                $table_data .= "<tr><td>" . ($key + 1) . "</td><td>" . $task['title'] . "</td>";
                if($user['user_type'] == 'Admin' || $user['user_type'] == 'Manager') { $table_data .= "<td>".$task['fullname']."</td>"; }
                $table_data .= "<td style='color:green; text-align:center'>" . $task['status'] . "</td></tr>";
            } elseif ($task['status'] == 'In Progress') {
                $table_data .= "<tr><td>" . ($key + 1) . "</td><td>" . $task['title'] . "</td>";
                if ($user['user_type'] == 'Admin' || $user['user_type'] == 'Manager') {
                    $table_data .= "<td>" . $task['fullname'] . "</td>";
                }
                $table_data .= "<td style='color:blue; text-align:center'>" . $task['status'] . "</td></tr>";
            } else {
                $table_data .= "<tr><td>" . ($key + 1) . "</td><td>" . $task['title'] . "</td>";
                if ($user['user_type'] == 'Admin' || $user['user_type'] == 'Manager') {
                    $table_data .= "<td>" . $task['fullname'] . "</td>";
                }
                $table_data .= "<td style='color:red;text-align:center'>" . $task['status'] . "</td></tr>";
            }
        }

        $table_data .= "</tbody></table>";

        if ($tasks) {
            echo $table_data;
        } else { }


        /*
        $li = '';

        foreach ($tasks as $task){
            $li .= '<li class="task_item'.(($task['status'] == 'Completed') ? ' completed':'').'" id="t_tasks_'.$task['id'].'">
                                <input id="s_tasks_'.$task['id'].'" type="checkbox" value="" name="" '.(($task['status'] == 'Completed') ? ' checked':'').' class="i-checks"/>
                                <span class="m-l-xs">'.$task['title'].'</span>
                                
                            </li>';
        }

        if($li == ''){

            //  echo '<p>No data available.</p>';

        }

        else{
            echo '<ul class="todo-list m-t">
                            
                            '.$li.'
                            
                        </ul>';
        }
        */




        break;

    case 'do_task':


        $ids = $_POST['ids'];
        $do = _post('action');

        if($do != 'delete'){

            if($do == 'completed'){
                foreach ($ids as $id){
                    $id = str_replace('t_tasks_','',$id);
                    $d = ORM::for_table('sys_tasks')->find_one($id);
                    if($d){
                        $d->status = 'Completed';
                        $d->save();
                    }
                }


            }

            elseif ($do == 'not_started'){
                foreach ($ids as $id){
                    $id = str_replace('t_tasks_','',$id);
                    $d = ORM::for_table('sys_tasks')->find_one($id);
                    if($d){
                        $d->status = 'Not Started';
                        $d->save();
                    }
                }
            }

            // Email function
            foreach ($ids as $id) {
                $id = str_replace('t_tasks_', '', $id);

                $t_data = ORM::for_table('sys_tasks')->find_one($id);

                $client_phone_number = '';
                
                if ($t_data->rel_type == 'Ticket') {

                    if ($t_data->cid != 0) {
                        
                        $client = ORM::for_table('crm_accounts')->find_one($t_data->cid);
                     
                        if ($client) { 
                            
                            $client_phone_number = $client->phone;
                            
                            if ($client->fname != '' && $client->lname != '') {
                                $client_name = trim($client->fname) . ' ' . trim($client->lname);
                            } else {
                                $client_name = $client->account;
                            }
                        }

                    } else {
                        $client_name = '';
                    }

                    if ($t_data->tid != 0) {
                        $ticket = ORM::for_table('sys_tickets')->find_one($t_data->tid);
                        if ($ticket) {
                            $ticket_id = $ticket->tid;
                            $ticket_subject = $ticket->subject;
                            $ticket_priority = $ticket->urgency;
                            $department = $ticket->dname;
                        }
                    }

                    $eml = ORM::for_table('sys_email_templates')->where('tplname', 'Ticket Task Updated - Client')->where('send', 'Yes')->find_one();

                    $email = $ticket->email;

                    if ($eml) {

                        $client_view_link = U . 'client/tickets/view/' . $ticket->id . '/';
                        $eml_subject = new Template($eml->subject);
                        $eml_subject->set('business_name', $config['CompanyName']);
                        $eml_subject->set('subject', $ticket->subjet);
                        $eml_subject->set('ticket_subject', $ticket->subject);
                        $eml_subject->set('ticket_id', '#' . $ticket->tid);
                        $subj = $eml_subject->output();

                        $eml_message = new Template($eml->message);
                        $eml_message->set('client_name', $client_name);
                        $eml_message->set('client_email', $email);
                        $eml_message->set('priority', $ticket->priority);
                        $eml_message->set('urgency', $ticket->urgency);
                        $eml_message->set('ticket_subject', $ticket->subject);
                        $eml_message->set('status', $ticket->urgency);
                        $eml_message->set('ticket_status', $ticket->status);
                        $eml_message->set('ticket_urgency', $ticket->urgency);
                        $eml_message->set('ticket_priority', $ticket->urgency);
                        $eml_message->set('ticket_id', $ticket->tid);
                        $eml_message->set('ticket_message', $ticket->message);
                        $eml_message->set('business_name', $config['CompanyName']);
                        $eml_message->set('ticket_link', $client_view_link);
                        $eml_message->set('department', $ticket->dname);

                        $eml_message->set('task_name', $t_data->title);
                        $eml_message->set('task_status', $t_data->status);
                        $eml_message->set('task_comments', $t_data->description);

                        // $eml_message->set('processing', $urgency);
                        $message_o = $eml_message->output();

                        if ($reply_type != 'internal') {
                            Notify_Email::_send($ticket->account, $email, $subj, $message_o, $cid = $ticket->userid);

                            if ($client_phone_number != '') {
                                require 'system/lib/misc/smsdriver.php';

                                $tpl = SMSTemplate::where('tpl', 'Task Status: Client Notification')->first();

                                if ($tpl) {
                                    $message = new Template($tpl->sms);
                                    $message->set('ticket_id', $t_data->tid);
                                    $message->set('task_name', $t_data->title);
                                    $message->set('task_status', $t_data->status);
                                    $message_o = $message->output();
                                    spSendSMS($client_phone_number, $message_o, 'PSCOPE', 0, 'text', 4);
                                }
                            }


                        }
                    }
                }
            }

        }
        else
        {
            foreach ($ids as $id) {
                $id = str_replace('t_tasks_', '', $id);
                $t_data = ORM::for_table('sys_tasks')->find_one($id);

                if ($t_data) {

                    $client_phone_number = '';
                 
                    if ($t_data->rel_type == 'Ticket') {

                        if ($t_data->cid != 0) {

                            $client = ORM::for_table('crm_accounts')->find_one($t_data->cid);
                            
                            if ($client) {

                                $client_phone_number = $client->phone;

                                if ($client->fname != '' && $client->lname != '') {
                                    $client_name = trim($client->fname) . ' ' . trim($client->lname);
                                } else {
                                    $client_name = $client->account;
                                }
                            }
                        } else {
                            $client_name = '';
                        }

                        if ($t_data->tid != 0) {
                            $ticket = ORM::for_table('sys_tickets')->find_one($t_data->tid);
                            if ($ticket) {
                                $ticket_id = $ticket->tid;
                                $ticket_subject = $ticket->subject;
                                $ticket_priority = $ticket->urgency;
                                $department = $ticket->dname;
                            }
                        }

                        $eml = ORM::for_table('sys_email_templates')->where('tplname', 'Ticket Task Deleted - Client')->where('send', 'Yes')->find_one();

                        $email = $ticket->email;

                        if ($eml) {

                            $client_view_link = U . 'client/tickets/view/' . $ticket->id . '/';
                            $eml_subject = new Template($eml->subject);
                            $eml_subject->set('business_name', $config['CompanyName']);
                            $eml_subject->set('subject', $ticket->subjet);
                            $eml_subject->set('ticket_subject', $ticket->subject);
                            $eml_subject->set('ticket_id', '#' . $ticket->tid);
                            $subj = $eml_subject->output();

                            $eml_message = new Template($eml->message);
                            $eml_message->set('client_name', $client_name);
                            $eml_message->set('client_email', $email);
                            $eml_message->set('priority', $ticket->priority);
                            $eml_message->set('urgency', $ticket->urgency);
                            $eml_message->set('ticket_subject', $ticket->subject);
                            $eml_message->set('status', $ticket->urgency);
                            $eml_message->set('ticket_status', $ticket->status);
                            $eml_message->set('ticket_urgency', $ticket->urgency);
                            $eml_message->set('ticket_priority', $ticket->urgency);
                            $eml_message->set('ticket_id', $ticket->tid);
                            $eml_message->set('ticket_message', $ticket->message);
                            $eml_message->set('business_name', $config['CompanyName']);
                            $eml_message->set('ticket_link', $client_view_link);
                            $eml_message->set('department', $ticket->dname);

                            $eml_message->set('task_name', $t_data->title);
                            $eml_message->set('task_status', 'Deleted');
                            $eml_message->set('task_comments', $t_data->description);

                            // $eml_message->set('processing', $urgency);
                            $message_o = $eml_message->output();

                            // if ($reply_type != 'internal') {
                                
                                Notify_Email::_send($ticket->account, $email, $subj, $message_o, $cid = $ticket->userid);

                                // SMS 
                                if ($client_phone_number != '') {
                                    require 'system/lib/misc/smsdriver.php';

                                    $tpl = SMSTemplate::where('tpl', 'Task Status: Client Notification')->first();

                                    if ($tpl) {
                                        $message = new Template($tpl->sms);
                                        $message->set('ticket_id', $t_data->tid);
                                        $message->set('task_name', $t_data->title);
                                        $message->set('task_status', $t_data->status);
                                        $message_o = $message->output();
                                        spSendSMS($client_phone_number, $message_o, 'PSCOPE', 0, 'text', 4);
                                    }
                                }
                            // }
                        }
                    }

                    $t_data->delete();
                }
            }

        }
       

        echo 'ok';


        break;

    case 'set_task_completed':

        $id = route(3);
        $id = str_replace('s_tasks_','',$id);
        $d = ORM::for_table('sys_tasks')->find_one($id);
        if($d){
            $d->status = 'Completed';
            $d->save();
            echo 'ok';
        }

        break;

    case 'set_task_not_started':

        $id = route(3);
        $id = str_replace('s_tasks_','',$id);
        $d = ORM::for_table('sys_tasks')->find_one($id);
        if($d){
            $d->status = 'Not Started';
            $d->save();
            echo 'ok';
        }


        break;

    case 'update_phone':

        $id = _post('id');

        $d = db_find_one('sys_tickets',$id);

        if($d){
            $customer = db_find_one('crm_accounts',$d->userid);


            if($customer){
                $customer->phone = _post('value');
                $customer->save();

            }

        }


        echo '1';


        break;

    case 'available_status':

        echo '<div class="form-group">
                    <label for="bulk_status">Status</label>
                    <select class="form-control" id="bulk_status" name="bulk_status" size="1">
                      
                        <option value="Open">Open</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Escalated">Escalated</option>
                        <option value="Closed">Closed</option>
    
                    </select>
                </div>';




        break;

    case 'set_status':


        $ids_raw = $_POST['ids'];

        $status = _post('status');





        foreach ($ids_raw as $id_single){
            $id = str_replace('row_','',$id_single);
            $t = ORM::for_table('sys_tickets')->select('id')->find_one($id);
            if($t){
                $t->status = $status;
                $t->save();
            }

        }


        echo $_L['Data Updated'];


        break;

    case 'settings':


        view('tickets_admin_edit_modal');


        break;

    case 'delete_multiple':

        if(!isset($_POST['ids'])){
            exit;
        }

        $ids_raw = $_POST['ids'];

        $ids = array();

        foreach ($ids_raw as $id_single){
            $id = str_replace('row_','',$id_single);
            array_push($ids,$id);
        }

        $tickets = ORM::for_table('sys_tickets')->where_id_in($ids)->delete_many();

        r2(U.'tickets/admin/list/','s',$_L['Deleted Successfully']);




        break;

    case 'log_time':

        $ticket_id = _post('ticket_id');

        $ticket = Ticket::find($ticket_id);

        if($ticket)
        {
            $ticket->ttotal = _post('total_time');
            $ticket->save();
        }

        break;

    case 'get-predefined-reply':

        $id = route(3);


        $reply = TicketPredefinedReply::find($id);

        if($reply)
        {
            echo $reply->message;
        }

        break;

    case 's':

        is_dev();


        $t = new Schema('sys_tickets');
        $t->add('tid','varchar',100);
        $t->add('did','int',10);
        $t->add('aid','int',10);
        $t->add('pid','int',10);
        $t->add('sid','int',10);
        $t->add('lid','int',10);
        $t->add('oid','int',10);
        $t->add('company_id','int',10);
        $t->add('dname','varchar',100);
        $t->add('userid','int',11);
        $t->add('account','varchar',200);
        $t->add('email','varchar',200);
        $t->add('cc','varchar',200);
        $t->add('bcc','varchar',200);
        $t->add('created_at','timestamp');
        $t->add('updated_at','timestamp');
        $t->add('subject');
        $t->add('message');
        $t->add('status','varchar',100);
        $t->add('urgency','varchar',100);
        $t->add('admin','varchar',100);
        $t->add('attachments');
        $t->add('last_reply','varchar',100);
        $t->add('flag','int',1);
        $t->add('escalated','int',1);
        $t->add('replying','int',1);
        $t->add('is_spam','int',1);
        $t->add('rating','int',2);
        $t->add('client_read','varchar',100);
        $t->add('admin_read','varchar',100);
        $t->add('source','varchar',100);

//  Question Incident Problem Feature Request
        $t->add('ttype','varchar',100);

        $t->add('tstart','varchar',100);
        $t->add('tend','varchar',100);
        $t->add('ttotal','varchar',100);
        $t->add('todo');
        $t->add('tags');
        $t->add('notes');
        $t->save();


        $t = new Schema('sys_ticketreplies');
        $t->add('tid','int',11);
        $t->add('userid','int',11);
        $t->add('account','varchar',200);
        $t->add('reply_type','varchar',200);
        $t->add('email','varchar',200);
        $t->add('created_at','timestamp');
        $t->add('updated_at','varchar',100);
        $t->add('message');
        $t->add('replied_by','varchar',200);
        $t->add('admin','varchar',100);
        $t->add('attachments');
        $t->add('client_read','varchar',100);
        $t->add('admin_read','varchar',100);
        $t->add('rating','int',2);
        $t->save();

        $t = new Schema('sys_ticketpredefinedreplies');
        $t->add('rname','varchar',200);
        $t->add('reply');
        $t->add('tags');
        $t->add('created_at','datetime');
        $t->add('updated_at','varchar',100);
        $t->add('created_by','int',11);
        $t->add('updated_by','int',11);
        $t->add('attachments');
        $t->save();

        $t = new Schema('sys_ticketmaillog');
        $t->add('date','datetime');
        $t->add('account','varchar',200);
        $t->add('subject','varchar',200);
        $t->add('message');
        $t->add('status','varchar',100);
        $t->add('attachments');
        $t->save();

        $t = new Schema('sys_ticketdepartments');
        $t->add('dname','varchar',200);
        $t->add('description');
        $t->add('email','varchar',200);
        $t->add('hidden','int',1,'0');
        $t->add('delete_after_import','int',1,'0');
        $t->add('host','varchar',200);
        $t->add('port','varchar',50);
        $t->add('username','varchar',200);
        $t->add('password','varchar',100);
        $t->add('encryption','varchar',100);
        $t->add('calendar_id','varchar',200);
        $t->add('sorder','int',10);
        $t->save();

        $t = new Schema('sys_canned_responses');
        $t->add('title');
        $t->add('message');
        $t->add('tags');
        $t->add('attachments');
        $t->add('sorder','int',10,0);
        $t->save();

// create email template

        $d = ORM::for_table('sys_email_templates')->create();
        $d->tplname = 'Tickets:Client Ticket Created';
        $d->subject = '{{subject}}';
        $d->message = '<p>{{client_name}},</p>
                <p>Thank you for contacting our support team. A support ticket has now been opened for your request. You will be notified when a response is made by email. Your ticket ID is {{ticket_id}} and a copy of your original message is included below.</p>
                <p>
                Subject: {ticket_subject}
                <br /> Message: <br />
                {{message}}
                <br /> Priority: {{ticket_priority}}
                <br /> Status: {{ticket_status}}
                </p>
                <p>You can view the ticket at any time at {{ticket_link}}</p>
                        ';
        $d->send = 'Yes';
        $d->core = 'Yes';
        $d->hidden = 0;
        $d->save();


        $d = ORM::for_table('sys_email_templates')->create();
        $d->tplname = 'Tickets:Admin Ticket Created';
        $d->subject = '{{subject}}';
        $d->message = '<p>{{admin_view_link}}</p> {{message}}';
        $d->send = 'Yes';
        $d->core = 'Yes';
        $d->hidden = 0;
        $d->save();


        $d = ORM::for_table('sys_email_templates')->create();
        $d->tplname = 'Tickets:Client Response';
        $d->subject = '{{subject}}';
        $d->message = '<p>{{ticket_message}}</p>
<p>----------------------------------------------<br /> Ticket ID: #{{ticket_id}}<br /> Subject: {$ticket_subject}<br /> Status: {{ticket_status}}<br /> Ticket URL: {$ticket_link}}<br /> ----------------------------------------------</p>';
        $d->send = 'Yes';
        $d->core = 'No';
        $d->hidden = 0;
        $d->save();

        $d = ORM::for_table('sys_email_templates')->create();
        $d->tplname = 'Tickets:Admin Response';
        $d->subject = '{{subject}}';
        $d->message = '<p>{$ticket_message}</p>
<p>----------------------------------------------<br /> Ticket ID: #{$ticket_id}}<br /> Subject: {{ticket_subject}}<br /> Status: {{ticket_status}}<br /> Ticket URL: {{ticket_link}}<br /> ----------------------------------------------</p>';
        $d->send = 'Yes';
        $d->core = 'No';
        $d->hidden = 0;
        $d->save();

        break;

}