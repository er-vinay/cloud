<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class CibilController extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Task_Model', 'Tasks');
        }

        public function index()
        {
            if ($this->input->server('REQUEST_METHOD') == 'POST') 
            {   
                $lead_id = $this->input->post('lead_id');
                $fetch = 'C.customer_id, C.first_name, C.middle_name, C.sur_name, C.mobile, C.dob, C.pancard, C.gender, LD.state_id, LD.city, LD.pincode, LD.check_cibil_status, LD.loan_amount';
                $conditions = ['LD.lead_id' => $lead_id];
                $table1 = 'leads LD';
                $table2 = 'customer C';
                $join2 = 'C.customer_id = LD.customer_id';
                $query = $this->Tasks->join_two_table_with_where($conditions, $fetch, $table1, $table2, $join2);
                $leadDetails = $query->row();


                // if($leadDetails->check_cibil_status == 0)
                // {
                    if(!empty($lead_id))
                    {
                        $m_name = str_replace("-", "", $leadDetails->middle_name);
                        $s_name = str_replace("-", "", $leadDetails->sur_name);
                        $customer_id= $leadDetails->customer_id;
                        $name       = $leadDetails->first_name;
                        $mobile     = $leadDetails->mobile;
                        $pancard    = $leadDetails->pancard;
                        $gender     = $leadDetails->gender;
                        $dob        = $leadDetails->dob;
                        $state_id   = $leadDetails->state_id;
                        $city       = $leadDetails->city;
                        $address    = $leadDetails->city;
                        $pincode    = $leadDetails->pincode;
                        $loan_amount    = ($leadDetails->loan_amount) ? round($leadDetails->loan_amount) : 5000;
    
                        if(empty($name) || empty($mobile) || empty($pancard) || empty($gender) || empty($dob) || empty($state_id) || empty($city) || empty($pincode))
                        {
                            foreach($leadDetails as $key => $value) {
                                if(empty($value)){
                                    $error .= $key .", ";
                                }
                            }
                            $json['err'] = "Required ". $error ."Please Update.";
                            echo json_encode($json);
                            return false;
                        } 
                        else 
                        {
                            $day = date('d', strtotime($dob));
                            $month = date("m", strtotime($dob));
                            $year = date("Y", strtotime($dob));
                            $dateOfBirth = $day.''.$month.''.$year;
                            
                            $scoreType = '08';
                            $purpose = '06';  // 01 - 06
                            $solutionSetId = '140';
            
                            $query_state = $this->db->select("state")->where("state_id", $state_id)->get('tbl_state')->row_array();
                            $stateName = ucwords(strtolower($query_state['state']));
                            
                            $stateNameData = array(
                                '01' => 'Jammu & Kashmir',
                                '02' => 'Himachal Pradesh',
                                '03' => 'Punjab',
                                '04' => 'Chandigarh',
                                '05' => 'Uttaranchal',
                                '06' => 'Haryana',
                                '07' => 'Delhi',
                                '08' => 'Rajasthan',
                                '09' => 'Uttar Pradesh',
                                '10' => 'Bihar',
                                '11' => 'Sikkim',
                                '12' => 'Arunachal Pradesh',
                                '13' => 'Nagaland',
                                '14' => 'Manipur',
                                '15' => 'Mizoram',
                                '16' => 'Tripura',
                                '17' => 'Meghalaya',
                                '18' => 'Assam',
                                '19' => 'West Bengal',
                                '20' => 'Jharkhand',
                                '21' => 'Orissa',
                                '22' => 'Chhattisgarh',
                                '23' => 'Madhya Pradesh',
                                '24' => 'Gujarat',
                                '25' => 'Daman & Diu',
                                '26' => 'Dadra & Nagar Haveli',
                                '27' => 'Maharashtra',
                                '28' => 'Andhra Pradesh',
                                '29' => 'Karnataka',
                                '30' => 'Goa',
                                '31' => 'Lakshadweep',
                                '32' => 'Kerala',
                                '33' => 'Tamil Nadu',
                                '34' => 'Pondicherry',
                                '35' => 'Andaman & Nicobar Islands',
                                '36' => 'Telangana'
                            );
    
                            $stateKey = array_search($stateName, $stateNameData);
                        
                            // $define_url = "UAT";
                            $define_url = "LIVE";
                            
                            if($define_url == "UAT") 
                            {
                                define("userId", "NB4235DC01_UAT001");
                                define("password", "TempPass@cibil2");
                                define("memberId", "NB42358888_UATC2C");
                                define("memberPass", "2iqzapqOkcqgmf@qnvni");
                                define("api_url", "https://www.test.transuniondecisioncentre.co.in/DC/TU/TU.IDS.ExternalServices/SolutionExecution/ExternalSolutionExecution.svc");
                                
                                $day = date('dd', strtotime($dob));
                                $month = date("mm", strtotime($dob));
                                $year = date("YYYY", strtotime($dob));
                                $dateOfBirth = $day.''.$month.''.$year;
                                
                                $name = "BHIMSERI SHYAM";
                                $mobile = 9823511150;
                                $pancard = "ACYPB6874F";
                                $gender = "Male";
                                $dateOfBirth = "05151963";
                                $address = "SHASHI INVESTMENT";
                                $stateKey = 07;
                                $city = "Delhi";
                                $pincode = 110001;
                            } else {
                                define("userId", "NB4235DC01_PROD002");
                                define("password", "Lo@anwalle15Dec2020");
                                define("memberId", "NB42358899_CIRC2C");
                                define("memberPass", "Ce8#Yh8@Py8@Dh");
                                define("api_url", "https://www.dc.transuniondecisioncentre.co.in/DE/TU.IDS.ExternalServices/SolutionExecution/ExternalSolutionExecution.svc");
                            }
                            
                            $input_xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                                    <soapenv:Header />
                                    <soapenv:Body>
                                      <tem:ExecuteXMLString>
                                        <tem:request>
                                          <![CDATA[ 
                            
                                  <DCRequest xmlns="http://transunion.com/dc/extsvc">
                                    <Authentication type="OnDemand">
                                        <UserId>'. userId .'</UserId>
                                        <Password>'. password .'</Password>
                                     </Authentication>
                                     <RequestInfo>
                                          <SolutionSetId>140</SolutionSetId>
                                          <ExecuteLatestVersion>true</ExecuteLatestVersion>
                                          <ExecutionMode>NewWithContext</ExecutionMode>
                                    </RequestInfo>
                                    <Fields>
                                      <Field key="Applicants">
                                       
                                            &lt;Applicants&gt;
                                    &lt;Applicant&gt;
                                      &lt;ApplicantType&gt;Main&lt;/ApplicantType&gt;
                                      &lt;ApplicantFirstName&gt;'. $name .'&lt;/ApplicantFirstName&gt;
                                      &lt;ApplicantMiddleName&gt;'. $m_name .'&lt;/ApplicantMiddleName&gt;
                                      &lt;ApplicantLastName&gt;'. $s_name .'&lt;/ApplicantLastName&gt;
                                      &lt;DateOfBirth&gt;'. $dateOfBirth .'&lt;/DateOfBirth&gt;
                                      &lt;Gender&gt;'. $gender .'&lt;/Gender&gt;
                                      &lt;EmailAddress&gt;&lt;/EmailAddress&gt;
                                      &lt;CompanyName&gt;&lt;/CompanyName&gt;
                                      &lt;Identifiers&gt;
                                        &lt;Identifier&gt;
                                          &lt;IdNumber&gt;'. $pancard .'&lt;/IdNumber&gt;
                                          &lt;IdType&gt;01&lt;/IdType&gt;
                                        &lt;/Identifier&gt;
                                        &lt;Identifier&gt;
                                          &lt;IdNumber&gt;&lt;/IdNumber&gt;
                                          &lt;IdType&gt;06&lt;/IdType&gt;
                                        &lt;/Identifier&gt;
                                      &lt;/Identifiers&gt;
                                      &lt;Telephones&gt;
                                        &lt;Telephone&gt;
                                          &lt;TelephoneExtension&gt;&lt;/TelephoneExtension&gt;
                                          &lt;TelephoneNumber&gt;'. $mobile .'&lt;/TelephoneNumber&gt;
                                          &lt;TelephoneType&gt;01&lt;/TelephoneType&gt;
                                        &lt;/Telephone&gt;
                                         &lt;Telephone&gt;
                                          &lt;TelephoneExtension&gt;&lt;/TelephoneExtension&gt;
                                          &lt;TelephoneNumber&gt;&lt;/TelephoneNumber&gt;
                                          &lt;TelephoneType&gt;01&lt;/TelephoneType&gt;
                                        &lt;/Telephone&gt;
                                      &lt;/Telephones&gt;
                                      &lt;Addresses&gt;
                                        &lt;Address&gt;
                                          &lt;AddressLine1&gt;'. $address .'&lt;/AddressLine1&gt;
                                          &lt;AddressLine2&gt;&lt;/AddressLine2&gt;
                                          &lt;AddressLine3&gt;&lt;/AddressLine3&gt;
                                          &lt;AddressLine4&gt;&lt;/AddressLine4&gt;
                                          &lt;AddressLine5&gt;&lt;/AddressLine5&gt;
                                          &lt;AddressType&gt;01&lt;/AddressType&gt;
                                          &lt;City&gt;'. $city .'&lt;/City&gt;
                                          &lt;PinCode&gt;'. $pincode .'&lt;/PinCode&gt;
                                          &lt;ResidenceType&gt;01&lt;/ResidenceType&gt;
                                          &lt;StateCode&gt;'. $stateKey .'&lt;/StateCode&gt;
                                        &lt;/Address&gt;
                                      &lt;/Addresses&gt;
                                      &lt;NomineeRelation&gt;&lt;/NomineeRelation&gt;
                                      &lt;NomineeName&gt;&lt;/NomineeName&gt;
                                      &lt;MemberRelationType4&gt;&lt;/MemberRelationType4&gt;
                                      &lt;MemberRelationName4&gt;&lt;/MemberRelationName4&gt;
                                      &lt;MemberRelationType3&gt;&lt;/MemberRelationType3&gt;
                                      &lt;MemberRelationName3&gt;&lt;/MemberRelationName3&gt;
                                      &lt;MemberRelationType2&gt;&lt;/MemberRelationType2&gt;
                                      &lt;MemberRelationName2&gt;&lt;/MemberRelationName2&gt;
                                      &lt;MemberRelationType1&gt;&lt;/MemberRelationType1&gt;
                                      &lt;MemberRelationName1&gt;&lt;/MemberRelationName1&gt;
                                      &lt;KeyPersonRelation&gt;&lt;/KeyPersonRelation&gt;
                                      &lt;KeyPersonName&gt;&lt;/KeyPersonName&gt;
                                      &lt;MemberOtherId3&gt;&lt;/MemberOtherId3&gt;
                                      &lt;MemberOtherId3Type&gt;&lt;/MemberOtherId3Type&gt;
                                      &lt;MemberOtherId2&gt;&lt;/MemberOtherId2&gt;
                                      &lt;MemberOtherId2Type&gt;&lt;/MemberOtherId2Type&gt;
                                      &lt;MemberOtherId1&gt;&lt;/MemberOtherId1&gt;
                                      &lt;MemberOtherId1Type&gt;&lt;/MemberOtherId1Type&gt;
                                      &lt;Accounts&gt;
                                        &lt;Account&gt;
                                          &lt;AccountNumber&gt;&lt;/AccountNumber&gt;
                                        &lt;/Account&gt;
                                      &lt;/Accounts&gt;
                                    &lt;/Applicant&gt;
                                  &lt;/Applicants&gt;
                            
                                  </Field>
                                  <Field key="ApplicationData">
                                   &lt;ApplicationData&gt;
                                  &lt;Purpose&gt;10&lt;/Purpose&gt;
                                  &lt;Amount&gt;'. $loan_amount .'&lt;/Amount&gt;
                                  &lt;ScoreType&gt;08&lt;/ScoreType&gt;
                                  &lt;GSTStateCode&gt;07&lt;/GSTStateCode&gt;
                                  
                                  
                                  &lt;MemberCode&gt;'. memberId .'&lt;/MemberCode&gt;
                                  &lt;Password&gt;'. memberPass .'&lt;/Password&gt;
                                  
                                  
                                  &lt;CibilBureauFlag&gt;False&lt;/CibilBureauFlag&gt;
                                    &lt;DSTuNtcFlag&gt;True&lt;/DSTuNtcFlag&gt;
                                    &lt;IDVerificationFlag&gt;False&lt;/IDVerificationFlag&gt;
                                    &lt;MFIBureauFlag&gt;True&lt;/MFIBureauFlag&gt;
                                    &lt;NTCProductType&gt;PL&lt;/NTCProductType&gt;
                                    &lt;ConsumerConsentForUIDAIAuthentication&gt;N&lt;/ConsumerConsentForUIDAIAuthentication&gt;
                                    &lt;MFIEnquiryAmount&gt;&lt;/MFIEnquiryAmount&gt;
                                    &lt;MFILoanPurpose&gt;&lt;/MFILoanPurpose&gt;
                                    &lt;MFICenterReferenceNo&gt;&lt;/MFICenterReferenceNo&gt;
                                    &lt;MFIBranchReferenceNo&gt;&lt;/MFIBranchReferenceNo&gt;
                                    &lt;FormattedReport&gt;True&lt;/FormattedReport&gt;
                                &lt;/ApplicationData&gt;
                            
                               
                                            </Field>
                                            <Field key="FinalTraceLevel">2</Field>
                                            </Fields>
                                            </DCRequest>        
                                       ]]>
                                    </tem:request>
                                  </tem:ExecuteXMLString>
                                </soapenv:Body>
                              </soapenv:Envelope>';
                
                            $url = api_url;
                
                            $headers = [
                                'Content-Type: text/xml', 
                                'soapAction: http://tempuri.org/IExternalSolutionExecution/ExecuteXMLString'
                            ];
                            // echo "<pre>".$input_xml; exit;
                            
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL,$url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            // curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            
                            $dataResponse = curl_exec($ch); 
                            
                            $soap = simplexml_load_string($dataResponse);
                            $response = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->ExecuteXMLStringResponse;
                            $xx = $response->ExecuteXMLStringResult;
                            $xx = simplexml_load_string($xx);
                            $ApplicationId = (string)$xx->ResponseInfo->ApplicationId;
                            
                            $data = [
                                'lead_id'               => $lead_id,
                                'customer_id'           => $customer_id,
                                'applicationId'         => $ApplicationId,
                                'created_at'            => timestamp,
                            ];

                            $data2 = [
                                'lead_id'               => $lead_id,
                                'customer_id'           => $customer_id,
                                'customer_name'         => $name,
                                'customer_mobile'       => $mobile,
                                'pancard'               => $pancard,
                                'loan_amount'            => $loan_amount,
                                'dob'                   => $dateOfBirth,
                                'gender'                => $gender,
                                'city'                  => $city,
                                'state_id'              => $state_id,
                                'pincode'               => $pincode,
                                'api1_request'          => $input_xml,
                                'api1_response'         => $dataResponse,
                                'applicationId'         => $ApplicationId,
                            ];
                            $this->db->insert('tbl_cibil', $data);
                            $this->db->insert('tbl_cibil_log', $data2);
                            $this->getApplication($lead_id, $ApplicationId, $customer_id);

                            // echo "else called : <pre>"; print_r($dataResponse); exit;
                            curl_close($ch);
                            
                        }
                    } else {
                        $json['err'] = "Lead Id is Required";
                        echo json_encode($json);
                    }
                // } else {
                //     $json['msg'] = "Recently cibil generated.";
                //     echo json_encode($json);
                //     return false;
                // }
            }
        }
        
        public function getApplication($lead_id, $ApplicationId, $customer_id)
        {
            $xml2 = '
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                    <soapenv:Header />
                    <soapenv:Body>
                      <tem:RetrieveDocumentMetaDataXMLString>
                        <tem:request>
                          <![CDATA[ 
                  <DCRequest xmlns="http://transunion.com/dc/extsvc">
                <Authentication type="Token">
                    <UserId>'. userId .'</UserId>
                    <Password>'. password .'</Password>
                </Authentication>
                <RetrieveDocumentMetaData>
                <ApplicationId>'. $ApplicationId .'</ApplicationId>
                </RetrieveDocumentMetaData>
                </DCRequest>        
                ]]>
                    </tem:request>
                  </tem:RetrieveDocumentMetaDataXMLString>
                </soapenv:Body>
                </soapenv:Envelope>
            ';
            
            $url2 = api_url;

            $ch2 = curl_init();
            $headers2 = [
                    'Content-Type: text/xml', 
                    'soapAction: http://tempuri.org/IExternalSolutionExecution/RetrieveDocumentMetaDataXMLString'
                ];
            
            curl_setopt($ch2, CURLOPT_URL,$url2);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch2, CURLOPT_POST, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, $xml2);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);
            
            $data2 = curl_exec($ch2); 
            $soap = simplexml_load_string($data2);
            file_put_contents('text.txt', $data2);
            $response = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->RetrieveDocumentMetaDataXMLStringResponse;
            
            $xx=$response->RetrieveDocumentMetaDataXMLStringResult;
            $xx=simplexml_load_string($xx);
            $documentId = (string)$xx->ResponseInfo->DocumentDetails->DocumentMetaData->DocumentId;
            
            $data = ['document_Id' => $documentId];

            $data2 = [
                    'api2_request'          => $xml2,
                    'api2_response'         => $data2,
                    'document_Id'           => $documentId,
                ];
                
            $this->db->where('lead_id', $lead_id)->update('tbl_cibil', $data);
            $this->db->where('lead_id', $lead_id)->update('tbl_cibil_log', $data2);
            $this->getDocument($lead_id, $ApplicationId, $documentId, $customer_id);
        }
        
        public function getDocument($lead_id, $ApplicationId, $documentId, $customer_id)
        {
            $xml3 = '
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                        <soapenv:Header />
                        <soapenv:Body>
                          <tem:DownloadDocument>
                            <tem:request>
                              <![CDATA[ 
                    <DCRequest xmlns="http://transunion.com/dc/extsvc">
                          <Authentication type="Token">
                                <UserId>'. userId .'</UserId>
                                <Password>'. password .'</Password>
                          </Authentication>
                          <DownloadDocument>
                            <ApplicationId>'. $ApplicationId .'</ApplicationId>
                            <DocumentId>'. $documentId .'</DocumentId>
                          </DownloadDocument>
                        </DCRequest>        
                           ]]>
                        </tem:request>
                      </tem:DownloadDocument>
                    </soapenv:Body>
                  </soapenv:Envelope>
            ';
            
            $url3 = api_url;
            
            $ch3 = curl_init();
            $headers3 = [
                    'Content-Type: text/xml', 
                    'soapAction: http://tempuri.org/IExternalSolutionExecution/DownloadDocument'
                ];
            
            curl_setopt($ch3, CURLOPT_URL,$url3);
            curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch3, CURLOPT_POST, true);
            curl_setopt($ch3, CURLOPT_POSTFIELDS, $xml3);
            curl_setopt($ch3, CURLOPT_HTTPHEADER, $headers3);
            
            $data3 = curl_exec($ch3);
            $filename = base64_decode($data3);
            
            $newFile = strstr($filename, "<?xml");
            $file = substr($newFile, 0, strpos($newFile, "</html>"));
            $file .= "</html>";
            $htmlResult = preg_replace('/&(?!(quot|amp|pos|lt|gt);)/', '&amp;', $file);
            
            $result = mb_convert_encoding($htmlResult, 'UTF-16', 'UTF-8');
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($result); //or simplexml_load_file
            
            foreach( libxml_get_errors() as $error ) {
                print_r($error);
            }
            
            if (false === $result)
            {
                throw new Exception('Input string could not be converted.');
            }
            $xml = simplexml_load_string( $result) or die("xml not loading");
            // echo "<pre>"; print_r($xml); exit;
            $cibilScore = $xml->body->table->tr[8]->td->table->tr->td[1];
            // $data = [   
            //     'cibil_file'            => $htmlResult,
            //     'memberCode'            => $xml->body->table->tr[1]->td->table->tr[1]->td[0]->table->tr[1]->td[1],
            //     'cibilScore'            => ($cibilScore) ? $cibilScore : 0,
            //     'totalAccount'          => strval($xml->body->table->tr[29]->td->table->tr[3]->td[1]->span[0]),
            //     'totalBalance'          => strval($xml->body->table->tr[29]->td->table->tr[3]->td[2]->span[0]),
            //     'overDueAccount'        => strval($xml->body->table->tr[29]->td->table->tr[4]->td[1]->span[0]),
            //     'overDueAmount'         => strval($xml->body->table->tr[29]->td->table->tr[4]->td[3]->span[0]),
            //     'zeroBalance'           => strval($xml->body->table->tr[29]->td->table->tr[5]->td[1]->span[0])
            // ];


            $summary = $xml->body->table->tr[29]->td->table->tr[3]; //->td[1]
            $overdue = $xml->body->table->tr[29]->td->table->tr[4]; //->td[1]
            $zerobalanceAcccount = $xml->body->table->tr[29]->td->table->tr[5]; //->td[1]
            $totalAccount = 0;
            $totalBalance = 0;
            $overDueAccount = 0;
            $overDueAmount = 0;
            $zeroBalance = 0;
            $i = 0;

            foreach($summary->children() as $key =>$child) {
                if($i == 6){
                    $totalAccount = $child;
            echo $i. "overdue node: " . $child->getName(). " = ". $key ." val : " . $child . "</br>";
                } else if($i == 7){
                    $totalBalance = $child;
            echo $i. "totalBalance node: " . $child->getName(). " = ". $key ." val : " . $child . "</br>";
                } else if($i == 8){
                    $overDueAccount = $child;
            echo $i. "overDueAccount node: " . $child->getName(). " = ". $key ." val : " . $child . "</br>";
                }
                $i++;
            }
            
            foreach($overdue->children() as $key =>$child) {
                if($i == 1){
            // echo $i. "overdue node: " . $child->getName(). " = ". $key ." val : " . $child . "</br>";
                    $overDueAmount = $child;
                }
                $i++;
            }
            foreach($zerobalanceAcccount->children() as $key =>$child) {
                if($i == 11){
                    $zeroBalance = $child;
                }
                $i++;
            }
            // echo '<pre>'; print_r($summary->children()); exit;
            
            $data = [
                'memberCode'     => $xml->body->table->tr[1]->td->table->tr[1]->td[0]->table->tr[1]->td[1],
                'cibilScore'     => ($cibilScore) ? $cibilScore : 0,
                'totalAccount'   => $totalAccount,
                'totalBalance'   => $totalBalance,
                'overDueAccount' => $overDueAccount,
                'overDueAmount'  => $overDueAmount,
                'zeroBalance'    => $zeroBalance
            ];

            $data2 = [
                'api3_request'          => $xml3,
                'api3_response'         => $data3,
                'cibil_file'            => $htmlResult,
                'memberCode'            => $xml->body->table->tr[1]->td->table->tr[1]->td[0]->table->tr[1]->td[1],
                'cibilScore'            => ($cibilScore) ? $cibilScore : 0,
                'totalAccount'          => $totalAccount,
                'totalBalance'          => $totalBalance,
                'overDueAccount'        => $overDueAccount,
                'overDueAmount'         => $overDueAmount,
                'zeroBalance'           => $zeroBalance
            ];
            $this->db->where('lead_id', $lead_id)->update('leads', ['check_cibil_status'=> 1, 'cibil'=> $cibilScore]); 
            $this->db->where('lead_id', $lead_id)->update('tbl_cibil', $data); 
            $this->db->where('lead_id', $lead_id)->update('tbl_cibil_log', $data2); 
            $json['customer_id'] = $customer_id;
            $json['msg'] = 'Cibil generated successfully.';
            echo json_encode($json);
        }
        
        public function viewCibilScore($cibil_id)
        {
            $data = $this->getCibilFile($cibil_id);
            $filename = $data['cibil_file'];
            
            if ($filename) 
            {
                $file1 = file_get_contents('readdata.xml', $filename);
                $newFile = strstr($filename, "<?xml");
                $file = substr($newFile, 0, strpos($newFile, "</html>"));
                $file .= "</html>";
                $temp = preg_replace('/&(?!(quot|amp|pos|lt|gt);)/', '&amp;', $file);
                // echo "<pre>"; print_r($temp); exit;
                $result = mb_convert_encoding($temp, 'UTF-16', 'UTF-8');

                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($result); //or simplexml_load_file

                // echo "<pre>"; print_r($result); exit;
                foreach( libxml_get_errors() as $error ) {
                    print_r($error);
                }
                
                if (false === $result)
                {
                    throw new Exception('Input string could not be converted.');
                }
                $xml = simplexml_load_string( $result) or die("xml not loading");
                $summary = $xml->body->table->tr[29]->td->table->tr[3]; //->td[1]
                $overdue = $xml->body->table->tr[29]->td->table->tr[4]; //->td[1]
                $zerobalanceAcccount = $xml->body->table->tr[29]->td->table->tr[5]; //->td[1]
                $totalAccount = 0;
                $totalBalance = 0;
                $overDueAccount = 0;
                $overDueAmount = 0;
                $zeroBalance = 0;
                $i = 0;
                foreach($overdue->children() as $key =>$child) {
                    if($i == 1){
                // echo $i. "overdue node: " . $child->getName(). " = ". $key ." val : " . $child . "</br>";
                        $overDueAmount = $child;
                    }
                    $i++;
                }
                foreach($summary->children() as $key =>$child) {
                    if($i == 6){
                        $totalAccount = $child;
                echo $i. "totalAccount node: " . $child->getName(). " = ". $key ." val : " . $totalAccount . "</br>";
                    } else if($i == 7){
                        $totalBalance = $child;
                echo $i. "totalBalance node: " . $child->getName(). " = ". $key ." val : " . $totalBalance . "</br>";
                    } else if($i == 8){
                        $overDueAccount = $child;
                echo $i. "overDueAccount node: " . $child->getName(). " = ". $key ." val : " . $overDueAccount . "</br>";
                    }
                    $i++;
                }
                
                foreach($zerobalanceAcccount->children() as $key =>$child) {
                    if($i == 11){
                        $zeroBalance = $child;
                    }
                    $i++;
                }
                // echo 'totalAccount : <pre>'. $totalAccount. "<br>"; 
                // echo 'totalBalance : <pre>'. $totalBalance. "<br>"; 
                // echo 'overDueAccount : <pre>'. $overDueAccount. "<br>"; 
                // echo 'overDueAmount : <pre>'. $overDueAmount. "<br>"; 
                // echo 'zeroBalance : <pre>'. $zeroBalance. "<br>"; 

                print_r($summary->children()); exit;
                
                $data = [
                    'memberCode'     => $xml->body->table->tr[1]->td->table->tr[1]->td[0]->table->tr[1]->td[1],
                    'cibilScore'     => $xml->body->table->tr[8]->td->table->tr->td[1],
                    'totalAccount'   => $totalAccount,
                    'totalBalance'   => $totalBalance,
                    'overDueAccount' => $overDueAccount,
                    'overDueAmount'  => $overDueAmount,
                    'zeroBalance'    => $zeroBalance
                ];
                // // $this->db->where('cibil_id', $cibil_id)->update('tbl_cibil');
                
                
              // echo "<pre>";print_r($xml->body->table->tr[1]->td->table->tr[1]->td[0]->table); exit;
            } else {
                exit('Failed to open readdata.xml.');
            }
        }
        
        public function ViewCivilStatement()
        {
            $json = '';
            if(!empty($_POST['customer_id']))
            {
                $json = $this->Tasks->ViewCivilStatement($_POST['customer_id']);
                echo json_encode($json); 
            }else{
                echo json_encode($json); 
            }
        }
        
        public function getCibilFile($cibil_id)
        {
            if(!empty($cibil_id))
            {
                $result = $this->db->select('tbl_cibil.cibil_file')->where('cibil_id', $cibil_id)->get('tbl_cibil')->row();
                $data = ['cibil_file' => $result->cibil_file];
                return $data;
            }
        }
        
        public function viewCustomerCibilPDF($cibil_id)
        {
            $data = $this->getCibilFile($cibil_id);
            echo $data['cibil_file'];
        }
        
        public function downloadCibilPDF($cibil_id)
        {
            // exit;
            $data = $this->getCibilFile($cibil_id);
            $filename = $data['cibil_file'];

            // $dom = new DOMDocument;
            // $dom->preserveWhiteSpace = FALSE;
            // $dom->loadXML($filename);
            // if($dom->save(APPPATH.'cibil.xml')){
            //     echo "<h2>Site Map Created SuccessFully</h2>";
            // }else{
            //     echo "<h2>Site Map Created Failed</h2>";
            // }

            // $this->output->set_content_type('text/xml');
            // $this->output->set_output($filename);

            header('Content-type: text/xml');
            header('Content-Disposition: attachment; filename="text.xml"');

            echo $xml_contents;

        }
        
        public function downloadcibil($cibil_id)
        {
            
            $data = $this->getCibilFile($cibil_id);
            $filename = $data['cibil_file'];
            file_put_contents(APPPATH."/views/cibil.php", $filename);
            
            $html = $this->load->view(utf8_encode("cibil"));
            
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteText($html);
            $mpdf->defaultfooterline = 1;
            $mpdf->Output('MyPDF.pdf', 'D');
        }
        
        public function checkpdf()
        {
            $result = $this->db->select('tbl_cibil.cibil_file, tbl_cibil.customer_name')->where('cibil_id', "1718")->get('tbl_cibil')->row();
            $mpdf = new \Mpdf\Mpdf();
            $html = $this->load->view('Tasks/cibilpdfview', $data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
        
        public function cibilpdfView($cibil_id)
        {
            $result = $this->db->select('tbl_cibil.cibil_file, tbl_cibil.customer_name')->where('cibil_id', $cibil_id)->get('tbl_cibil')->row(); 
                $data = [
                    'customer_name' =>$result->customer_name,
                    'cibil_file'    =>$result->cibil_file
                    ];
            return $this->load->view('Tasks/cibilpdfview', $data);        
        }

        public function viewDownloadCibilPDF($cibil_id)
        {
            $data = $this->getCibilFile($cibil_id);
            $filename = $data['cibil_file'];
            
            // $pth    =   file_get_contents($filename);
            // $nme    =   $customer_name. "cibil_". todayDate .".pdf";
            // $nme    =   file_get_contents('cibil.pdf', $filename);
            // force_download($nme, $pth); 
            // force_download($nme, $filename); 
            
            $data = file_get_contents('cibil.pdf', $filename);
            // $data = file_get_contents($filename.".pdf");
            // force_download('file.pdf', $data);
            
          $mpdf = new \Mpdf\Mpdf();
          // $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '']);
          $mpdf->SetProtection(array());
         // $html = $this->load->view('pdf', $data, true);
          $mpdf->WriteHTML($data);
          $mpdf->defaultfooterline = 1;
          $mpdf->Output();
        }
        
        
    }

?>