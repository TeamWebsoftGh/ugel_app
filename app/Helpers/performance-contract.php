<?php

use App\Models\PerformanceContract\PerformanceContract;
use App\Models\PerformanceContract\PerformanceContractSupervisor;
use App\Models\WorkflowRequestDetail;
use App\Models\Employee;
use App\Models\PerformanceContract\AppraisalSetting;
use App\Models\PerformanceContract\AppraisalSettingsReview;
use App\Models\PerformanceContract\AreaSetting;
use App\Models\PerformanceContract\PerformanceContractObjective;

if (!function_exists('is_contract_supervisor')) {
     function is_contract_supervisor($performance_contract_id,$user_id,$staff_id)
    {
        $user_id=$user_id??get_auth_emp()->user_id;
        $contract = PerformanceContract::where('id',$performance_contract_id)
                                                  ->first();
       if($contract){
       if(PerformanceContractSupervisor::where('performance_contract_id',$performance_contract_id)
                       ->where(function($q) use($user_id,$staff_id){
                        $q->where('user_id',$user_id)
                          ->where('mgr_code','SUPERVISOR')
                          ->orWhere('staff_id',$staff_id);
         })->first()){
          return true;
         } return false;

      }return false;      
    }
}

if (!function_exists('is_contract_hod')) {
    function is_contract_hod($performance_contract_id,$user_id,$staff_id)
   {
       $user_id=$user_id??get_auth_emp()->user_id;
       $contract = PerformanceContract::where('id',$performance_contract_id)
                                                 ->first();
      if($contract){
      if(PerformanceContractSupervisor::where('performance_contract_id',$performance_contract_id)
                      ->where(function($q) use($user_id,$staff_id){
                       $q->where('user_id',$user_id)
                         ->where('mgr_code','HOD')
                         ->orWhere('staff_id',$staff_id);
        })->first()){
         return true;
        } return false;

     }return false;      
   }
}


if (!function_exists('get_auth_emp')) {
    function get_auth_emp(){
        $data = Employee::where('users.email',user()->email)
       ->join('users','users.email','employees.email')
       ->selectRaw('users.*,employees.*')
       ->first();  
       return optional($data);  
    }
}


if (!function_exists('is_supervisor_score')) {
    function is_supervisor_score($target_statement){
      return $target_statement == 'Supervisor Bonus Score';
    //    if(PerformanceContractObjective::where([
    //     'target_statement' => $target_statement
    //     ])->first()){
    //         return true;
    //     }
    }
}
 
if (!function_exists('is_contract_employee')) {
    function is_contract_employee($performance_contract_id,$staff_id)
   {
       $contract = PerformanceContract::where('id',$performance_contract_id)
                                                 ->where('staff_id',$staff_id)
                                                 ->first();
                          
      if($contract && (get_auth_emp()->staff_id==$staff_id) ){return true ; }return false;  
      
      
   }
}

    
    
    
if (!function_exists('can_implement_contract')) { 
    function can_implement_contract($req_details_id,$user_id)
    {
        $user_id=$user_id??get_auth_emp()->user_id;
        if ($req_details = WorkflowRequestDetail::where('id',$req_details_id)->first()) {

            if($req_details->implementor_id == $user_id){
               return true;
            }elseif($req_details->new_implementor_id == $user_id){
               return true;
            } 
            // we can add other exceptions here
            
            return false;
            } return false; 
    }
}

if (!function_exists('form_labels')) { 
    function form_labels($label)
    {
        $label=($label=='mid_year_status'?'q3_status':$label);
        $label=($label=='appraisal_status'?'q4_status':$label);

        $label=($label=='item_type_id'?'department':$label);
       $exp_label = explode('_',$label);
       $leb = '';
       foreach ($exp_label as $value) {
        
          if ($value=='id') {
          continue;
          }
          $leb .=$value.' ';
       }
       return ucwords($leb);
    }
}


if (!function_exists('get_reviews')) {
    function get_reviews()
    {
      return AppraisalSettingsReview::where('status','ACTIVE')
                                ->whereDate('start_date', '<=', date("Y-m-d"))
                                ->whereDate('end_date', '>=', date("Y-m-d"))
                // ->where('company_id', get_auth_emp()->company_id)
                ->get(['review_name','review_type']);
    }
}
if (!function_exists('get_critical_areas')) {
    function get_critical_areas($objective_category_id)
    {
      return AreaSetting::where('objective_category_id',$objective_category_id)
                ->get();
    }
}

if (!function_exists('get_current_appraisal_setting')) {
    function get_current_appraisal_setting()
    {
      return AppraisalSetting::where('status','ACTIVE')
                // ->where('company_id', get_auth_emp()->company_id)
                ->orderBy('id','desc')
                ->first();
    }
}

if (!function_exists('get_review_final_status')) { 
   function get_review_final_status($review_type)
 {
    if($review_type=="mid_year_status"){
     return "final_midy_score";
    }elseif($review_type=="appraisal_status"){
        return "final_ye_score";
    }elseif($review_type=="q1_status"){
        return "final_q1_score";
    }elseif($review_type=="q2_status"){
        return "final_q2_score";
    }
 }
}

if (!function_exists('get_review_final_status_cv')) { 
    function get_review_final_status_cv($review_type)
  {
     if($review_type=="mid_year_status"){
      return "final_midy_score_cv";
     }elseif($review_type=="appraisal_status"){
         return "final_ye_score_cv";
     }elseif($review_type=="q1_status"){
         return "final_q1_score_cv";
     }elseif($review_type=="q2_status"){
         return "final_q2_score_cv";
     }
  }
 }



if (!function_exists('performance_metrix')) { 
 function performance_metrix($score,$score_cv)
{
    $score_label=null;
    $score_name=null;
    if ($score_cv < 70) {
        $score_label='E';
        $score_name='Below Expectations';
        $score=$score;
    }elseif ($score_cv >= 70 && $score_cv < 80) {
        $score_label='D';
        $score_name='Satisfactory';
        $score=$score;
    }elseif ($score_cv >= 80 && $score_cv < 90) {
        $score_label='C';
        $score_name='Good';
        $score=$score;
    }elseif ($score_cv >= 90 && $score_cv < 105) {
        $score_label='B';
        $score_name='Excellent';
        $score=$score;
    }elseif ($score_cv >= 105) {
        $score_label='A';
        $score_name='Excellent Expectations';
        $score=$score;
    }



    return [
        'score_label' => $score_label,
        'score_name' => $score_name,
        'score' => $score,
    ];
  }
}





if (!function_exists('performance_metrix_label')) { 
    function performance_metrix_label($score)
   {
       $score_name=null;
       $score_label=null;
       if ($score < 70) {
           $score_label='E';
           $score_name='Below Expectations';
           $score=$score;
       }elseif ($score >= 70 && $score < 80) {
           $score_name='Satisfactory';
           $score_label='D';
           $score=$score;
       }elseif ($score >= 80 && $score < 90) {
           $score_name='Good';
           $score_label='C';
           $score=$score;
       }elseif ($score >= 90 && $score < 105) {
           $score_name='Excellent';
           $score_label='B';
           $score=$score;
       }elseif ($score >= 105) {
           $score_label='A';
           $score_name='Excellent Expectations';
           $score=$score;
       }

       return [
           'score_label' => $score_label,
           'score_name' => $score_name,
           'score' => $score,
       ];
     }
   }



if (!function_exists('core_values_metrix')) { 
    function core_values_metrix($score)
    {
       $score_label=null;
       if ($score >= 1 && $score <= 3) {
           $score_label='E';
       }elseif ($score >= 4 && $score <= 6) {
           $score_label='D';
       }elseif ($score >= 7 && $score <= 9) {
           $score_label='C';
       }elseif ($score >= 10 && $score <= 12) {
           $score_label='B';
       }elseif ($score >= 13 && $score <= 15) {
           $score_label='A';
       }
      return $score_label;
     }
   }


 if (!function_exists('msgStrReplace')) { 
     function msgStrReplace($msg,array $replaces)
    {
       foreach ($replaces as $key => $value) {
        $msg = str_replace($key,$value,$msg);
       }
       return $msg;
    }
  }



 if (!function_exists('wkfAlertMessageType')) { 

  function wkfAlertMessageType($type)
    {
       $msq = [
        'subject' => '',
        'body' => '',
       ];
       if ($type == 'submit') {
        $msq=[
            'subject' => '<requestname> request pending your approval',
            'body' => 'submit_message',
           ];
       } elseif($type == 'decline') {
        $msq=[
            'subject' => '<requestname> request has been declined',
            'body' => 'decline_message',
           ];
       }
       elseif($type == 'approve') {
        $msq=[
            'subject' => '<requestname> request approved',
            'body' => 'approve_message',
           ];
       }
       elseif($type == 'inform') {
        $msq=[
            'subject' => '<requestname> request by <requestorfullname> ',
            'body' => 'inform_message',
           ];
       }
       return $msq;
       
    }
}



if (!function_exists('contract_date')) { 
    function contract_date($start_date, $end_date)
      {
        $currentDate = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($currentDate));   
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));  
        if (($currentDate >= $start_date) && ($currentDate <= $end_date)){   
        return true;
        }else{    
        return false; 
        }
      }
    }