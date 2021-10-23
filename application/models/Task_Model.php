<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
    class Task_Model extends CI_Model
    {
		private $table = 'leads';
		private $table_state = 'tbl_state';
		private $table_disburse = 'tbl_disburse';

    	function __construct()  
		{
			parent::__construct();
	    	date_default_timezone_set('Asia/Kolkata');
	        define("date", date('Y-m-d'));
	        define("timestamp", date('Y-m-d H:i:s'));
	        define("ip", $this->input->ip_address());
	        define("product_id", $_SESSION['isUserSession']['product_id']);
	        define("company_id", $_SESSION['isUserSession']['company_id']);
	        define("user_id", $_SESSION['isUserSession']['user_id']);
    		define('agent', $_SESSION['isUserSession']['labels']);
	        
	        /////////// define role ///////////////////////////////////////
	        
            define('screener', "SANCTION QUICKCALLER");
            define('creditManager', "Sanction & Telecaller");
            define('headCreditManager', "Sanction Head");
            define('admin', "Client Admin");
            define('teamDisbursal', "Disbursal");
            define('teamClosure', "Account and MIS");
            define('teamCollection', "Collection");
		}

		public function index($conditions = null, $rowperpage = null, $start = null)
		{
            $this->db->select('LD.lead_id, LD.customer_id, LD.application_no, LD.loan_no, CAM.tenure, C.first_name, C.middle_name, C.sur_name, C.email, C.dob, C.mobile, C.pancard, LD.user_type, ST.state, LD.city, LD.created_on, LD.source, LD.status, LD.ip, LD.coordinates, LD.imei_no');
            $this->db->from($this->table. ' LD');
			$this->db->limit($rowperpage, $start);
            $this->db->join($this->table_state.' ST', 'ST.state_id = LD.state_id');
            $this->db->join('customer C', 'C.customer_id = LD.customer_id', 'left');
            $this->db->join('credit_analysis_memo CAM', 'CAM.lead_id = LD.lead_id', 'left');
            $this->db->join('loan L', 'L.lead_id = LD.lead_id', 'left');

			if($this->uri->segment(1) == "collection") 
			{
	    		$collectionDate = date('d-m-Y', strtotime('+5 days', strtotime(timestamp)));
				$this->db->where('CAM.repayment_date BETWEEN "'. date('d-m-Y', strtotime(timestamp)). '" and "'. date('d-m-Y', strtotime($collectionDate)).'"');
            	$this->db->join('credit_analysis_memo CAM', 'CAM.lead_id = LD.lead_id', 'left');
			}else{
        		$this->db->where($conditions);
			}

            return $this->db->order_by('LD.lead_id', 'desc')->get();   
		}

		
		public function getState()
		{
			return $this->db->select('ST.state_id, ST.state')->from('tbl_state as ST')->get();
		}
		
		public function getCity($state_id)
		{
			return $this->db->select('CT.id, CT.state_id, CT.city')
				->where('CT.state_id', $state_id)
				->from('tbl_city CT')->get();
		}

		public function getLeadsCount($stage)
	    {
			$result = $this->db->select("lead_id")->where('stage', $stage)->from($this->table)->get();
			return $result->num_rows();
	    }

		public function insert($data=null, $table=null)
		{
			return $this->db->insert($table, $data);
		}

		public function select($conditions=null, $data=null, $table=null)
		{
			return $this->db->select($data)->where($conditions)->from($table)->get();
		}

		public function update($conditions, $data)
		{
			return $this->db->where($conditions)->update($this->table, $data);
		}
        
        public function join_table($conditions = null, $data = null, $table1 = null, $table2 = null, $join2=null, $table3 = null, $join3=null, $table4 = null, $join4=null) 
        {
            return $this->db->select($data)
                ->where($conditions)
                ->from($table1)
                ->join($table2, $join2, 'left')
	            ->join($table3, $join3, 'left')
	            ->join($table4, $join4, 'left')
                ->get();
        }
        
        public function join_two_table($data = null, $table1 = null, $table2 = null, $join2=null) 
        {
            return $this->db->select($data)
                ->from($table1)
                ->join($table2, $join2, 'left')
                ->get();
        }
        
        public function join_two_table_with_where($conditions = null, $data = null, $table1 = null, $table2 = null, $join2=null)
        {
            return $this->db->select($data)
                ->where($conditions)
                ->from($table1)
                ->join($table2, $join2, 'left')
                ->get();
        }

        public function three_join_table($conditions = null, $data = null, $table2 = null, $table3 = null) 
        {
            return $this->db->select($data)
                ->where($conditions)
                ->from($this->table.' LD')
                ->join($this->table_disburse ." DS", 'DS.lead_id = LD.lead_id')
                ->join($this->table_state ." ST", 'ST.state_id = LD.state_id')
                ->get();
        }













		public function globalUpdate($conditions=null, $data=null, $table=null)
		{
			return $this->db->where($conditions)->update($table, $data);
		}
		public function updateLeads($conditions=null, $data=null, $table=null)
		{
			return $this->db->where($conditions)->update($table, $data);
		}
		public function delete($conditions=null, $table=null)
		{
			return $this->db->where($conditions)->delete($table);
		}

		public function import_lead_data($data) 
		{
		    return $this->db->insert($this->table, $data);
		}

		public function getProducts()
	    {
	        return $this->db->select('PD.product_code, PD.product_name, PD.source')->where('PD.product_id', product_id)->from('tbl_product as PD')->get();
	    }
	    
	    public function getLeadStatus($type)
	    {
	    	if($type == 'PART PAYMENT') {
	    		$status = $type;
	    		$stage = 'S16';
	    	} else if($type == 'CLOSED') {
	    		$status = $type;
	    		$stage = 'S17';
	    	} else if($type == 'SETTLED') {
	    		$status = $type;
	    		$stage = 'S18';
	    	} else if($type == 'WRITEOFF') {
	    		$status = $type;
	    		$stage = 'S19';
	    	} else {
	    		$status = '-';
	    		$stage = '-';
	    	}
	    	$data['status'] = $status;
	    	$data['stage'] = $stage;
	    	return $data;
	    }

	    public $count = 1;

		public function generateLoanNo($lead_id)
		{
			$conditions2 = ['P.company_id' => company_id, 'P.product_id' => product_id];
			$fetch2 = 'P.product_id, P.product_name, P.product_code';
			$sql2 = $this->select($conditions2, $fetch2, 'tbl_product P');
			$product = $sql2->row();

			$conditions3 = ['LD.lead_id' => $lead_id];
			$fetch3 = 'CL.company_id, CL.company_name, CL.company_code, CT.city_code, L.loan_no';
			$table2 = 'loan L';
			$join2 = 'LD.lead_id = L.lead_id';
			$table3 = 'tbl_city CT';
			$join3 = 'LD.state_id = CT.state_id';
			$table4 = 'company_login CL';
			$join4 = 'CL.company_id = LD.company_id';
			$sql3 = $this->join_table($conditions3, $fetch3, 'leads LD', $table2, $join2, $table3, $join3, $table4, $join4);
			$query = $sql3->row();

			$q = $this->db->select('L.loan_no')->where('L.loan_no !=', '')->from('loan L')->order_by('loan_id', 'desc')->limit(1)->get();
			$pre_loan = $q->row();
			$num1 = preg_replace('/[^1-9]/', '', $pre_loan->loan_no) + $this->count;
			$zerocounts = '';
			for ($i = strlen('000000000'); $i > strlen($num1); $i--) { 
				$zerocounts = $zerocounts . '0';
			}
			$number = $zerocounts .''. $num1;
			$loan_no = $query->company_code .''. $product->product_code .''. $query->city_code .''. $number; // NFPDNRL000000063
			return $loan_no;
		}

		public function recommend()
	    {
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id,leads.application_no, leads.name, leads.middle_name, leads.sur_name, leads.email, leads.mobile, leads.pancard, tb_states.state, leads.city, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.partPayment')
                ->where($where)
                ->where('leads.status', "RECOMMEND")
                ->from('leads')
                ->join('tb_states', 'leads.state_id = tb_states.id');
            return $this->db->order_by('leads.lead_id', 'desc')->get();
	    }

		public function leadSendBack()
	    {
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id,leads.application_no, leads.name, leads.middle_name, leads.sur_name, leads.email, leads.mobile, leads.pancard, tb_states.state, leads.city, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.partPayment')
                ->where($where)
                ->where('leads.status', "SEND BACK")
                ->from('leads')
                ->join('tb_states', 'leads.state_id = tb_states.id');
            return $this->db->order_by('leads.lead_id', 'desc')->get();
	    }

		public function leadSanctioned()
	    {
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id,leads.application_no, leads.name, leads.middle_name, leads.sur_name, leads.email, leads.mobile, leads.pancard, tb_states.state, leads.city, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.partPayment')
                ->where($where)
                ->where('leads.status', "SANCTION")
                ->from('leads')
                ->join('tb_states', 'leads.state_id = tb_states.id');
            return $this->db->order_by('leads.lead_id', 'desc')->get();
	    }

		public function leadDisbursed()
	    {
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id,leads.application_no, leads.name, leads.middle_name, leads.sur_name, leads.email, leads.mobile, leads.pancard, tb_states.state, leads.city, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.partPayment')
                ->where($where)
                ->where('leads.status', "DISBURSED")
                ->from('leads')
                ->join('tb_states', 'leads.state_id = tb_states.id');
            return $this->db->order_by('leads.lead_id', 'desc')->get();
	    }
	    
	    public function sanctionHold()
	    { 
	        $where = ['company_id' => company_id, 'product_id' => product_id];
	        $this->db->select('leads.lead_id, leads.name, leads.email, tb_states.state, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.remark')  
                ->where('leads.leads_duplicate', 0)
                ->where('leads.lead_rejected', 0)
                ->where('leads.loan_approved',0) 
                ->where('leads.lead_status', 'Hold')
                ->where($where)
                ->from('leads')
                ->join('tb_states', 'leads.state_id = tb_states.id');
             return $query = $this->db->order_by('leads.lead_id', 'desc')->get();  
	    }    

		public function leadDisbursed1($limit, $start)
	    {
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id,leads.application_no, leads.name, leads.middle_name, leads.sur_name, leads.email, leads.mobile, leads.pancard, tb_states.state, leads.city, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.partPayment')
                ->where($where)
                ->where('leads.status', "DISBURSED")
                ->from('leads')
                ->join('tb_states', 'leads.state_id = tb_states.id');
            $this->db->limit($limit, $start);
            return $this->db->order_by('leads.lead_id', 'desc')->get();
	    }
		
		public function index2()  
		{
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id, leads.name, leads.email, tb_states.state, leads.created_on, leads.source, leads.status')
                ->where('date(leads.created_on)', todayDate)
		        ->where('leads.loan_approved', 1)
		        ->where($where)
                ->from(tableLeads)
                ->join('tb_states', 'leads.state_id = tb_states.id');
            return $query = $this->db->order_by('leads.lead_id', 'desc')->get();
		}

	    public function selectQuery($fetch, $where, $table)
	    {
            return $this->db->select($fetch)->where($where)->get($table);
	    }

		public function updateQuery($where, $table, $update)
		{
            $this->db->where($where)->update($table, $update);
		}
		
		public function joinTwoTable($fetch, $where, $table, $joinTableTwo, $joinEqualTo)
		{
    		return $this->db->select($fetch)->where($where)->from($table)->join($joinTableTwo, $joinEqualTo)->get();
		}

	    public function getMISData()
	    {
	    	if(!empty($_SESSION['isUserSession']['user_id']))
	    	{
	    		$data = $this->db->distinct()->select('recovery.*, recovery.payment_amount as total_paid, leads.name, leads.email, tb_states.state, leads.source, leads.status, loan.loan_no, users.name as recovery_by')
	    			->where('DATE(recovery.created_on)', currentDate)
	    			->where('recovery.PaymentVerify', 0)
	    			->from('recovery')
	    			->join('leads', 'leads.lead_id = recovery.lead_id')
	    			->join('credit', 'credit.lead_id = recovery.lead_id')
	    			->join('loan', 'loan.lead_id = recovery.lead_id')
            		->join('tb_states', 'leads.state_id = tb_states.id')
            		->join('users', 'users.user_id = recovery.recovery_by')
            // 		->group_by('recovery.lead_id')
	    			->get();
    			return $data;
	    	}
	    }

	    public function getRecoveryData($sql)
	    {
			$data = '
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="border: 1px solid #dde2eb">
                        <thead>
                            <tr>
                                <th class="whitespace"><b>#</b></th>
                                <th class="whitespace"><b>Loan&nbsp;No</b></th>
                                <th class="whitespace"><b>Payment&nbsp;Mode</b></th>
                                <th class="whitespace"><b>Payment&nbsp;Amount</b></th>
                                <th class="whitespace"><b>Discount</b></th>
                                <th class="whitespace"><b>Refund</b></th>
                                <th class="whitespace"><b>Refrence&nbsp;No</b></th>
                                <th class="whitespace"><b>Recovery&nbsp;Date</b></th>
                                <th class="whitespace"><b>Loan&nbsp;Status</b></th>
                                <th class="whitespace"><b>Payment&nbsp;Verification</b></th>
                                <th class="whitespace"><b>Date</b></th>
                                <th class="whitespace"><b>Action</b></th>
                            </tr>
                        </thead>
                	<tbody>';
    		if($sql->num_rows() > 0)
    		{
    			foreach($sql->result() as $row) 
    			{
			        $payment_verification = "PENDING";
    			    
			        $editBtn = "";
			        $deleteBtn = "";
    			    if($row->payment_verification == "PENDING" && agent == "AC1")
    			    {
				        $editBtn = '<a class="btn btn-control btn-primary" onclick="editsCoustomerPayment('. $row->id.', '.$row->received_amount.', '. $row->refrence_no .', '. $row->discount .', '. $row->refund .', '. $row->date_of_recived .')"><i class="fa fa-pencil"></i></a>';
                    }
    			    if($row->payment_verification == "PENDING" && agent == "CO1"){
				        $deleteBtn = '<a class="btn btn-control btn-danger" onclick="deleteCoustomerPayment('.$row->id.', '. user_id .')"><i class="fa fa-trash"></i></a>';
    			    }
    				$data .= '
                        <tr>
                            <td class="whitespace">'. $row->id .'</td>
                            <td class="whitespace">'. $row->loan_no .'</td>
                            <td class="whitespace">'. $row->payment_mode .'</td>
                            <td class="whitespace">'. $row->received_amount .'</td>
                            <td class="whitespace">'. $row->discount .'</td>
                            <td class="whitespace">'. $row->refund .'</td>
                            <td class="whitespace">'. $row->refrence_no .'</td>
                            <td class="whitespace">'. $row->date_of_recived.'</td>
                            <td class="whitespace">'. $row->recovery_status .'</td>
                            <td class="whitespace">'. $row->payment_verification.'</td>
                            <td class="whitespace">'. $row->created_on .'</td>
                            <td class="whitespace">
		                        <a class="btn btn-control btn-success" onclick="viewCustomerPaidSlip(this.title)" title="'. $row->id .'"><i class="fa fa-eye"></i></a>&nbsp;'. $editBtn .'&nbsp;'. $deleteBtn .'
                            </td>
                        </tr>
    				';
    			}
			    $data .='</tbody></table></div>';
			}else
			{
			    $data .='<tbody><tr><td colspan="40" style="text-align: -webkit-center;color:red;">Record Not Found...</td></tr></tbody></table></div>';
			}
	        return $data;
	    }

		public function duplicateTask()  
		{
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id, leads.name, leads.email, tb_states.state, leads.created_on, leads.source, leads.status')
                // ->where('date(leads.created_on)', todayDate)
                ->where($where)
                ->where('leads.leads_duplicate', 1)
                ->from(tableLeads)
                ->join('tb_states', 'leads.state_id = tb_states.id');
            return $query = $this->db->order_by('leads.lead_id', 'desc')->get();
		}

		public function duplicateTaskList($lead_id)  
		{
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('tbl_duplicate_leads.duplicate_lead_id, users.name, tbl_duplicate_leads.reson, tbl_duplicate_leads.created_on')
	            ->where('tbl_duplicate_leads.lead_id', $lead_id)
	            ->where($where)
                ->from('tbl_duplicate_leads')
                ->join('users', 'users.user_id = tbl_duplicate_leads.user_id');
            $duplicateTaskDetails = $this->db->order_by('tbl_duplicate_leads.duplicate_lead_id', 'desc')->get()->result();

            $data = '<div class="table-responsive">
		        <table class="table table-hover table-striped">
                  <thead>
                    <tr class="table-primary">
                      <th scope="col">#</th>
                      <th scope="col">Duplicate Lead ID</th>
                      <th scope="col">Duplicate Marked By</th>
                      <th scope="col">Reson</th>
                      <th scope="col">Initiated On</th>
                    </tr>
                </thead>';
          	$i = 1;
			if($duplicateTaskDetails > 0)
			{
				foreach($duplicateTaskDetails as $column)
				{
				    $data.='<tbody>
                		<tr>
							<td>'.$i++.'</th>
							<td>'.$column->duplicate_lead_id.'</td>
							<td>'.$column->name.'</td>
							<td>'.$column->reson.'</td>
							<td>'.$column->created_on.'</td>
						</tr>';
				}
				
				$data .='</tbody></table></div>';
			}
			else
			{
			    $data .='<tbody><tr><td colspan="7" style="text-align: -webkit-center;color:red;">Record Not Found...</td></tr></tbody></table></div>';
			}
	        return $data;
		}

		public function rejectedTask()  
		{
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('leads.lead_id,leads.application_no, leads.name, leads.middle_name, leads.sur_name, leads.email, leads.mobile, leads.pancard, tb_states.state, leads.city, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.partPayment')
                // ->where('leads.lead_rejected', 1)
                ->where($where)
                ->where('leads.status', "REJECT")
                ->from(tableLeads)
                ->join('tb_states', 'leads.state_id = tb_states.id');
            return $query = $this->db->order_by('leads.lead_id', 'desc')->get();
		}

		public function rejectedLeadDetails($lead_id)  
		{
	        $where = ['company_id' => company_id, 'product_id' => product_id];
            $this->db->select('tbl_rejected_loan.rejected_lead_id, users.name, tbl_rejected_loan.reson, tbl_rejected_loan.created_on')
	            ->where('tbl_rejected_loan.lead_id', $lead_id)
	            ->where($where)
                ->from('tbl_rejected_loan')
                ->join('users', 'users.user_id = tbl_rejected_loan.user_id', 'left');
            $rejectedTaskDetails = $this->db->order_by('tbl_rejected_loan.rejected_lead_id', 'desc')->get()->result();

            $data = '<div class="table-responsive">
		        <table class="table table-hover table-striped">
                  <thead>
                    <tr class="table-primary">
                      <th scope="col">#</th>
                      <th scope="col">Rejected Lead ID</th>
                      <th scope="col">Rejected By</th>
                      <th scope="col">Reson</th>
                      <th scope="col">Initiated On</th>
                    </tr>
                </thead>';
          	$i = 1;
			if($rejectedTaskDetails > 0)
			{
				foreach($rejectedTaskDetails as $column)
				{
				    $data.='<tbody>
                		<tr>
							<td>'.$i++.'</th>
							<td>'.$column->rejected_lead_id.'</td>
							<td>'.$column->name.'</td>
							<td>'.$column->reson.'</td>
							<td>'.$column->created_on.'</td>
						</tr>';
				}
				
				$data .='</tbody></table></div>';
			}
			else
			{
			    $data .='<tbody><tr><td colspan="7" style="text-align: -webkit-center;color:red;">Record Not Found...</td></tr></tbody></table></div>';
			}
	        return $data;
		}

		public function get_filtered_credit(){  
			$this->get_credits($lead_id);
			$query = $this->db->get();  
			return $query->num_rows();  
		}

		public function get_all_credit()
		{  
			$this->db->select('*');  
			$this->db->from("credit");
			$lead_id = $this->uri->segment(3);
			$this->db->where('lead_id',$lead_id);
			return $this->db->count_all_results();  
		}

		public function bank_analiysis($lead_id)
		{
	        $result = $this->db->select('tbl_cart.*')->where('lead_id', $lead_id)->where('product_id', product_id)->order_by('tbl_cart.cart_id', 'desc')->get('tbl_cart');
        	$data = '<div class="table-responsive">
		        <table class="table table-hover table-striped">
                  <thead>
                    <tr class="table-primary">
                      <th scope="col">Sr.</th>
                      <th scope="col">Doc ID</th>
                      <th scope="col">Initiated On</th>
                      <th scope="col">Action</th>
                    </tr>
                </thead>';
          	if($result->num_rows() > 0)
			{
				$i = 1;
				foreach($result->result() as $column)
				{
					$id = $column->lead_id;
                    $status = "In Progress";
                    if($column->status == "Processed"){
                       $status = '<a href="#" data-toggle="modal" data-target="#viewCibilModel" id="viewCibilPDF" onclick="ViewBankingAnalysis(this.title)" title="'.$column->docId.'"><i class="fa fa-file-pdf-o"></i></a>'; 
                    }
				    $data.='<tbody>
                		<tr>							
							<td>'.$i.'</td>
							<td>'.$column->docId.'</td>
							<td>'.$column->created_at.'</td>
							<td><a href="#" data-toggle="modal" data-target="#viewCibilModel" id="viewCibilPDF" onclick="ViewBankingAnalysis(this.title)" title="'.$column->docId.'">'. $column->status .'</a>
						</td>
						</tr>';
				// 		<a href="#" data-toggle="modal" data-target="#viewCibilModel" id="viewCibilPDF" onclick="ViewBankingAnalysis(this.title)" title="'.$column->docId.'"><i class="fa fa-file-pdf-o"></i></a>
							    
					$i++;
				}
				$data .='</tbody></table></div>';
				
			}
			else
			{
		    	$data .='<tbody><tr><td colspan="9" style="text-align: -webkit-center;color:red;">Record Not Found...</td></tr></tbody></table></div>';
			}
		    return $data;
		}
		
		public function DownloadBankingAnalysis($doc_id)
		{
		  //  $Auth_Token = "UAT";
            $Auth_Token = "LIVE";
            
            if($Auth_Token == "UAT") {
                define('api_token', 'API://IlJKyP5wUwzCvKQbb796ZSjOITkMSRN8rifQTMrNM1/NUUv8/tuaN6Lun6d1NG4S');
            } else {
                define('api_token', 'API://9jwoyrhfdtDuDt0epG4VsisYdBHMsZMGC7IlUhwN8t1Qb2bgwxFqrn7K0LgWIly1');
            }
            
            $urlDownload = 'https://cartbi.com/api/downloadFile';
            $header2 = [
                'Content-Type: text/plain', 
                'auth-token: '. api_token
                ];
            
            $ch2 = curl_init($urlDownload);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, $header2);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, $doc_id);
            
            $downloadCartData = curl_exec($ch2);
            curl_close($ch2);
            return $this->db->where('docId', $doc_id)->update('tbl_cart', ['downloadCartData' => $downloadCartData]);
		}
		
		public function ViewBankingAnalysis($doc_id)
		{
		  //  date_default_timezone_set('Asia/Kolkata');
		    $row = $this->db->select('tbl_cart.downloadCartData')->where('docId', $doc_id)->get('tbl_cart');
		    $collection = $row->row_array();
		    $CartData = json_decode($collection['downloadCartData']);
            // echo "<pre>"; print_r($CartData); exit;
            // echo "<pre>"; print_r($CartData->data);
            // echo "<pre>"; print_r($CartData->data[0]);
            // echo "<pre>"; print_r($CartData->data[0]->salary[0]);
            // echo "<pre>"; print_r($CartData->data[0]->emi[0]); exit;
            
        	$data .= '
                <div id="collection">
                    <div class="footer-support">
                        <h2 class="footer-support">Salary Details &nbsp;<i class="fa fa-angle-double-down"></i></h2>
                    </div>
                </div>
                <div class="table-responsive">
    		        <table class="table table-hover table-bordered table-striped">
                      <thead>
                        <tr class="table-primary">
                            <th scope="col"><strong>Transaction Date</strong></th>	
                            <th scope="col"><strong>Narration</strong></th>
                            <th scope="col"><strong>Cheque</strong></th>
                            <th scope="col"><strong>Amount</strong></th>
                            <th scope="col"><strong>Type</strong></th>
                        </tr>
                    </thead>';
	        foreach($CartData->data[0]->salary as $salary){
	            $data .= '<tbody>
	                <tr>
						<td><strong>'. $salary->month .'</strong></td>
						<td colspan="12"><strong>Rs. '. number_format($salary->totalSalary, 2) .'</strong></td>
	                </tr>';
    	        foreach($salary->transactions as $salaryList){
    	            $data .= '
					    <tr>
    					    <td>'. date('d/M/Y', substr($salaryList->transactionDate, 0, 10)) .'</td>
                            <td>'. $salaryList->narration .'</td>	
                            <td>'. $salaryList->cheque .'</td>
                            <td>'. number_format($salaryList->amount, 2) .'</td>
                            <td>'. $salaryList->type .'</td>
					    </tr>';
    	        }
	        }
            $data .= '</tbody></table></div>';
	        
        	$data .= '
                <div id="collection">
                    <div class="footer-support">
                        <h2 class="footer-support">EMI Details &nbsp;<i class="fa fa-angle-double-down"></i></h2>
                    </div>
                </div>
                <div class="table-responsive">
    		        <table class="table table-hover table-bordered table-striped">
                      <thead>
                        <tr class="table-primary">
                            <th scope="col"><strong>Transaction Date</strong</th>
                            <th scope="col"><strong>Narration</strong</th>		
                            <th scope="col"><strong>Payment Category</strong</th>
                            <th scope="col"><strong>Cheque</strong</th>
                            <th scope="col"><strong>Amount</strong</th>
                            <th scope="col"><strong>Type</strong</th>	
                        </tr>
                    </thead>';
	        foreach($CartData->data[0]->emi as $emi){
	            $data .= '<tbody>
	                <tr>
						<td><strong>'. $emi->commonEntity .'</strong></td>
						<td colspan="5"><strong>Rs. '. number_format($emi->amount, 2) .'</strong></td>
						
	                </tr>';
    	        foreach($emi->transactions as $emiList){
    	            $data .= '
					    <tr>
    					    <td>'. date('d/M/Y', substr($emiList->transactionDate, 0, 10)) .'</td>
                            <td>'. $emiList->narration .'</td>		
                            <td>'. $emiList->paymentCategory .'</td>
                            <td>'. $emiList->cheque .'</td>
                            <td>Rs. '. number_format($emiList->amount, 2) .'</td>
                            <td>'. $emiList->type .'</td>
					    </tr>';
    	        }
	        
	        }
            $data .= '</tbody>
                </table>
            </div>';
		    return $data;
		}
		
		public function ViewCivilStatement($customer_id)
		{
			$data = '<div class="table-responsive">
		        <table class="table table-hover table-striped table-bordered" data-order="[[ 0, "desc" ]]" style="margin-top: 10px;">
                  <thead>
                    <tr class="table-primary">
                      <th scope="col">Sr.&nbsp;No</th>
                      <th scope="col">Member&nbsp;ID</th>
                      <th scope="col">High&nbsp;Credit/&nbsp;Sanc&nbsp;Amt</th>
                      <th scope="col">Total&nbsp;Accounts</th>
                      <th scope="col">Overdue&nbsp;Accounts</th>
                      <th scope="col">Zero&nbsp;Balance&nbsp;Accounts</th>
                      <th scope="col">Current&nbsp;Balance</th>
                      <th scope="col">Score</th>
                      <th scope="col">Report&nbsp;Date</th>
                      <th scope="col">Report</th>
                    </tr>
                </thead>';
		    if(!empty($customer_id))
		    {
		        $conditions = ['CB.customer_id' => $customer_id];
		        $select = 'CB.lead_id, CB.customer_id, CB.cibil_id, CB.cibilScore, CB.cibil_file, CB.memberCode, CB.totalAccount, CB.totalBalance, CB.overDueAccount, CB.created_at, CB.overDueAmount, CB.zeroBalance';
		        $table = 'tbl_cibil CB';
				$cibilData = $this->select($conditions, $select, $table);
	            if($cibilData->num_rows() > 0)
	            {
    				$i = 1;
    				foreach($cibilData->result() as $column)
    				{
    					$id = $column->lead_id;
    				    $data.='<tbody>
                    		<tr>				
    							<td>'.$i.'</td>
    							<td>'.$column->memberCode.'</td>		
    							<td></td>
    							<td>'.strval($column->totalAccount) .'</td>
    							<td>'.strval($column->totalBalance) .'</td>
    							<td>'.strval($column->overDueAccount) .'</td>
    							<td>'.strval($column->zeroBalance) .'</td>
    							<td>'.strval($column->cibilScore) .'</td>
    							<td>'.date('d-m-Y', strtotime($column->created_at)).'</td>
    							<td>
    							    <a href="'. base_url('viewCustomerCibilPDF/'.$column->cibil_id) .'" target="_blank"><i class="fa fa-file-pdf-o" title="View CREDIT BUREAU"></i></a> |  
    							    <a href="'. base_url('viewCustomerCibilPDF/'.$column->cibil_id) .'" target="_blank" download><i class="fa fa-download" title="Download CREDIT BUREAU"></i></a>
    							</td>
    						</tr>';
    					$i++;
    				}
    				return $data .='</tbody></table></div>';
    			}
    			else
    			{
    		    	return $data .='<tbody><tr><td colspan="11" style="text-align: -webkit-center;color:red;">Record Not Found...</td></tr></tbody></table></div>';
    			}
			}else{
				return $data .='<tbody><tr><td colspan="11" style="text-align: -webkit-center;color:red;">Record Not Found...</td></tr></tbody></table></div>';
			}
		}
		
	    public function getOldHistory($lead_id)
	    {
            // $where = ['company_id' => company_id, 'product_id' => product_id];
		    $sql = $this->db->select('pancard, mobile')->where('lead_id', $lead_id)->from('leads')->get();
		    $result = $sql->row();
		    $pancard = $result->pancard;

		    if(empty($pancard)) {
		        $result = $sql->result();
		        foreach($result as $row){
		            if(!empty($row->pancard)){
		                $pancard = $row->pancard;
		                break;
		            }
		        }
		    }
		    $query = $this->db->select('leads.lead_id, leads.name, leads.email, leads.pancard, tb_states.state, leads.created_on, leads.source, leads.status, leads.credit_manager_id, leads.partPayment,
		        loan.loan_amount, loan.loan_no, loan.loan_tenure, loan.loan_intrest, loan.loan_repay_amount, loan.loan_repay_date, loan.loan_disburse_date, loan.loan_admin_fee')
                ->where('leads.pancard', $pancard)
                ->where('leads.loan_approved', 3)
                // ->where($where)
                ->from('leads')
                ->join('tb_states', 'leads.state_id = tb_states.id')
                ->join('loan', 'leads.lead_id = loan.lead_id');
                // ->join('recovery', 'recovery.lead_id = leads.lead_id')
            return $query->get();
	    }
	    

		public function getCAMDetails($lead_id)
		{
            $table1 = 'leads LD';
            $table2 = 'customer C';
            $join2 	= 'C.customer_id = LD.customer_id';
            $table3 = 'credit_analysis_memo CAM';
            $join3 	= 'CAM.lead_id = LD.lead_id';
            $table4 = 'loan L';
            $join4 	= 'L.lead_id = LD.lead_id';

	        $conditions = ['LD.company_id' => company_id, 'LD.product_id' => product_id, 'LD.lead_id' => $lead_id];
	        
            $select = 'LD.lead_id, LD.customer_id, LD.application_no, C.first_name, C.middle_name, C.sur_name, C.email, C.alternate_email, C.gender, C.mobile, C.alternate_mobile, LD.obligations, LD.loan_amount, LD.status, LD.stage, CAM.loan_recommended, CAM.roi, CAM.total_admin_fee, CAM.disbursal_date, CAM.repayment_date, CAM.tenure, CAM.net_disbursal_amount, CAM.repayment_amount, L.loan_no, L.loanAgreementRequest, L.agrementRequestedDate, L.loanAgreementResponse, L.agrementUserIP, L.agrementResponseDate, L.status as loan_status, L.company_ac_no, L.channel, L.mode_of_payment, L.disburse_refrence_no';
	        return $this->Tasks->join_table($conditions, $select, $table1, $table2, $join2, $table3, $join3, $table4, $join4);
		}
        
        public function sendDisbursalMail($lead_id)
        {
        	$sql = $this->getCAMDetails($lead_id);
        	$camDetails = $sql->row();
		    $message = '
	            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	            <html xmlns="http://www.w3.org/1999/xhtml">
	               <head>
	                  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	                  <title>Mailer</title>
	               </head>
	               <body>
	                  <table width="778" border="0" align="center" cellpadding="0" cellspacing="0" style="padding:10px; border:solid 1px #ccc; font-family:Arial, Helvetica, sans-serif;">
	                     <tr>
	                        <td width="381" align="left"><img src="'. base_url('public/front/images/logo.gif') .'" class="img-responsive" alt="logo"/></td>
	                        <td width="11" align="left">&nbsp;</td>
	                        <td width="384" align="right">
	                           <table width="100%" border="0">
	                              <tr>
	                                 <td align="right"><strong style="line-height:25px;">Dear &nbsp;'. $camDetails->first_name .'</strong></td>
	                              </tr>
	                              <tr>
	                                 <td align="right">Loan No.: -</td>
	                              </tr>
	                           </table>
	                        </td>
	                     </tr>
	                     <tr>
	                        <td colspan="3">
	                           <hr / style="background:#ddd !important;">
	                        </td>
	                     </tr>
	                     <tr>
	                        <td colspan="3">&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td><strong>Loanwalle app Lead partner of Naman Finlease Pvt. Ltd.</strong></td>
	                        <td>&nbsp;</td>
	                        <td rowspan="4" align="center" valign="top"><img class="img-responsive" src="https://www.loanwalle.com/public/front/images/image-loan.jpg" width="384" height="406" / style="border:solid 1px #ccc; padding:10px;"></td>
	                     </tr>
	                     <tr>
	                        <td><span style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px; text-align:justify;">Thank you for choosing us and giving us the opportunity to be of service to you. Hope you are satisfied with us.</span></td>
	                        <td>&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td><span style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px; text-align:justify;">In order to avail this loan, you have already filled up and submitted a loan application and signed our loan agreement in acceptance of the terms and conditions. </span></td>
	                        <td>&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td><span style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px; text-align:justify;">To avoid any kind of ambiguity in future we are sending you this term sheet detailing the terms and conditions of the loan. Please go through this  carefully and give your approval so we can go forth and disburse the loan amount.</span></td>
	                        <td>&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td align="center" valign="top">&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td colspan="3" style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px;"><strong>Loanwalle.com Powered by Naman Finlease Pvt. Ltd. (RBI approved NBFC) </strong></td>
	                     </tr>
	                     <tr>
	                        <td colspan="3" style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px;"><strong>S-370 Panchsheel Park, Near Panchsheel Park Metro Station Gate No.1, New Delhi- 110017 </strong></td>
	                     </tr>
	                     <tr>
	                        <td colspan="3" style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px;"><strong>Loan Terms to be agreed by the Customer</strong></td>
	                     </tr>
	                     <tr>
	                        <td colspan="3" style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px;">&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td colspan="3">
	                           <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
	                              <tr>
	                                 <td width="42%" bgcolor="#FFFFFF" style="padding:10px;">Name </td>
	                                 <td width="58%" bgcolor="#FFFFFF" style="padding:10px;">'. $camDetails->first_name .'</td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Loan Amount</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Rs. '. ($camDetails->loan_recommended).' /- </td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Rate of Interest (%)</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">'. $camDetails->roi .' per day</td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Disbursal Date</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">'. $camDetails->disbursal_date .' </td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Commitment Payback Date</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">'. $camDetails->repayment_date .'</td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Repayment Amount</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">'. $camDetails->repayment_amount .' </td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Period</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">'. $camDetails->tenure .' </td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Penalty (%)</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">2.00</td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Administrative Fee </td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Rs. '. $camDetails->total_admin_fee .'/-</td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Repayment Cheque(s)</td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">&nbsp;-</td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Cheque drawn on (Bank Name) </td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">&nbsp;-</td>
	                              </tr>
	                              <tr>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Cheque &amp;NACHBouncing Charges </td>
	                                 <td bgcolor="#FFFFFF" style="padding:10px;">Rs. 1000/- every time</td>
	                              </tr>
	                           </table>
	                        </td>
	                     </tr>
	                     <tr>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td colspan="3" style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px;">Non-payment of loan on time will affect your CIBIL Score and will affect your chance of getting <br />
	                           further loans 
	                        </td>
	                     </tr>
	                     <tr>
	                        <td colspan="3" style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px;">from Banks and financial institutions  In case your cheque bounces we are liable to take legal action u/s 138 of IPC  </td>
	                     </tr>
	                     <tr>
	                        <td colspan="3" style="font-size:17px;
	                           line-height: 25px;
	                           padding-bottom: 6px;">Please send confirmation mail that you are agreed with the above term.</td>
	                     </tr>
	                     <tr>
	                        <td style="padding-bottom:10px; padding-top:10px;"><strong>Best Regards,  </strong></td>
	                        <td style="padding-bottom:10px; padding-top:10px;">&nbsp;</td>
	                        <td>&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td><strong>Team Loanwalle</strong></td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                     </tr>
	                     <tr>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
                        </tr>
	                     <tr>
	                        <td>
		                        <form action="'. base_url('loanAgreementLetterResponse') .'" method="post" enctype="mutlipart-from/data">
                    				<input type="hidden" name="'. $this->security->get_csrf_token_name() .'" value="'. $this->security->get_csrf_hash() .'" />
		                        	<input type="hidden" name="lead_id" value="'. $lead_id .'">
		                        	<input type="hidden" name="email" value="'. $camDetails->email .'">
		                        	<input type="hidden" name="response" value="1">
		                        	<input type="hidden" name="ip" value="'. $_SERVER['REMOTE_ADDR'] .'">
		                        	<input type="submit" name="Accept Terms & Conditions" value="Accept Terms & Conditions" style="color: #fff; background: #0d70ac; border-bottom: none !important; padding: 12px 9px !important;">
		                        </form>
	                        </td>
	                     </tr>
	                     <tr>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                     </tr>
	                  </table>
	               </body>
	            </html>
	        ';

	        // echo "<pre>"; print_r($message); exit;

	        $from = 'info@loanwalle.com';
	        $conditions = ['company_id' => company_id];
	        $fetch = '*';
	        $sql = $this->select($conditions, $fetch, 'tbl_email_credentails');
	        
	        $config['protocol']     = 'sendmail';
	        $config['smtp_host']    = "smtp.gmail.com";
	        $config['smtp_port']    = 25;
	        $config['smtp_user']    = $sql->smtp_user;
	        $config['smtp_pass']    = $sql->smtp_pass;
	        $config['charset']      = 'iso-8859-1';
	        $config['wordwrap']     = TRUE;
	        $config['mailtype']     = 'html';
	        $config['priority']     = '1';
	        
	        $this->load->library('email');
	        $this->email->initialize($config);
	        $this->email->set_newline("\r\n");
	        $this->email->from($from);
	        $this->email->to($camDetails->email);
	        $this->email->bcc("info@loanwalle.com, vinay.kumar@loanwalle.com");
	        $this->email->subject('Loan Disbursal Letter - Loanwalle');
	        $this->email->message($message);
	        
	        if($this->email->send() == true){
	        	$data = 'true';
	        }else{
	        	$data = 'false';
	        }
	        return $data;
        }
		
    }
?>