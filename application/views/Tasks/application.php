
    <form id="insertApplication" class="form-inline" method="post" enctype="multipart/form-data" style="margin: 10px;">
        <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $leadDetails->lead_id; ?>" />
        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $leadDetails->customer_id; ?>" />
        <input type="hidden" name="user_id" id="user_id" value="<?= user_id ?>">
        <input type="hidden" name="company_id" id="company_id" value="<?= company_id ?>">
        <input type="hidden" name="product_id" id="product_id" value="<?= product_id ?>">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

        <div class="col-md-6">
            <label class="labelField">Borrower Type</label>
            <input type="text" class="form-control inputField" id="borrower_type" name="borrower_type" autocomplete="off" value="NEW" readonly>
        </div>

        <div class="col-md-6">
            <label class="labelField">PAN&nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="pancard" name="pancard" onchange="validatePanNumber(this)" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField" class="labelField">Loan Applied&nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="loan_applied" name="loan_applied" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField" class="labelField">Loan Tenure&nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="loan_tenure" name="loan_tenure" onchange="tenure(this)" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Loan Purpose&nbsp;<strong class="required_Fields">*</strong></label>
            <select class="form-control inputField" id="loan_purpose" name="loan_purpose" autocomplete="off">
                <option value="">SELECT</option>
                <option value="TRAVEL">TRAVEL</option>
                <option value="MEDICAL">MEDICAL</option>
                <option value="ACADEMICS">ACADEMICS</option>
                <option value="OBLIGATIONS">OBLIGATIONS</option>
                <option value="OCCASION">OCCASION</option>
                <option value="PURCHASE">PURCHASE</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="labelField" class="labelField">First Name &nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="first_name" name="first_name" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Middle Name</label>
            <input type="text" class="form-control inputField" id="middle_name" name="middle_name" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Surname</label>
            <input type="text"  class="form-control inputField" id="sur_name" name="sur_name" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Gender&nbsp;<strong class="required_Fields">*</strong></label>
            <select class="form-control inputField" id="gender" name="gender" autocomplete="off">
                <option value="">SELECT</option>
                <option value="MALE">MALE</option>
                <option value="FEMALE">FEMALE</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="labelField">DOB&nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="dob" name="dob" autocomplete="off">
            <span id="pan_msg" style="color: red;"></span>
        </div>

        <div class="col-md-6">
            <label class="labelField">Salary Mode&nbsp;<strong class="required_Fields">*</strong></label>
            <select class="form-control inputField" id="salary_mode" name="salary_mode" autocomplete="off">
                <option value="">SELECT</option>
                <option value="BANK">BANK</option>
                <option value="CASH">CASH</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="labelField">Salary&nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="monthly_income" name="monthly_income" onchange="monthlyIncome(this)" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Obligations&nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="obligations" name="obligations" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Mobile&nbsp;<strong class="required_Fields">*</strong> </label>
            <input type="text" class="form-control inputField" id="mobile" name="mobile" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Mobile Alternate</label>
            <input type="text" class="form-control inputField" id="alternate_mobile" name="alternate_mobile" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Email (Personal)&nbsp;<strong class="required_Fields">*</strong></label>
            <input  type="text" class="form-control inputField" id="email" name="email" onchange="IsEmail(this)" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Email (Office)</label>
            <input type="text" class="form-control inputField" id="alternate_email" name="alternate_email" onchange="IsEmail(this)" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="labelField">Pincode&nbsp;<strong class="required_Fields">*</strong></label>
            <input type="text" class="form-control inputField" id="pincode" name="pincode" autocomplete="off" onchange="apiPincode(this, '')">
        </div>

        <div class="col-md-6">
            <label class="labelField">State&nbsp;<strong class="required_Fields">*</strong></label>
            <select class="form-control inputField" id="state" name="state" autocomplete="off">
            </select>
        </div>

        <div class="col-md-6">
            <label class="labelField">City&nbsp;<strong class="required_Fields">*</strong></label>
            <select class="form-control inputField" id="city" name="city" autocomplete="off">
            </select>
        </div>
    </form>
    <div class="col-md-12" style="margin: 10px;">
        <button id="saveApplication" class="btn btn-success lead-sanction-button">Save </button> 
    </div>