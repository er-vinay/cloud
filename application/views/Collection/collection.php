<?php $getVerificationdata=getVerificationdata('tbl_verification',$leadDetails->lead_id); 

$getCollectiondata=getVerificationdata('collection',$leadDetails->lead_id); 
//echo "<pre>";print_r($getCollectiondata);
?>
<!------- table structure for varification form ----------->


<div class="table-responsive">

        <table class="table table-hover table-striped table-bordered">
            <tr>
                <th>Loan No.</th>
                <td><input type="text" value="<?php  if( isset($getVerificationdata[0]['mobile_verified'])=='' || isset($getVerificationdata[0]['mobile_verified'])=='-' )  { echo "NO"; } else { echo "YES"; }?>" readonly class="form-control inputField" id="mobileVerified" name="mobileVerified" autocomplete="off"></td>
                <th>Status</th>
                <td><input type="text" readonly class="form-control inputField" id="alternateMobileVarification" name="alternateMobileVarification" value="<?php  if(isset($getVerificationdata[0]['alternate_mobile_verified'])=='' || isset($getVerificationdata[0]['alternate_mobile_verified'])=='-' )  { echo "NO"; } else { echo "YES"; }?>" autocomplete="off"></td>
            </tr>
            <tr>
                <th>Application No. </th>
                <td><input type="datetime-local" readonly class="form-control inputField" id="OfficeEmailVerificationSentOn" name="OfficeEmailVerificationSentOn"  value="<?php if(isset($getVerificationdata[0]['office_email_verification_send_on'])=='' ||  isset($getVerificationdata[0]['office_email_verification_send_on'])=='-' )  { echo "NO"; } else { echo "YES"; } ?>" autocomplete="off"></td>
                <th>CIF No.</th>
                <td><input readonly type="datetime-local" value="<?php if(isset($getVerificationdata[0]['office_email_verified_on'])=='' ||  isset($getVerificationdata[0]['office_email_verified_on'])=='-' )  { echo "NO"; } else { echo "YES"; }   ?>" class="form-control inputField" id="OfficeEmailverifiedOn" name="OfficeEmailverifiedOn" autocomplete="off"></td>
            </tr>
            
            <tr>
                <th>Borrower Type</th>
                <td>
              
                <select class="form-control inputField" id="PANverified" name="PANverified">
                <option value="NO" <?php  if(isset($getVerificationdata[0]['pan_verified'])=='NO') { echo "selected";} ?> >NO</option>
                <option value="YES" <?php  if(isset($getVerificationdata[0]['pan_verified'])=='YES') { echo "selected";} ?>>YES</option>
                </select>

                <span id="pan_msg" style="color: red;"></span></td>
                <th>Borrower Type</th>
                <td><input readonly type="text" class="form-control inputField" id="aadharVerified" value="<?php  if(isset($getVerificationdata[0]['aadhar_verified'])=='' || isset($getVerificationdata[0]['aadhar_verified'])=='-'  )  { echo "NO"; } else { echo "YES"; }  ?>" name="aadharVerified" autocomplete="off"></td>
            </tr>
          
            <tr>
                <th>First Name </th>
                <td>
               
                <select class="form-control inputField" id="BankStatementSVerified" name="BankStatementSVerified">
                <option value="NO"  <?php if(isset($getVerificationdata[0]['bank_statement_verified'])=='NO') { echo "selected";} ?>>NO</option>
                <option value="YES" <?php if(isset($getVerificationdata[0]['bank_statement_verified'])=='YES') { echo "selected";} ?>>YES</option>
                </select>
                
                </td>
                <th>Middle Name</th>
                <td><input  value="<?php   if(isset($getVerificationdata[0]['app_download_on'])=='' || isset($getVerificationdata[0]['app_download_on'])=='-' )   { echo "NO"; } else { echo "YES"; }   ?>" readonly type="datetime-local" class="form-control inputField" id="appDownloadedOn" name="appDownloadedOn " autocomplete="off"></td>
            </tr>
            
            <tr>
                <th>Surname </th>
                <td><input readonly type="text" class="form-control inputField" id="DigitalKYCVerified" name="DigitalKYCVerified" value="<?php  if(isset($getVerificationdata[0]['digital_kyc_verified'])=='' || isset($getVerificationdata[0]['digital_kyc_verified'])=='-'  )  { echo "NO"; } else { echo "YES"; }   ?>" autocomplete="off"></td>
                <th>Gender</th>
                <td><select name="gender" id="gender" class="form-control" disabled="">
                    <option value="MALE">Male</option>
                     <option value="Female">Female</option>
                    
                </select>
                </td>
            </tr>          
        </table>
        
    </div>




<div class="footer-support">
<h2 class="footer-support"><button type="button" class="btn btn-info collapse" data-toggle="collapse" data-target="#ALRESIDENCE">APPROVED LOCATION CONFIRMATION&nbsp;<i class="fa fa-angle-double-down"></i></button></h2>
</div>
<div id="ALRESIDENCE" class="collapse"> 
<!------ table for  RESIDENCE section ----------------------->

<div class="table-responsive">
    <form id="" method="post" >
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
 <input type="hidden" name="lead_id" id="lead_id" value="<?= $leadDetails->lead_id ?>">
        <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['isUserSession']['user_id'] ?>">
        <input type="hidden" name="company_id" id="company_id" value="<?= $_SESSION['isUserSession']['company_id'] ?>">





        <table class="table table-hover table-striped table-bordered">
            <tr>
                <th>Present Addres</th>
                <td colspan="3"><?php if(isset($getVerificationdata[0]['initiiated_on'])=='' || isset($getVerificationdata[0]['initiiated_on'])=='-' )  { echo "NO"; } else { echo "YES"; } ?></td>
               
            </tr>
            <tr>
                <th>City*</th>
                <td><?php if(isset($getVerificationdata[0]['met_with'])=='' || isset($getVerificationdata[0]['met_with'])=='-' ) { echo "NO"; } else { echo "YES"; }  ?></td>
                <th>State</th>
                <td><?php if(isset($getVerificationdata[0]['relation'])=='' || isset($getVerificationdata[0]['relation'])=='-' ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>
           

            <tr>
                <th>Pincode </th>
                <td><?php  if(isset($getVerificationdata[0]['residence_type'])=='' || isset($getVerificationdata[0]['residence_type'])=='-'    ) { echo "NO"; } else { echo "YES"; } ?></td>
                <th>PostOffice</th>
                <td><?php if(isset($getVerificationdata[0]['fi_residence_house_type'])=='' || isset($getVerificationdata[0]['fi_residence_house_type'])=='-'  ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>

            <tr>
                <th>Present Residence Type</th>
                <td><?php if(isset($getVerificationdata[0]['fi_residence_ease_of_identification'])=='' || isset($getVerificationdata[0]['fi_residence_ease_of_identification'])=='-' ) { echo "NO"; } else { echo "-"; }  ?></td>

                <th>Residing Since</th> 
                <td colspan="3"><input type="text" value="<?php if(isset($getCollectiondata[0]['approvedLocCon_residenceSince'])!='' || isset($getCollectiondata[0]['approvedLocCon_residenceSince'])!='-' ) { echo $getCollectiondata[0]['approvedLocCon_residenceSince']; } else { echo "-"; }  ?>" name="residence_since" id="residence_since" class="form-control"></td>
                
                
            </tr>

              <tr>
                <th>SCM Remarks</th>
                <td colspan="3"><input type="text" value="<?php if(isset($getCollectiondata[0]['approvedLocCon_scmRemarks'])!='' || isset($getCollectiondata[0]['approvedLocCon_scmRemarks'])!='-' ) { echo $getCollectiondata[0]['approvedLocCon_scmRemarks']; } else { echo "YES"; }  ?>" name="scm_remarks" id="scm_remarks" class="form-control"></td>
               
            </tr>
           
            <tr>
                
                <th></th>
                <td colspan=""><button class="btn btn-success lead-hold-button" type="button" onclick="rejectalc('1')">APPROVE</button></td>
                
                <th><button type="button" class="btn btn-success reject-button " onclick="rejectalc('0')">REJECT</button></th>
                <td colspan=""></td>
                
            </tr>
           
           
          
        </table>
    </form>
    </div>
<!-- end section for the residence section ----------------->

</div>

<div class="footer-support">
<h2 class="footer-support"><button type="button" class="btn btn-info collapse" data-toggle="collapse" data-target="#FVRESIDENCE">FIELD VERIFICATION - RESIDENCE&nbsp;<i class="fa fa-angle-double-down"></i></button></h2>
</div>
<div id="FVRESIDENCE" class="collapse"> 
<!------ table for  OFFICE section ----------------------->

<div class="table-responsive">
     <form id="applocConfirmation" method="post" >
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
 <input type="hidden" name="lead_id" id="lead_id" value="<?= $leadDetails->lead_id ?>">
        <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION['isUserSession']['user_id'] ?>">
        <input type="hidden" name="company_id" id="company_id" value="<?= $_SESSION['isUserSession']['company_id'] ?>">


        <table class="table table-hover table-striped table-bordered">
             <tr>
                <th>Present Addres</th>
                <td colspan="3"><?php if(isset($getVerificationdata[0]['initiiated_on'])=='' || isset($getVerificationdata[0]['initiiated_on'])=='-' )  { echo "NO"; } else { echo "YES"; } ?></td>
               
            </tr>
            <tr>
                <th>City*</th>
                <td><?php if(isset($getVerificationdata[0]['met_with'])=='' || isset($getVerificationdata[0]['met_with'])=='-' ) { echo "NO"; } else { echo "YES"; }  ?></td>
                <th>State</th>
                <td><?php if(isset($getVerificationdata[0]['relation'])=='' || isset($getVerificationdata[0]['relation'])=='-' ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>
        

            <tr>
                <th>Pincode </th>
                <td><?php  if(isset($getVerificationdata[0]['residence_type'])=='' || isset($getVerificationdata[0]['residence_type'])=='-'    ) { echo "NO"; } else { echo "YES"; } ?></td>
                <th>PostOffice</th>
                <td><?php if(isset($getVerificationdata[0]['fi_residence_house_type'])=='' || isset($getVerificationdata[0]['fi_residence_house_type'])=='-'  ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>
        
            <tr>
                <th>Present Residence Type</th>
                <td><?php if( isset($getVerificationdata[0]['fi_initiated_on'])=='' || isset($getVerificationdata[0]['fi_initiated_on'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Residing Since</th>
                <td><input type="text"  value="<?php if(isset($getCollectiondata[0]['fvr_fvr_residenceSince'])!='' || isset($getCollectiondata[0]['fvr_fvr_residenceSince'])!='-' ) { echo $getCollectiondata[0]['fvr_fvr_residenceSince']; } else { echo "-"; }  ?>" name="fvr_residenceSince" id="fvr_residenceSince" class="form-control"></td>
            </tr>
            <tr>
                <th>Residence CPV Initiated On</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_met_with'])=='' || isset($getVerificationdata[0]['fi_met_with'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Allocated To</th>
                <td><select name="fvr_allocateTo" id="fvr_allocateTo" class="form-control">
                    <option value="1" <?php if($getCollectiondata[0]['fvr_allocatoTo']=='1') { echo "selected";}  ?> >1</option>
                    <option value="2" <?php if($getCollectiondata[0]['fvr_allocatoTo']=='2') { echo "selected";}  ?>>2</option>
                    <option value="3" <?php if($getCollectiondata[0]['fvr_allocatoTo']=='3') { echo "selected";}  ?>>3</option>
                </select></td>
            </tr>
            

            <tr>
                <th>Allocated On </th>
                <td><?php if(isset( $getVerificationdata[0]['fi_entry_allowed'])=='' || isset($getVerificationdata[0]['fi_entry_allowed'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Report Status</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_employer_name'])=='' || isset($getVerificationdata[0]['fi_employer_name'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>
            
           
            <tr>
               
                <th colspan="2"></th>
                <td><button type="button" class="btn btn-success lead-sanction-button" id="savefvrData">Save</button></td>
            </tr>
           
          
        </table>
    </form>
    </div>
<!----- end section for the OFFICE section ----------------->
<!---another div -->

</div>

<div class="footer-support">
<h2 class="footer-support"><button type="button" class="btn btn-info collapse" data-toggle="collapse" data-target="#FVOFFICE">FIELD VERIFICATION - OFFICE&nbsp;<i class="fa fa-angle-double-down"></i></button></h2>
</div>
<div id="FVOFFICE" class="collapse"> 
<!------ table for  OFFICE section ----------------------->

<div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
             <tr>
                <th>Office/ Employer Name*</th>
                <td colspan="3"><?php if(isset($getVerificationdata[0]['initiiated_on'])=='' || isset($getVerificationdata[0]['initiiated_on'])=='-' )  { echo "NO"; } else { echo "YES"; } ?></td>
               
            </tr>
            <tr>
                <th>Office Address</th>
                <td colspan="3"><?php if(isset($getVerificationdata[0]['initiiated_on'])=='' || isset($getVerificationdata[0]['initiiated_on'])=='-' )  { echo "NO"; } else { echo "YES"; } ?></td>
               
            </tr>
            <tr>
                <th>City*</th>
                <td><?php if(isset($getVerificationdata[0]['met_with'])=='' || isset($getVerificationdata[0]['met_with'])=='-' ) { echo "NO"; } else { echo "YES"; }  ?></td>
                <th>State</th>
                <td><?php if(isset($getVerificationdata[0]['relation'])=='' || isset($getVerificationdata[0]['relation'])=='-' ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>
        

            <tr>
                <th>Industry  </th>
                <td><?php  if(isset($getVerificationdata[0]['residence_type'])=='' || isset($getVerificationdata[0]['residence_type'])=='-'    ) { echo "NO"; } else { echo "YES"; } ?></td>
                <th>Sector</th>
                <td><?php if(isset($getVerificationdata[0]['fi_residence_house_type'])=='' || isset($getVerificationdata[0]['fi_residence_house_type'])=='-'  ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>
        
            <tr>
                <th>Department</th>
                <td><?php if( isset($getVerificationdata[0]['fi_initiated_on'])=='' || isset($getVerificationdata[0]['fi_initiated_on'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Designation </th>
                <td><?php if( isset($getVerificationdata[0]['fi_received_on'])=='' || isset($getVerificationdata[0]['fi_received_on'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>
            <tr>
                <th>Employed Since</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_met_with'])=='' || isset($getVerificationdata[0]['fi_met_with'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Present Service Tenure</th>
                <td><?php if( isset($getVerificationdata[0]['fi_relation'])=='' || isset($getVerificationdata[0]['fi_relation'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>
            

            <tr>
                <th>Office CPV Initiated On</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_entry_allowed'])=='' || isset($getVerificationdata[0]['fi_entry_allowed'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Allocate To</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_employer_name'])=='' || isset($getVerificationdata[0]['fi_employer_name'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>
            
           
            
           
          
        </table>
    </div> 
    <!-- another div -->




<div class="footer-support">
<h2 class="footer-support"><button type="button" class="btn btn-info collapse" data-toggle="collapse" data-target="#FVCOLL">FIELD VISIT - COLLECTION &nbsp;<i class="fa fa-angle-double-down"></i></button></h2>
</div>
<div id="FVCOLL" class="collapse"> 
<!------ table for  OFFICE section ----------------------->

<div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
             <tr>
                <th>Mobile</th>
                <td colspan="3"><?php if(isset($getVerificationdata[0]['initiiated_on'])=='' || isset($getVerificationdata[0]['initiiated_on'])=='-' )  { echo "NO"; } else { echo "YES"; } ?></td>
               
            </tr>
            <tr>
                <th>Mobile Alternate</th>
                <td colspan="3"><?php if(isset($getVerificationdata[0]['initiiated_on'])=='' || isset($getVerificationdata[0]['initiiated_on'])=='-' )  { echo "NO"; } else { echo "YES"; } ?></td>
               
            </tr>
            <tr>
                <th>Email (Personal)</th>
                <td><?php if(isset($getVerificationdata[0]['met_with'])=='' || isset($getVerificationdata[0]['met_with'])=='-' ) { echo "NO"; } else { echo "YES"; }  ?></td>
                <th>Email (Office)</th>
                <td><?php if(isset($getVerificationdata[0]['relation'])=='' || isset($getVerificationdata[0]['relation'])=='-' ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>
        

            <tr>
                <th>Loan Amount  </th>
                <td><?php  if(isset($getVerificationdata[0]['residence_type'])=='' || isset($getVerificationdata[0]['residence_type'])=='-'    ) { echo "NO"; } else { echo "YES"; } ?></td>
                <th>Tenure as on date</th>
                <td><?php if(isset($getVerificationdata[0]['fi_residence_house_type'])=='' || isset($getVerificationdata[0]['fi_residence_house_type'])=='-'  ) { echo "NO"; } else { echo "YES"; } ?></td>
            </tr>
        
            <tr>
                <th>ROI</th>
                <td><?php if( isset($getVerificationdata[0]['fi_initiated_on'])=='' || isset($getVerificationdata[0]['fi_initiated_on'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Interest as on date </th>
                <td><?php if( isset($getVerificationdata[0]['fi_received_on'])=='' || isset($getVerificationdata[0]['fi_received_on'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>
            <tr>
                <th>Disbursal Date</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_met_with'])=='' || isset($getVerificationdata[0]['fi_met_with'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Delay (days)</th>
                <td><?php if( isset($getVerificationdata[0]['fi_relation'])=='' || isset($getVerificationdata[0]['fi_relation'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>
            

            <tr>
                <th>Repay Date</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_entry_allowed'])=='' || isset($getVerificationdata[0]['fi_entry_allowed'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Late Payment Interest as on date</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_employer_name'])=='' || isset($getVerificationdata[0]['fi_employer_name'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>

              <tr>
                <th>Repay Amount</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_entry_allowed'])=='' || isset($getVerificationdata[0]['fi_entry_allowed'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Total Payable (Rs)</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_employer_name'])=='' || isset($getVerificationdata[0]['fi_employer_name'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>

              <tr>
                <th>Penal ROI</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_entry_allowed'])=='' || isset($getVerificationdata[0]['fi_entry_allowed'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Total Received (Rs)</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_employer_name'])=='' || isset($getVerificationdata[0]['fi_employer_name'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>

             <tr>
                <th></th>
                <td></td>
                <th>Total Due (Rs)</th>
                <td></td>
            </tr>


              <tr>
                <th>Allocate To</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_entry_allowed'])=='' || isset($getVerificationdata[0]['fi_entry_allowed'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
                <th>Allocated On</th>
                <td><?php if(isset( $getVerificationdata[0]['fi_employer_name'])=='' || isset($getVerificationdata[0]['fi_employer_name'])=='-'  )   { echo "NO"; } else { echo "YES"; }?></td>
            </tr>
            
           
            
           
          
        </table>
    </div> 







</div>






  
