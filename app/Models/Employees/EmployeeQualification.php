<?php

namespace App\Models\Employees;

use App\Models\Common\QualificationEducationLevel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmployeeQualification extends Model
{
	protected $guarded=[];

	public function employee()
    {
		return $this->hasOne(Employee::class,'id','employee_id')->withoutGlobalScope('exit_date');
	}

	public function education_level()
    {
		return $this->belongsTo(QualificationEducationLevel::class,'education_level_id');
	}
//	public function LanguageSkill(){
//		return $this->hasOne(QualificationLanguage::class,'id','language_skill_id');
//	}
//	public function GeneralSkill(){
//		return $this->hasOne(QualificationSkill::class,'id','general_skill_id');
//	}

}
