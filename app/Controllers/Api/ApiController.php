<?php

namespace App\Controllers\Api;

use App\Models\AdditionModel;
use App\Models\AddressModel;
use App\Models\BankInfoModel;
use App\Models\ClientModel;
use App\Models\ClientPaymentsModel;
use App\Models\DeductionModel;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\EmployeeFileModel;
use App\Models\EmployeeModel;
use App\Models\EmpSalaryModel;
use App\Models\ExpancesModel;
use App\Models\HolidayModel;
use App\Models\InvoiceItemModel;
use App\Models\InvoiceModel;
use App\Models\PaySalaryModel;
use App\Models\ProposalsModel;
use App\Models\QuotationItemModel;
use App\Models\QuotationsModel;
use App\Models\SalaryTypeModel;
use App\Models\ServiceModel;
use App\Models\ServicesCategoryModel;
use App\Models\SettingModel;
use App\Models\SocialMediaModel;
use App\Models\SummaryModel;
use App\Models\ToDoListModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function PHPUnit\Framework\equalTo;

class ApiController extends ResourceController
{
    private $db;
   public function __construct()
   {
      $this->db=db_connect();    
   } 


    //----------------------------------UserAuth----------------------------------

    public function registerUser()
    {
          $rules=[
         //    "name" =>"required",
             "em_email" =>"required|valid_email|is_unique[employee.em_email]",
             "em_password" =>"required",
          ];
    
          if(!$this->validate($rules))
          {
             $response=[
                "status" =>500,
                "message" =>$this->validator->getErrors(),
                "error" =>true,
                "data" =>[],
             ];      
          }
          else
          {
             $user_obj=new EmployeeModel();
             $data=[
             //   "em_id" =>$this->request->getVar("em_id"),
            //    "em_code" =>$this->request->getVar("em_code"),
            //    "des_id" =>$this->request->getVar("em_id"),
            //    "dep_id" =>$this->request->getVar("em_code"),
            //    "first_name" =>$this->request->getVar("first_name"),
            //    "last_name" =>$this->request->getVar("last_name"),
                "em_email" =>$this->request->getVar("em_email"),
            //    "em_password" =>password_hash($this->request->getVar("em_password"),PASSWORD_DEFAULT),
                "em_password" =>sha1($this->request->getVar("em_password")),
            //    "em_role" =>$this->request->getVar("em_role"),
            //    "em_address" =>$this->request->getVar("em_address"),
            //    "status" =>$this->request->getVar("status"),
            //    "em_gender" =>$this->request->getVar("em_gender"),
            //    "em_phone" =>$this->request->getVar("em_phone"),
            //    "em_birthday" =>$this->request->getVar("em_birthday"),
            //    "em_blood_group" =>$this->request->getVar("em_blood_group"),
            //    "em_joining_date" =>$this->request->getVar("em_joining_date"),
            //    "em_contact_end" =>$this->request->getVar("em_contact_end"),
            //    "em_image" =>$this->request->getVar("em_image"),
             //   "em_nid" =>$this->request->getVar("em_nid"),
             ];
             if($user_obj->insert($data))
             {
                $response=[
                    "status" =>200,
                    "message" =>"User Have been registered",
                    "error" =>false,
                    "data" =>[],
                 ];          
             }
             else
             {
                $response=[
                    "status" =>500,
                    "message" =>"Failed to register user",
                    "error" =>true,
                    "data" =>[],
                 ];      
             }
          }
    
          return $this->respondCreated($response);
    }

    public function loginUser()
    {
        $rules=[
            "em_email" =>"required|valid_email",
            "em_password" =>"required",
         ];
         if(!$this->validate($rules))
         {
            $response=[
                "status" =>500,
                "message" =>$this->validator->getErrors(),
                "error" =>true,
                "data" =>[],
             ];   
         }
         else
         {
             $email=$this->request->getVar("em_email");
             $password=$this->request->getVar("em_password");

             $user_obj=new EmployeeModel();
             $userdata=$user_obj->where("em_email",$email)->first();
             if(!empty($userdata))
             {
                 //password_verify($password,$userdata['em_password']
                if(sha1($password)==$userdata['em_password'])
                {
                    $iat=time();
                    $nbf=$iat;
                    $exp=$iat+9500;
                    $payload=[
                        "iat" =>$iat,
                        "nbf" =>$nbf,
                        "exp" =>$exp,
                        "userdata" => $userdata
                    ];
            
                  $token=JWT::encode($payload,$this->getKey(), 'HS256');
                   $response=[
                    "status" =>200,
                    "message" =>"User Logged in",
                    "error" =>false,
                    "data" =>[
                      "token" =>$token,
                      "user_data"=>$userdata,
                    ],
                 ];   
                   
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"Password did not matched",
                        "error" =>true,
                        "data" =>[],
                     ];   
                }
             }
             else
             {
                $response=[
                    "status" =>500,
                    "message" =>"Email id not exists",
                    "error" =>true,
                    "data" =>[],
                 ];   
             }
         }

         return $this->respondCreated($response);
    }

    public function getKey()
    {
        return "ABCEDFRG";
    }

    public function header_auth()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $response=[
                    "status" =>200,
                    "message" =>"Dashboard Data",
                    "error" =>false,
                    "data" =>[
                        "user" =>$decoded_data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"User Must be Login",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    //-----------------------------------Dashboard--------------------------------

    public function dashboardList()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                $holiday_obj=new HolidayModel();
                $to_do_obj=new ToDoListModel();
                $emp_data=$this->db->table("employee")->countAll();
                $holiday_data=$holiday_obj->findAll();
                $to_do_list=$to_do_obj->findAll();
                $response=[
                    "status" =>200,
                    "message" =>"Dashboard Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "employee_count"=>$emp_data,
                        "holiday" =>$holiday_data,
                        "to-do-list" =>$to_do_list,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"User Must be Login",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
       
        return $this->respondCreated($response);
    }

    //---------------------------------Employee------------------------------------

    public function listEmployee()
    {
      //  $response_token=$this->header_auth();
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                $emp_data=$emp_obj->orderBy('id',"DESC")->findAll();
                $response=[
                    "status" =>200,
                    "message" =>"Employee Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "employee_data"=>$emp_data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }
  
    public function addEmployee()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                $rules=[
                    "em_id" =>"required",
                    "em_code" =>"required",
                    "des_id" =>"required",
                    "dep_id" =>"required",
                    "first_name" =>"required",
                 //   "last_name" =>"required",
                    "em_email" =>"required",
                  //  "em_password" =>"required",
                    "em_role" =>"required",
               //     "em_address" =>"required",
                //    "status" =>"required",
              //      "em_gender" =>"required",
                    "em_phone" =>"required",
              //      "em_birthday" =>"required",
              //      "em_blood_group" =>"required",
                    "em_joining_date" =>"required",
               //     "em_contact_end" =>"required",
                //    "em_nid" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $file=$this->request->getFile("em_image");
                    if($file)
                    {
                     $image_name=$file->getName();
                     $temp=explode(".",$image_name);
                     $newImageName=round(microtime(true)).'.'.end($temp);
                     if($file->move("images",$newImageName))
                     {
                        $employee=new EmployeeModel();
                        $data=[
                          "em_id" =>$this->request->getVar("em_id"),
                          "em_code" =>$this->request->getVar("em_code"),
                          "des_id" =>$this->request->getVar("des_id"),
                          "dep_id" =>$this->request->getVar("dep_id"),
                          "first_name" =>$this->request->getVar("first_name"),
                          "last_name" =>$this->request->getVar("last_name"),
                       //   "em_email" =>$this->request->getVar("em_email"),
                       //   "em_password" =>sha1($this->request->getVar("em_password")),
                          "em_role" =>$this->request->getVar("em_role"),
                          "em_address" =>$this->request->getVar("em_address"),
                          "status" =>$this->request->getVar("status"),
                          "em_gender" =>$this->request->getVar("em_gender"),
                          "em_phone" =>$this->request->getVar("em_phone"),
                          "em_birthday" =>$this->request->getVar("em_birthday"),
                          "em_blood_group" =>$this->request->getVar("em_blood_group"),
                          "em_joining_date" =>$this->request->getVar("em_joining_date"),
                          "em_contact_end" =>$this->request->getVar("em_contact_end"),
                          "em_nid" =>$this->request->getVar("em_nid"),
                          "em_image" =>"/images/".$newImageName,
                         ];

                         
                          $response=[
                              "status" =>200,
                              "message" =>$data,
                              "error" =>false,
                              "data" =>[]
                            ];

                         if($employee->insert($data))
                         {
                            $response=[
                              "status" =>200,
                              "message" =>"Employee has been added",
                              "error" =>false,
                              "data" =>[]
                            ];
                         }
                         else
                         {
                             $response=[
                               "status" =>500,
                               "message" =>"Failed to added employee",
                               "error" =>true,
                               "data" =>[]
                             ];
                         }
                     }
                     else
                     {
                        $employee=new EmployeeModel();
                        $data=[
                            "em_id" =>$this->request->getVar("em_id"),
                            "em_code" =>$this->request->getVar("em_code"),
                            "des_id" =>$this->request->getVar("des_id"),
                            "dep_id" =>$this->request->getVar("dep_id"),
                            "first_name" =>$this->request->getVar("first_name"),
                            "last_name" =>$this->request->getVar("last_name"),
                           // "em_email" =>$this->request->getVar("em_email"),
                         //   "em_password" =>sha1($this->request->getVar("em_password")),
                            "em_role" =>$this->request->getVar("em_role"),
                            "em_address" =>$this->request->getVar("em_address"),
                            "status" =>$this->request->getVar("status"),
                            "em_gender" =>$this->request->getVar("em_gender"),
                            "em_phone" =>$this->request->getVar("em_phone"),
                            "em_birthday" =>$this->request->getVar("em_birthday"),
                            "em_blood_group" =>$this->request->getVar("em_blood_group"),
                            "em_joining_date" =>$this->request->getVar("em_joining_date"),
                            "em_contact_end" =>$this->request->getVar("em_contact_end"),
                            "em_nid" =>$this->request->getVar("em_nid"),
                         ];
                 
                         if($employee->insert($data))
                         {
                            $response=[
                                "status" =>200,
                                "message" =>"Employee Added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                         }
                         else
                         {
                             $response=[
                               "status" =>500,
                               "message" =>"Failed to employee added",
                               "error" =>true,
                               "data" =>[]
                             ];
                         }
                     }
                    }
                    else
                    {
                        $employee=new EmployeeModel();
                        $data=[
                            "em_id" =>$this->request->getVar("em_id"),
                            "em_code" =>$this->request->getVar("em_code"),
                            "des_id" =>$this->request->getVar("des_id"),
                            "dep_id" =>$this->request->getVar("dep_id"),
                            "first_name" =>$this->request->getVar("first_name"),
                         //   "last_name" =>$this->request->getVar("last_name"),
                            "em_email" =>$this->request->getVar("em_email"),
                           // "em_password" =>sha1($this->request->getVar("em_password")),
                            "em_role" =>$this->request->getVar("em_role"),
                         //   "em_address" =>$this->request->getVar("em_address"),
                       //    "status" =>$this->request->getVar("status"),
                        //    "em_gender" =>$this->request->getVar("em_gender"),
                            "em_phone" =>$this->request->getVar("em_phone"),
                        //    "em_birthday" =>$this->request->getVar("em_birthday"),
                      //      "em_blood_group" =>$this->request->getVar("em_blood_group"),
                            "em_joining_date" =>$this->request->getVar("em_joining_date"),
                      //      "em_contact_end" =>$this->request->getVar("em_contact_end"),
                      //      "em_nid" =>$this->request->getVar("em_nid"),
                         ];
                 
                         if($employee->insert($data))
                         {
                            $response=[
                                "status" =>200,
                                "message" =>"Employee Added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                         }
                         else
                         {
                             $response=[
                               "status" =>500,
                               "message" =>"Failed to employee added",
                               "error" =>true,
                               "data" =>[]
                             ];
                         }
                    }
                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateEmployee($emp_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                if($emp_obj->find($emp_id))
                {
                    $rules=[
                        "name" =>"required",
                        "person" =>"required",
                        "address" =>"required",
                        "contact_no" =>"required",
                        "email" =>"required",
                        "balance" =>"required",
                    ];
                    if(!$this->validate($rules))
                    {
                        $response=[
                            "status" =>500,
                            "message" =>$this->validator->getErrors(),
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $data=[
                            "name" =>$this->request->getVar("name"),
                            "person" =>$this->request->getVar("person"),
                            "address" =>$this->request->getVar("address"),
                            "contact_no" =>$this->request->getVar("contact_no"),
                            "email" =>$this->request->getVar("email"),
                            "gst_no" =>$this->request->getVar("gst_no"),
                            "remarks" =>$this->request->getVar("remarks"),
                            "balance" =>$this->request->getVar("balance"),
                        ];  
    
                        if($emp_obj->update($emp_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Client update Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"ID does not exists",
                        "error" =>true,
                        "data" =>[],
                    ];
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteEmployee($emp_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                if($emp_obj->delete($emp_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Employee delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete Employee data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

     public function updatePersoninfo($emp_id)
     {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                if($emp_obj->find($emp_id))
                {
                    $rules=[
                        "em_id" =>"required",
                        "em_code" =>"required",
                        "des_id" =>"required",
                        "dep_id" =>"required",
                        "first_name" =>"required",
                        "last_name" =>"required",
                        "em_role" =>"required",
                        "status" =>"required",
                        "em_gender" =>"required",
                        "em_email" =>"required",
                        "em_phone" =>"required",
                        "em_joining_date" =>"required",
                        "em_contact_end" =>"required",
                    ];
                    if(!$this->validate($rules))
                    {
                        $response=[
                            "status" =>500,
                            "message" =>$this->validator->getErrors(),
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $file=$this->request->getFile("em_image");
                        if($file->isValid()){
                         $image_name=$file->getName();
                         $temp=explode(".",$image_name);
                         $newImageName=round(microtime(true)).'.'.end($temp);
                         if($file->move("images",$newImageName))
                         {
                            $data=[
                                "em_code" =>$this->request->getVar("em_code"),
                                "des_id" =>$this->request->getVar("des_id"),
                                "dep_id" =>$this->request->getVar("dep_id"),
                                "first_name" =>$this->request->getVar("first_name"),
                                "last_name" =>$this->request->getVar("last_name"),
                                "em_role" =>$this->request->getVar("em_role"),
                                "status" =>$this->request->getVar("status"),
                                "em_gender" =>$this->request->getVar("em_gender"),
                                "em_email" =>$this->request->getVar("em_email"),
                                "em_phone" =>$this->request->getVar("em_phone"),
                                "em_birthday" =>$this->request->getVar("em_birthday"),
                                "em_blood_group" =>$this->request->getVar("em_blood_group"),
                                "em_joining_date" =>$this->request->getVar("em_joining_date"),
                                "em_contact_end" =>$this->request->getVar("em_contact_end"),
                            ];  
                            if($emp_obj->update($emp_id,$data))
                            {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Employee personal details update Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                            }
                            else
                            {
                                $response=[
                                    "status" =>500,
                                    "message" =>"Missing Token",
                                    "error" =>true,
                                    "data" =>[],
                                ];
                            }
                         }
                         else
                         {
                            $response=[
                                "status" =>200,
                                "message" =>"Image Uploading failed",
                                "error" =>false,
                                "data" =>[],
                            ];
                         }
                        }
                        else
                        {
                            $data=[
                                "em_code" =>$this->request->getVar("em_code"),
                                "des_id" =>$this->request->getVar("des_id"),
                                "dep_id" =>$this->request->getVar("dep_id"),
                                "first_name" =>$this->request->getVar("first_name"),
                                "last_name" =>$this->request->getVar("last_name"),
                                "em_role" =>$this->request->getVar("em_role"),
                                "status" =>$this->request->getVar("status"),
                                "em_gender" =>$this->request->getVar("em_gender"),
                                "em_email" =>$this->request->getVar("em_email"),
                                "em_phone" =>$this->request->getVar("em_phone"),
                                "em_birthday" =>$this->request->getVar("em_birthday"),
                                "em_blood_group" =>$this->request->getVar("em_blood_group"),
                                "em_joining_date" =>$this->request->getVar("em_joining_date"),
                                "em_contact_end" =>$this->request->getVar("em_contact_end"),
                            ];  
                            if($emp_obj->update($emp_id,$data))
                            {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Employee personal details update Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                            }
                            else
                            {
                                $response=[
                                    "status" =>500,
                                    "message" =>"Missing Token",
                                    "error" =>true,
                                    "data" =>[],
                                ];
                            }

                        }
                    }
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"ID does not exists",
                        "error" =>true,
                        "data" =>[],
                    ];
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
     }

     public function editAddress($emp_id)
     {
         $auth=$this->request->getHeader("Authorization");
         try{
             if(isset($auth))
             {
                 $token=$auth->getValue();
                 $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                 $emp_obj=new EmployeeModel();
                 $rules=[
                     "emp_id" =>"required",
                     "city" =>"required",
                     "country" =>"required",
                     "address" =>"required",
                 ];
                 if(!$this->validate($rules))
                 {
                     $response=[
                         "status" =>500,
                         "message" =>$this->validator->getErrors(),
                         "error" =>true,
                         "data" =>[],
                     ];
                 }
                 else
                 {
                     $data=[
                         "emp_id" =>$this->request->getVar("emp_id"),
                         "em_address" =>$this->request->getVar("address").','.
                                         $this->request->getVar("city").','.
                                         $this->request->getVar("country"),
                     ];
                         
                     if($emp_obj->update($emp_id,$data))
                     {
                         $response=[
                             "status" =>200,
                             "message" =>"Address updated Successfull",
                             "error" =>false,
                             "data" =>[],
                         ];
                     }
                     else
                     {
                         $response=[
                             "status" =>500,
                             "message" =>"Failed to update address",
                             "error" =>true,
                             "data" =>[],
                         ];
                     }
                 }
             }
             else
             {
                 $response=[
                     "status" =>500,
                     "message" =>"Missing token",
                     "error" =>true,
                     "data" =>[],
                 ];
             }
         }catch(Exception $ex)
         {
             $response=[
                 "status" =>500,
                 "message" =>$ex->getMessage(),
                 "error" =>true,
                 "data" =>[],
             ];
         }
         return $this->respondCreated($response);
     }

     public function editBankinfo($emp_id)
     {
         $auth=$this->request->getHeader("Authorization");
         try{
             if(isset($auth))
             {
                 $token=$auth->getValue();
                 $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                 $bank_obj=new BankInfoModel();

                 $rules=[
                     "em_id" =>"required",
                     "holder_name" =>"required",
                     "bank_name" =>"required",
                     "branch_name" =>"required",
                     "account_number" =>"required",
                     "ifsc" =>"required",
                 ];
                 if(!$this->validate($rules))
                 {
                     $response=[
                         "status" =>500,
                         "message" =>$this->validator->getErrors(),
                         "error" =>true,
                         "data" =>[],
                     ];
                 }
                 else
                 {
                     $data=[
                         "em_id" =>$this->request->getVar("emp_id"),
                         "holder_name" =>$this->request->getVar("holder_name"),
                         "bank_name" =>$this->request->getVar("bank_name"),
                         "branch_name" =>$this->request->getVar("branch_name"),
                         "account_number" =>$this->request->getVar("account_number"),
                         "ifsc" =>$this->request->getVar("ifsc"),
                     ];
                       
                      $empdata=$bank_obj->where("em_id",$emp_id)->first();
                     if(!empty($empdata))
                     {
                        if($bank_obj->update($emp_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Bank details updated Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to update bank details",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                     }
                     else
                     {
                        if($bank_obj->insert($data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Bank details inserted Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to add bank details",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                     }
                    
                 }
             }
             else
             {
                 $response=[
                     "status" =>500,
                     "message" =>"Missing token",
                     "error" =>true,
                     "data" =>[],
                 ];
             }
         }catch(Exception $ex)
         {
             $response=[
                 "status" =>500,
                 "message" =>$ex->getMessage(),
                 "error" =>true,
                 "data" =>[],
             ];
         }
         return $this->respondCreated($response);
     }

     public function editDocument($emp_id)
     {
         $auth=$this->request->getHeader("Authorization");
         try{
             if(isset($auth))
             {
                 $token=$auth->getValue();
                 $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                 $empfile_obj=new EmployeeFileModel();
                 $emp_obj=new EmployeeModel();
                 $rules=[
                     "emp_id" =>"required",
                     "file_title" =>"required",
                     //"file_url" =>"required",
                 ];
                 if(!$this->validate($rules))
                 {
                     $response=[
                         "status" =>500,
                         "message" =>$this->validator->getErrors(),
                         "error" =>true,
                         "data" =>[],
                     ];
                 }
                 else
                 {
                     $emp_id=$this->request->getVar("emp_id");
                     $file=$this->request->getFile("file_url");
                     if($file)
                     {
                        $image_name=$file->getName();
                        $temp=explode(".",$image_name);
                        $newImageName=round(microtime(true)).'.'.end($temp);
                        if($file->move("images",$newImageName))
                        {
                           $data=[
                               "em_id" =>$this->request->getVar("emp_id"),
                               "file_title" =>$this->request->getVar("file_title"),
                               "file_url" =>"/images/".$newImageName,
                           ];
   
                           $id=$this->db->table('Employee')->where('em_id',$emp_id)->get()->getResult();
                           if($id)
                           {
                               $update_file=$this->db->table('employee_file')->where('em_id',$emp_id)->update($data);
                               if($update_file)
                               {
                                   $response=[
                                       "status" =>200,
                                       "message" =>"Document added Successfull",
                                       "error" =>false,
                                       "data" =>[],
                                   ];
                               }
                               else
                               {
                                   $response=[
                                       "status" =>500,
                                       "message" =>"Failed to add document details",
                                       "error" =>true,
                                       "data" =>[],
                                   ];
                               }
                           }
                           else
                           {
                               
                               if($empfile_obj->insert($data))
                               {
                                   $response=[
                                       "status" =>200,
                                       "message" =>"Document added Successfull",
                                       "error" =>false,
                                       "data" =>[],
                                   ];
                               }
                               else
                               {
                                   $response=[
                                       "status" =>500,
                                       "message" =>"Failed to add bank details",
                                       "error" =>true,
                                       "data" =>[],
                                   ];
                               }
                           }
                            
                        }
                        else
                        {
                           $response=[
                               "status" =>200,
                               "message" =>"Image Uploading failed",
                               "error" =>false,
                               "data" =>[],
                           ];
                        }  
                     }
                     else
                     {
                        $response=[
                            "status" =>500,
                            "message" =>"Please attached the file",
                            "error" =>true,
                            "data" =>[],
                        ];
                     }
                   
                 }
             }
             else
             {
                 $response=[
                     "status" =>500,
                     "message" =>"Missing token",
                     "error" =>true,
                     "data" =>[],
                 ];
             }
         }catch(Exception $ex)
         {
             $response=[
                 "status" =>500,
                 "message" =>$ex->getMessage(),
                 "error" =>true,
                 "data" =>[],
             ];
         }
         return $this->respondCreated($response);
     }

     public function editSocialmedia($emp_id)
     {
         $auth=$this->request->getHeader("Authorization");
         try{
             if(isset($auth))
             {
                 $token=$auth->getValue();
                 $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                 $social_obj=new SocialMediaModel();
                 $rules=[
                     "emp_id" =>"required",
                     "facebook" =>"required",
                     "linkedin" =>"required",
                     "instagram" =>"required",
                     "skype_id" =>"required",
                 ];
                 if(!$this->validate($rules))
                 {
                     $response=[
                         "status" =>500,
                         "message" =>$this->validator->getErrors(),
                         "error" =>true,
                         "data" =>[],
                     ];
                 }
                 else
                 {
                     $data=[
                         "emp_id" =>$this->request->getVar("emp_id"),
                         "facebook" =>$this->request->getVar("facebook"),
                         "linkedin" =>$this->request->getVar("linkedin"),
                         "instagram" =>$this->request->getVar("instagram"),
                         "skype_id" =>$this->request->getVar("skype_id"),
                     ];
                         
                  
                     $empdata=$social_obj->where("emp_id",$emp_id)->first();
                     if(!empty($empdata))
                     {
                        if($social_obj->update($emp_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Social media updated Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to update social media",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                     }
                     else
                     {
                        if($social_obj->insert($data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Social media added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to add social media",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                     }
                     
                 }
             }
             else
             {
                 $response=[
                     "status" =>500,
                     "message" =>"Missing token",
                     "error" =>true,
                     "data" =>[],
                 ];
             }
         }catch(Exception $ex)
         {
             $response=[
                 "status" =>500,
                 "message" =>$ex->getMessage(),
                 "error" =>true,
                 "data" =>[],
             ];
         }
         return $this->respondCreated($response);
     }

     public function editSalary($id)
     {
         $auth=$this->request->getHeader("Authorization");
         try{
             if(isset($auth))
             {
                 $token=$auth->getValue();
                 $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                 $pay_sal_obj=new PaySalaryModel();
                 $rules=[
                     "emp_id" =>"required",
                     "type_id" =>"required",
                     "total_pay" =>"required",
                     "basic" =>"required",
                     "medical" =>"required",
                     "house_rent" =>"required",
                     "conveyance" =>"required",
                     "provident_fund" =>"required",
                     "tax" =>"required",
                     "other" =>"required",
                 ];
                 if(!$this->validate($rules))
                 {
                     $response=[
                         "status" =>500,
                         "message" =>$this->validator->getErrors(),
                         "error" =>true,
                         "data" =>[],
                     ];
                 }
                 else
                 {
                     $data=[
                        "emp_id" =>$this->request->getVar("emp_id"),
                        "type_id" =>$this->request->getVar("type_id"),
                        "basic" =>$this->request->getVar("basic"),
                        "total_pay" =>$this->request->getVar("total_pay"),
                        "medical" =>$this->request->getVar("medical"),
                        "house_rent" =>$this->request->getVar("house_rent"),
                        "conveyance" =>$this->request->getVar("conveyance"),
                        "provident_fund" =>$this->request->getVar("provident_fund"),
                        "tax" =>$this->request->getVar("tax"),
                        "other" =>$this->request->getVar("other"),
                     ];  
                     $emp_id=$this->request->getVar("emp_id");
                     $check_pay=$pay_sal_obj->where("emp_id",$emp_id)->find();
                  //   $check_pay=$this->db->table("pay_salary")->where('emp_id',$emp_id)->get()->getRow();
                     if(!empty($check_pay))
                     {      
                          $id=$check_pay[0]['pay_id'];
                          if($pay_sal_obj->update($id,$data))
                          {
                            $response=[
                                "status" =>500,
                                "message" =>"Salary Updated successfull",
                                "error" =>true,
                                "data" =>[],
                            ];
                          }

                     }
                     else
                     {
                        if($pay_sal_obj->insert($data))
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Salary added successfull",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                     }

                    
                 }
             }
             else
             {
                 $response=[
                     "status" =>500,
                     "message" =>"Missing token",
                     "error" =>true,
                     "data" =>[],
                 ];
             }
         }catch(Exception $ex)
         {
             $response=[
                 "status" =>500,
                 "message" =>$ex->getMessage(),
                 "error" =>true,
                 "data" =>[],
             ];
         }
         return $this->respondCreated($response);
       
     }

     public function listaddition()
     {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $quote_item_obj=new QuotationItemModel();
                $check_addition=$quote_item_obj->where("emp_id","Nir123")->find();
                $response=[
                    "status" =>200,
                    "message" =>"Salary type Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "Sal_type_data"=>$check_addition[0]['id'],
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
     }

     public function editPassword($emp_id)
     {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                $userdata=$emp_obj->where("id",$emp_id)->first();
                $rules=[
                    "emp_id" =>"required",
                    "change_password" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "em_password" =>sha1($this->request->getVar("change_password")),
                    ];
                    $em_password =$this->request->getVar("em_password");
                    if(sha1($em_password)==$userdata['em_password'])
                    {
                        if($emp_obj->update($emp_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Password updated Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to update password",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }   
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Password is incorrect",
                            "error" =>false,
                            "data" =>[],
                        ];
                    } 
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
     }

     public function editPersonalinfo($emp_id)
     {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $emp_obj=new EmployeeModel();
                $rules=[
                    "em_code" =>"required",
                    "des_id" =>"required",
                    "dep_id" =>"required",
                    "first_name" =>"required",
                    "em_email" =>"required",
                  //  "em_password" =>"required",
                   "em_role" =>"required",
               //     "em_address" =>"required",
                    "status" =>"required",
                    "em_gender" =>"required",
                    "em_phone" =>"required",
                    "em_birthday" =>"required",
                    "em_joining_date" =>"required",
                    "em_contact_end" =>"required",
                //    "em_nid" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $file=$this->request->getFile("em_image");
                    if($file)
                    {
                     $image_name=$file->getName();
                     $temp=explode(".",$image_name);
                     $newImageName=round(microtime(true)).'.'.end($temp);
                     if($file->move("images",$newImageName))
                     {
                        $employee=new EmployeeModel();
                        $data=[
                          "em_id" =>$this->request->getVar("em_code"),
                          "em_code" =>$this->request->getVar("em_code"),
                          "des_id" =>$this->request->getVar("des_id"),
                          "dep_id" =>$this->request->getVar("dep_id"),
                          "first_name" =>$this->request->getVar("first_name"),
                          "last_name" =>$this->request->getVar("last_name"),
                          "em_email" =>$this->request->getVar("em_email"),
                       //   "em_password" =>sha1($this->request->getVar("em_password")),
                          "em_role" =>$this->request->getVar("em_role"),
                          "em_address" =>$this->request->getVar("em_address"),
                          "status" =>$this->request->getVar("status"),
                          "em_gender" =>$this->request->getVar("em_gender"),
                          "em_phone" =>$this->request->getVar("em_phone"),
                          "em_birthday" =>$this->request->getVar("em_birthday"),
                          "em_blood_group" =>$this->request->getVar("em_blood_group"),
                          "em_joining_date" =>$this->request->getVar("em_joining_date"),
                          "em_contact_end" =>$this->request->getVar("em_contact_end"),
                          "em_image" =>"/images/".$newImageName,
                         ];

                         
                          $response=[
                              "status" =>200,
                              "message" =>$data,
                              "error" =>false,
                              "data" =>[]
                            ];

                         if($employee->update($emp_id,$data))
                         {
                            $response=[
                              "status" =>200,
                              "message" =>"Employee has been updated",
                              "error" =>false,
                              "data" =>[]
                            ];
                         }
                         else
                         {
                             $response=[
                               "status" =>500,
                               "message" =>"Failed to updated employee",
                               "error" =>true,
                               "data" =>[]
                             ];
                         }
                     }
                     else
                     {
                        $employee=new EmployeeModel();
                        $data=[
                            "em_id" =>$this->request->getVar("em_code"),
                            "em_code" =>$this->request->getVar("em_code"),
                            "des_id" =>$this->request->getVar("des_id"),
                            "dep_id" =>$this->request->getVar("dep_id"),
                            "first_name" =>$this->request->getVar("first_name"),
                            "last_name" =>$this->request->getVar("last_name"),
                            "em_email" =>$this->request->getVar("em_email"),
                         //   "em_password" =>sha1($this->request->getVar("em_password")),
                            "em_role" =>$this->request->getVar("em_role"),
                           // "em_address" =>$this->request->getVar("em_address"),
                            "status" =>$this->request->getVar("status"),
                            "em_gender" =>$this->request->getVar("em_gender"),
                            "em_phone" =>$this->request->getVar("em_phone"),
                            "em_birthday" =>$this->request->getVar("em_birthday"),
                            "em_blood_group" =>$this->request->getVar("em_blood_group"),
                            "em_joining_date" =>$this->request->getVar("em_joining_date"),
                            "em_contact_end" =>$this->request->getVar("em_contact_end"),
                         
                         ];
                 
                         if($employee->update($emp_id,$data))
                         {
                            $response=[
                                "status" =>200,
                                "message" =>"Employee updated Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                         }
                         else
                         {
                             $response=[
                               "status" =>500,
                               "message" =>"Failed to employee updated",
                               "error" =>true,
                               "data" =>[]
                             ];
                         }
                     }
                    }
                    else
                    {
                        $employee=new EmployeeModel();
                        $data=[
                            "em_id" =>$this->request->getVar("em_code"),
                            "em_code" =>$this->request->getVar("em_code"),
                            "des_id" =>$this->request->getVar("des_id"),
                            "dep_id" =>$this->request->getVar("dep_id"),
                            "first_name" =>$this->request->getVar("first_name"),
                            "last_name" =>$this->request->getVar("last_name"),
                            "em_email" =>$this->request->getVar("em_email"),
                           // "em_password" =>sha1($this->request->getVar("em_password")),
                            "em_role" =>$this->request->getVar("em_role"),
                            "em_address" =>$this->request->getVar("em_address"),
                       //    "status" =>$this->request->getVar("status"),
                            "em_gender" =>$this->request->getVar("em_gender"),
                            "em_phone" =>$this->request->getVar("em_phone"),
                        //    "em_birthday" =>$this->request->getVar("em_birthday"),
                            "em_blood_group" =>$this->request->getVar("em_blood_group"),
                            "em_joining_date" =>$this->request->getVar("em_joining_date"),
                            "em_contact_end" =>$this->request->getVar("em_contact_end"),
                        //  "em_nid" =>$this->request->getVar("em_nid"),
                         ];
                 
                         if($employee->update($emp_id,$data))
                         {
                            $response=[
                                "status" =>200,
                                "message" =>"Employee updated Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                         }
                         else
                         {
                             $response=[
                               "status" =>500,
                               "message" =>"Failed to employee added",
                               "error" =>true,
                               "data" =>[]
                             ];
                         }
                    }
                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
     }


     //--------------------------------- Salary_type --------------------------------------

     public function listSalary_type()
     {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $sal_type_obj=new SalaryTypeModel();
                $sal_data=$sal_type_obj->orderBy('id',"DESC")->findAll();
                $response=[
                    "status" =>200,
                    "message" =>"Salary type Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "Sal_type_data"=>$sal_data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
     }

    //----------------------------------Holiday---------------------------------------

    public function addHoliday()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $holiday_obj=new HolidayModel();

                $rules=[
                    "holiday_name" =>"required",
                    "from_date" =>"required",
                    "to_date" =>"required",
                    "number_of_days" =>"required",
                    "year" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "holiday_name" =>$this->request->getVar("holiday_name"),
                        "from_date" =>$this->request->getVar("from_date"),
                        "to_date" =>$this->request->getVar("to_date"),
                        "number_of_days" =>$this->request->getVar("number_of_days"),
                        "year" =>$this->request->getVar("year"),
                    ];
                        
                    if($holiday_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Holiday added Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"User Must be Login",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteHoliday($holiday_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $holiday_obj=new HolidayModel();
                if($holiday_obj->delete($holiday_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Holiday data delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete holiday data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function listHoliday()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $holiday_obj=new HolidayModel();
                  $holiday_data=$holiday_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Holiday Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "Holiday_data"=>$holiday_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }


    //------------------------------------Todo-----------------------------------------

    public function addTodo()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $todo_obj=new ToDoListModel();

                $rules=[
                    "user_id" =>"required",
                    "to_dodata" =>"required",
                    "date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "user_id" =>$this->request->getVar("user_id"),
                        "to_dodata" =>$this->request->getVar("to_dodata"),
                        "date" =>$this->request->getVar("date"),
                        "value" =>$this->request->getVar("value"),
                    ];
                    if($todo_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Todo added Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"User Must be Login",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteTodo($todo_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $todo_obj=new ToDoListModel();
                if($todo_obj->delete($todo_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Todo data delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete todo data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function listTodo()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $todo_obj=new ToDoListModel();
                  $todo_data=$todo_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Todo Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "todo_data"=>$todo_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }


    //------------------Department ------------------------------

    public function listDepartment()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $dept_obj=new DepartmentModel();
                  $dept_data=$dept_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Department Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "department_data"=>$dept_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addDepartment()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $department_obj=new DepartmentModel();

                $rules=[
                    "dep_name" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "dep_name" =>$this->request->getVar("dep_name"),
                    ];  
                    if($department_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Department added Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Missing Token",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateDepartment($dept_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $department_obj=new DepartmentModel();
                if($department_obj->find($dept_id))
                {
                    $rules=[
                        "dep_name" =>"required",
                    ];
                    if(!$this->validate($rules))
                    {
                        $response=[
                            "status" =>500,
                            "message" =>$this->validator->getErrors(),
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $data=[
                            "dep_name" =>$this->request->getVar("dep_name"),
                        ];  
    
                        if($department_obj->update($dept_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Department update Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"ID does not exists",
                        "error" =>true,
                        "data" =>[],
                    ];
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteDepartment($dept_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $dept_obj=new DepartmentModel();
                if($dept_obj->delete($dept_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Department delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete Department data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    //--------------------Designation -------------------------------

    public function listDesignation()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $des_obj=new DesignationModel();
                  $des_data=$des_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Designation Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "designation_data"=>$des_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addDesignation()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $des_obj=new DesignationModel();

                $rules=[
                    "des_name" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "des_name" =>$this->request->getVar("des_name"),
                    ];  
                    if($des_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Designation added Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Missing Token",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateDesignation($des_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $des_obj=new DesignationModel();
                $rules=[
                    "des_name" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "des_name" =>$this->request->getVar("des_name"),
                    ];  

                    if($des_obj->update($des_id,$data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Designation update Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Missing Token",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteDesignation($des_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $des_obj=new DesignationModel();
                if($des_obj->delete($des_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Designation delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete Designation data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    //------------------ Client -------------------------------

    public function listClient()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $client_obj=new ClientModel();
                  $client_data=$client_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Client Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "Client_data"=>$client_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addClient()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $client_obj=new ClientModel();

                $rules=[
                    "name" =>"required",
                    "person" =>"required",
                    "address" =>"required",
                    "contact_no" =>"required",
                    "email" =>"required",
                    "balance" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "name" =>$this->request->getVar("name"),
                        "person" =>$this->request->getVar("person"),
                        "address" =>$this->request->getVar("address"),
                        "contact_no" =>$this->request->getVar("contact_no"),
                        "email" =>$this->request->getVar("email"),
                        "gst_no" =>$this->request->getVar("gst_no"),
                        "remarks" =>$this->request->getVar("remarks"),
                        "balance" =>$this->request->getVar("balance"),
                    //    "status" =>$this->request->getVar("status"),

                    ];  
                    if($client_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Client added Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Missing Token",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateClient($client_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $client_obj=new ClientModel();
                if($client_obj->find($client_id))
                {
                    $rules=[
                        "name" =>"required",
                        "person" =>"required",
                        "address" =>"required",
                        "contact_no" =>"required",
                        "email" =>"required",
                        "balance" =>"required",
                    ];
                    if(!$this->validate($rules))
                    {
                        $response=[
                            "status" =>500,
                            "message" =>$this->validator->getErrors(),
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $data=
                        [
                            "name" =>$this->request->getVar("name"),
                            "person" =>$this->request->getVar("person"),
                            "address" =>$this->request->getVar("address"),
                            "contact_no" =>$this->request->getVar("contact_no"),
                            "email" =>$this->request->getVar("email"),
                            "gst_no" =>$this->request->getVar("gst_no"),
                            "remarks" =>$this->request->getVar("remarks"),
                            "balance" =>$this->request->getVar("balance"),
                        ];  

                        if($client_obj->update($client_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Client update Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"ID does not exists",
                        "error" =>true,
                        "data" =>[],
                    ];
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteClient($client_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $client_obj=new ClientModel();
                if($client_obj->delete($client_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Client delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete Designation data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }


    //------------------ Services-Category --------------------------------------

    public function listServices_category_list()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $cat_obj=new ServicesCategoryModel();
                  $service_cat_data=$cat_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Services Category Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "Services_category_data"=>$service_cat_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addServices_category()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $cat_obj=new ServicesCategoryModel();
                 
                $rules=[
                    "cname" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "cname" =>$this->request->getVar("cname"),
                    ];  
                    if($cat_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Services Category Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Missing Token",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateServices_category($cat_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $cat_obj=new ServicesCategoryModel();
                if($cat_obj->find($cat_id))
                {
                    $rules=[
                        "cname" =>"required",
                    ];
                    if(!$this->validate($rules))
                    {
                        $response=[
                            "status" =>500,
                            "message" =>$this->validator->getErrors(),
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $data=[
                            "cname" =>$this->request->getVar("cname"),
                        ];  
    
                        if($cat_obj->update($cat_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Services Category update Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"Id does not exists",
                        "error" =>true,
                        "data" =>[],
                    ];
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteServices_category($cat_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $cat_obj=new ServicesCategoryModel();
                if($cat_obj->delete($cat_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Services category delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete Designation data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

   //----------------- Settings --------------------------------------------------

    public function listSettings()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $setting_obj=new SettingModel();
                $setting_data=$setting_obj->findAll();
                $response=[
                    "status" =>200,
                    "message" =>"Setting Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "settings_data"=>$setting_data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateSettings($setting_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $setting_obj=new SettingModel();
                if($setting_obj->find($setting_id))
                {
                    $rules=[
                        "sitetitle" =>"required",
                        "copyright" =>"required",
                        "contact" =>"required",
                        "currency" =>"required",
                        "symbol" =>"required",
                        "system_email" =>"required",
                        "address" =>"required",
                        "bank_name" =>"required",
                        "account_name" =>"required",
                        "account_number" =>"required",
                        "ifsc" =>"required",
                        "upi_id" =>"required", 
                    ];
                    if(!$this->validate($rules))
                    {
                        $response=[
                            "status" =>500,
                            "message" =>$this->validator->getErrors(),
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                         $sitelogo=$this->request->getFile("sitelogo");
                         if($sitelogo)
                         {
                         $image_name=$sitelogo->getName();
                         $temp=explode(".",$image_name);
                         $newImageName=round(microtime(true)).'.'.end($temp);
                         if($sitelogo->move("images",$newImageName))
                         {
                             $data=[
                                "sitelogo" =>"/images/".$newImageName,
                                "sitetitle" =>$this->request->getVar("sitetitle"),
                                "description" =>$this->request->getVar("description"),
                                "copyright" =>$this->request->getVar("copyright"),
                                "contact" =>$this->request->getVar("contact"),
                                "currency" =>$this->request->getVar("currency"),
                                "symbol" =>$this->request->getVar("symbol"),
                                "system_email" =>$this->request->getVar("system_email"),
                                "address" =>$this->request->getVar("address"),
                                "address2" =>$this->request->getVar("address2"),
                            ];  
                            if($setting_obj->update($setting_id,$data))
                            {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Setting update Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                            }
                            else
                            {
                                $response=[
                                    "status" =>500,
                                    "message" =>"Missing Token",
                                    "error" =>true,
                                    "data" =>[],
                                ];
                            }
                         }
                         else
                         {
                            $response=[
                                "status" =>200,
                                "message" =>"Image Uploading failed",
                                "error" =>false,
                                "data" =>[],
                            ];
                         }
                        }
                        else
                        {
                            $data=[
                                "sitetitle" =>$this->request->getVar("sitetitle"),
                                "description" =>$this->request->getVar("description"),
                                "copyright" =>$this->request->getVar("copyright"),
                                "contact" =>$this->request->getVar("contact"),
                                "currency" =>$this->request->getVar("currency"),
                                "symbol" =>$this->request->getVar("symbol"),
                                "system_email" =>$this->request->getVar("system_email"),
                                "address" =>$this->request->getVar("address"),
                                "address2" =>$this->request->getVar("address2"),
                            ];  
                            if($setting_obj->update($setting_id,$data))
                            {
                               $response=[
                                  "status" =>200,
                                  "message" =>"Setting update Successfull",
                                  "error" =>false,
                                  "data" =>[],
                                ];
                            }
                            else
                            {
                              $response=[
                                  "status" =>500,
                                  "message" =>"Missing Token",
                                  "error" =>true,
                                  "data" =>[],
                               ];
                            }

                        }
                        
                      
                    }
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"ID does not exists",
                        "error" =>true,
                        "data" =>[],
                    ];
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }


   //------------------- Services --------------------------------------------------

    public function addServices()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $services_obj=new ServiceModel();
                $cat_service_obj=new ServicesCategoryModel();
               
                $rules=[
                    "category_id" =>"required",
                    "name" =>"required",
                    "short_descr" =>"required",
                    "long_descr" =>"required",
                    "price" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $id=$this->request->getVar("category_id");
                    if($cat_service_obj->find($id))
                    {
                        $data=[
                            "category_id" =>$this->request->getVar("category_id"),
                            "name" =>$this->request->getVar("name"),
                            "short_descr" =>$this->request->getVar("short_descr"),
                            "long_descr" =>$this->request->getVar("long_descr"),
                            "price" =>$this->request->getVar("price"),
                        ];  
                        if($services_obj->insert($data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Services added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to add services",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Category id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                   
                }
               
                 
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function listServices()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                // $services_obj=new ServiceModel();
                // $services_data=$services_obj->findAll();
                $builder=$this->db->table("services_category");
                $builder->select("services.*,services_category.cname");
                $builder->join("services","services_category.id=services.category_id");
                $data=$builder->orderBy('id',"DESC")->get()->getResult();
                $response=[
                    "status" =>200,
                    "message" =>"Services Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "service_data"=>$data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateServices($service_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $services_obj=new ServiceModel();
                $cat_service_obj=new ServicesCategoryModel();
                $cat_id=$this->request->getVar("category_id");
                $rules=[
                    "category_id" =>"required",
                    "name" =>"required",
                    "short_descr" =>"required",
                    "long_descr" =>"required",
                    "price" =>"required",
                    "isactive" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {

                    if($services_obj->find($service_id) && $cat_service_obj->find($cat_id) )
                    {
                        $data=[
                            "category_id" =>$this->request->getVar("category_id"),
                            "name" =>$this->request->getVar("name"),
                            "short_descr" =>$this->request->getVar("short_descr"),
                            "long_descr" =>$this->request->getVar("long_descr"),
                            "price" =>$this->request->getVar("price"),
                            "currency" =>$this->request->getVar("currency"),
                            "isactive" =>$this->request->getVar("isactive"),
                        ];  
    
                        if($services_obj->update($service_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Services update Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"ID does not exists",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteServices($service_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $service_obj=new ServiceModel();
                if($service_obj->delete($service_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Services delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete services data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    //----------------- Proposals ------------------------------------
    
    public function addProposals()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $pro_obj=new ProposalsModel();
                $client_obj=new ClientModel();
                $rules=[
                    "client_id" =>"required",
                    "short_descr" =>"required",
                    "descr" =>"required",
                    "follow_up_date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $id=$this->request->getVar("client_id");
                    if($client_obj->find($id))
                    {
                    
                        $file=$this->request->getFile("file_src");
                        if($file){
                         $image_name=$file->getName();
                         $temp=explode(".",$image_name);
                         $newImageName=round(microtime(true)).'.'.end($temp);
                         if($file->move("images",$newImageName))
                         {
                            $data=[
                                "client_id" =>$this->request->getVar("client_id"),
                                "short_descr" =>$this->request->getVar("short_descr"),
                                "descr" =>$this->request->getVar("descr"),
                                "status" =>$this->request->getVar("status"),
                                "follow_up_date" =>$this->request->getVar("follow_up_date"),
                                "file_src" =>"/images/".$newImageName,
                             ];
                             if($pro_obj->insert($data))
                             {
                                $response=[
                                  "status" =>200,
                                  "message" =>"Proposals has been added",
                                  "error" =>false,
                                  "data" =>[]
                                ];
                             }
                             else
                             {
                                 $response=[
                                   "status" =>500,
                                   "message" =>"Failed to added employee",
                                   "error" =>true,
                                   "data" =>[]
                                 ];
                             }
                         }
                         else
                         {
                           
                            $data=[
                                "client_id" =>$this->request->getVar("client_id"),
                                "short_descr" =>$this->request->getVar("short_descr"),
                                "descr" =>$this->request->getVar("descr"),
                                "status" =>$this->request->getVar("status"),
                                "follow_up_date" =>$this->request->getVar("follow_up_date"),
                             ];
                     
                             if($pro_obj->insert($data))
                             {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Proposals Added Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                             }
                             else
                             {
                                 $response=[
                                   "status" =>500,
                                   "message" =>"Failed to proposals added",
                                   "error" =>true,
                                   "data" =>[]
                                 ];
                             }
                         }
                        }
                        else
                        {
                            $data=[
                                "client_id" =>$this->request->getVar("client_id"),
                                "short_descr" =>$this->request->getVar("short_descr"),
                                "descr" =>$this->request->getVar("descr"),
                                "status" =>$this->request->getVar("status"),
                                "follow_up_date" =>$this->request->getVar("follow_up_date"),
                            ];  
                            if($pro_obj->insert($data))
                             {
                                $response=[
                                  "status" =>200,
                                  "message" =>"Proposals has been added",
                                  "error" =>false,
                                  "data" =>[]
                                ];
                             }
                             else
                             {
                                 $response=[
                                   "status" =>500,
                                   "message" =>"Failed to added employee",
                                   "error" =>true,
                                   "data" =>[]
                                 ];
                             }

                        }
                        
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Client id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                   
                } 
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function listProposals()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                // $pro_obj=new ProposalsModel();
                // $pro_data=$pro_obj->findAll();
                $builder=$this->db->table("proposal");
                $builder->select("proposal.*,clients.name");
                $builder->join("clients","clients.id=proposal.client_id");
                $data=$builder->orderBy('id',"DESC")->get()->getResult();
                $response=[
                    "status" =>200,
                    "message" =>"Proposals Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "proposals_data"=>$data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateProposals($pro_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $pro_obj=new ProposalsModel();
                $client_obj=new ClientModel();
                $client_id=$this->request->getVar("client_id");
                $rules=[
                    "client_id" =>"required",
                    "short_descr" =>"required",
                    "descr" =>"required",
                    "follow_up_date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {

                    if($pro_obj->find($pro_id) && $client_obj->find($client_id) )
                    {
                        $file=$this->request->getFile("file_src");
                        if($file){
                         $image_name=$file->getName();
                         $temp=explode(".",$image_name);
                         $newImageName=round(microtime(true)).'.'.end($temp);
                         if($file->move("images",$newImageName))
                         {
                            $data=[
                                "client_id" =>$this->request->getVar("client_id"),
                                "short_descr" =>$this->request->getVar("short_descr"),
                                "descr" =>$this->request->getVar("descr"),
                                "status" =>$this->request->getVar("status"),
                                "follow_up_date" =>$this->request->getVar("follow_up_date"),
                                "file_src" =>"/images/".$newImageName,
                             ];
                             if($pro_obj->update($pro_id,$data))
                             {
                                 $response=[
                                     "status" =>200,
                                     "message" =>"Proposals update Successfull",
                                     "error" =>false,
                                     "data" =>[],
                                 ];
                             }
                             else
                             {
                                 $response=[
                                     "status" =>500,
                                     "message" =>"Missing Token",
                                     "error" =>true,
                                     "data" =>[],
                                 ];
                             }
                         }
                         else
                         {
                           
                            $data=[
                                "client_id" =>$this->request->getVar("client_id"),
                                "short_descr" =>$this->request->getVar("short_descr"),
                                "descr" =>$this->request->getVar("descr"),
                                "status" =>$this->request->getVar("status"),
                                "follow_up_date" =>$this->request->getVar("follow_up_date"),
                             ];
                     
                             if($pro_obj->update($pro_id,$data))
                             {
                                 $response=[
                                     "status" =>200,
                                     "message" =>"Proposals update Successfull",
                                     "error" =>false,
                                     "data" =>[],
                                 ];
                             }
                             else
                             {
                                 $response=[
                                     "status" =>500,
                                     "message" =>"Missing Token",
                                     "error" =>true,
                                     "data" =>[],
                                 ];
                             }
                         }
                        }
                        else
                        {
                            $data=[
                                "client_id" =>$this->request->getVar("client_id"),
                                "short_descr" =>$this->request->getVar("short_descr"),
                                "descr" =>$this->request->getVar("descr"),
                                "status" =>$this->request->getVar("status"),
                                "follow_up_date" =>$this->request->getVar("follow_up_date"),
                            ];  
                            if($pro_obj->update($pro_id,$data))
                            {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Proposals update Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                            }
                            else
                            {
                                $response=[
                                    "status" =>500,
                                    "message" =>"Missing Token",
                                    "error" =>true,
                                    "data" =>[],
                                ];
                            }

                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"ID does not exists",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteProposals($pro_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $pro_obj=new ProposalsModel();
                if($pro_obj->delete($pro_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Proposals delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete services data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }


    //---------------------- Expenses -----------------------------------
    
    public function addExpenses()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $exp_obj=new ExpancesModel();
                $emp_obj=new EmployeeModel();
                $rules=[
                    "user_id" =>"required",
                    "descr" =>"required",
                    "amount" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $id=$this->request->getVar("user_id");
                    if($emp_obj->find($id))
                    {
                        $file=$this->request->getFile("file_src");
                        if($file){
                         $image_name=$file->getName();
                         $temp=explode(".",$image_name);
                         $newImageName=round(microtime(true)).'.'.end($temp);
                         if($file->move("file",$newImageName))
                         {
                            $data=[
                                "user_id" =>$this->request->getVar("user_id"),
                                "descr" =>$this->request->getVar("descr"),
                                "amount" =>$this->request->getVar("amount"),
                                "date" =>$this->request->getVar("date"),
                                "file_src" =>"/file/".$newImageName,
                             ];
                             if($exp_obj->insert($data))
                             {
                                $response=[
                                  "status" =>200,
                                  "message" =>"Expances has been added",
                                  "error" =>false,
                                  "data" =>[]
                                ];
                             }
                             else
                             {
                                 $response=[
                                   "status" =>500,
                                   "message" =>"Failed to added expenses",
                                   "error" =>true,
                                   "data" =>[]
                                 ];
                             }
                         }
                         else
                         {
                           
                            $data=[
                                "user_id" =>$this->request->getVar("user_id"),
                                "descr" =>$this->request->getVar("descr"),
                                "amount" =>$this->request->getVar("amount"),
                                "date" =>$this->request->getVar("date"),
                             ];
                     
                             if($exp_obj->insert($data))
                             {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Expenses Added Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                             }
                             else
                             {
                                 $response=[
                                   "status" =>500,
                                   "message" =>"Failed to proposals added",
                                   "error" =>true,
                                   "data" =>[]
                                 ];
                             }
                         }
                        }
                        else
                        {
                            $data=[
                                "user_id" =>$this->request->getVar("user_id"),
                                "descr" =>$this->request->getVar("descr"),
                                "amount" =>$this->request->getVar("amount"),
                                "date" =>$this->request->getVar("date"),
                            ];  
                            if($exp_obj->insert($data))
                             {
                                $response=[
                                  "status" =>200,
                                  "message" =>"Expenses has been added",
                                  "error" =>false,
                                  "data" =>[]
                                ];
                             }
                             else
                             {
                                 $response=[
                                   "status" =>500,
                                   "message" =>"Failed to added Expenses",
                                   "error" =>true,
                                   "data" =>[]
                                 ];
                             }

                        }
                        
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"User id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                   
                } 
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function listExpenses()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $exp_obj=new ExpancesModel();
                $exp_data=$exp_obj->findAll();
                $builder=$this->db->table("employee");
                $builder->select("employee.first_name,expenses.*");
                $builder->join("expenses","expenses.user_id=employee.id");
                $data=$builder->orderBy('id',"DESC")->get()->getResult();
                $response=[
                    "status" =>200,
                    "message" =>"Expenses Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "expenses_data"=>$data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateExpenses($exp_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $exp_obj=new ExpancesModel();
                $emp_obj=new EmployeeModel();
                $emp_id=$this->request->getVar("user_id");
                $rules=[
                    "user_id" =>"required",
                    "descr" =>"required",
                    "amount" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    if($emp_obj->find($emp_id) && $exp_obj->find($exp_id) )
                    {
                        $file=$this->request->getFile("file_src");
                        if($file){
                         $image_name=$file->getName();
                         $temp=explode(".",$image_name);
                         $newImageName=round(microtime(true)).'.'.end($temp);
                         if($file->move("images",$newImageName))
                         {
                            $data=[
                                "user_id" =>$this->request->getVar("user_id"),
                                "descr" =>$this->request->getVar("descr"),
                                "amount" =>$this->request->getVar("amount"),
                                "date" =>$this->request->getVar("date"),
                                "file_src" =>"/images/".$newImageName,
                             ];
                             if($exp_obj->update($exp_id,$data))
                             {
                                 $response=[
                                     "status" =>200,
                                     "message" =>"Expenses update Successfull",
                                     "error" =>false,
                                     "data" =>[],
                                 ];
                             }
                             else
                             {
                                 $response=[
                                     "status" =>500,
                                     "message" =>"Missing Token",
                                     "error" =>true,
                                     "data" =>[],
                                 ];
                             }
                         }
                         else
                         {
                            $response=[
                                "status" =>200,
                                "message" =>"Image Uploading failed",
                                "error" =>false,
                                "data" =>[],
                            ];
                         }
                        }
                        else
                        {
                            $data=[
                                "user_id" =>$this->request->getVar("user_id"),
                                "descr" =>$this->request->getVar("descr"),
                                "amount" =>$this->request->getVar("amount"),
                                "date" =>$this->request->getVar("date"),
                            ];  
                            if($exp_obj->update($exp_id,$data))
                            {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Expenses update Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                            }
                            else
                            {
                                $response=[
                                    "status" =>500,
                                    "message" =>"Missing Token",
                                    "error" =>true,
                                    "data" =>[],
                                ];
                            }

                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"ID does not exists",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteExpenses($exp_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $exp_obj=new ExpancesModel();
                if($exp_obj->delete($exp_id))
                {
                     $response=[
                        "status" =>200,
                        "message" =>"Expenses delete Successfull",
                        "error" =>false,
                        "data" =>[],
                        ];
                }
                else
                  {
                    $response=[
                         "status" =>500,
                          "message" =>"Failed to delete expenses data",
                          "error" =>true,
                          "data" =>[],
                     ];
                 }
                
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    //--------------- payroll type ---------------------------

    public function listPayroll_type()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $sal_type_obj=new SalaryTypeModel();
                  $sal_type_data=$sal_type_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Salary type Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "payroll_type_data"=>$sal_type_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addPayroll_type()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $salary_type_obj=new SalaryTypeModel();

                $rules=[
                    "salary_type" =>"required",
                    "create_date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "salary_type" =>$this->request->getVar("salary_type"),
                        "create_date" =>$this->request->getVar("create_date"),
                    ];  
                    if($salary_type_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Salary added Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Missing Token",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updatePayroll_type($salary_type_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $salary_type_obj=new SalaryTypeModel();
                $rules=[
                    "salary_type" =>"required",
                    "create_date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "salary_type" =>$this->request->getVar("salary_type"),
                        "create_date" =>$this->request->getVar("create_date"),
                    ];  
                    
                    if($salary_type_obj->find($salary_type_id))
                    {
                        if($salary_type_obj->update($salary_type_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Salary update Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Salary id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deletePayroll_type($salary_type_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $salary_type_obj=new SalaryTypeModel();

                if($salary_type_obj->find($salary_type_id))
                {
                    if($salary_type_obj->delete($salary_type_id))
                    {
                         $response=[
                            "status" =>200,
                            "message" =>"Salary delete Successfull",
                            "error" =>false,
                            "data" =>[],
                            ];
                    }
                    else
                      {
                        $response=[
                             "status" =>500,
                              "message" =>"Failed to delete Salary data",
                              "error" =>true,
                              "data" =>[],
                         ];
                     }
                }
                else
                {
                    $response=[
                        "status" =>500,
                         "message" =>"Salary ID did not match",
                         "error" =>true,
                         "data" =>[],
                    ];
                }  
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    //-------------------- Payroll ------------------------------------------


    public function listPayroll_list()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(),'HS256'));
                  $builder=$this->db->table("pay_salary");
                  $builder->select("pay_salary.*,employee.first_name,last_name,em_code,dep_id");
                  $builder->join("employee","employee.em_id=pay_salary.emp_id");
                  $data=$builder->orderBy('id',"DESC")->get()->getResult();
                  $response=[
                      "status" =>200,
                      "message" =>"Salary Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "payroll_list_data"=>$data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function Generate_listPayroll_list($dep_id)
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(),'HS256'));
                  $builder=$this->db->table("pay_salary");
                  $builder->select("pay_salary.*,employee.first_name,last_name,em_code,dep_id");
                  $builder->join("employee","employee.em_id=$dep_id");
                  $data=$builder->get()->getResult();
                  $response=[
                      "status" =>200,
                      "message" =>"Salary Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "payroll_list_data"=>$data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function generate_payslip($dep_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(),'HS256'));
                // $builder=$this->db->table("pay_salary");
                // $builder->select("pay_salary.emp_id,pay_salary.total_pay,pay_salary.bonus,employee.first_name,last_name");
                // $builder->join("employee","employee.dep_id=$dep_id & pay");
               // $data=$builder->get()->getResult();
                $emp_sal_obj=new PaySalaryModel();
                $emp_obj=new EmployeeModel();
                $emp_data=$emp_obj->where("dep_id",$dep_id)->find();
                $emp_sal_data=$emp_sal_obj->find();
                $arr = array();

                foreach($emp_data as $results)
                {
                    foreach($emp_sal_data as $sal_data)
                    {
                            if($results['em_id']==$sal_data['emp_id'])
                            {
                                $arr[]=array(
                                    'pay_id'=>$sal_data['pay_id'],
                                    'emp_id'=>$sal_data['emp_id'],
                                    'total_pay'=>$sal_data['total_pay'],
                                    'bonus'=>$sal_data['bonus'],
                                    'first_name'=>$results['first_name']
                                );
                            }
                    }
                    
                }
                $response=[
                    "status" =>200,
                    "message" =>"Payslip data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "payslip data"=>$arr,
                    ],
                ];

               
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function addPayroll_list()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $pay_salary_obj=new PaySalaryModel();

                $rules=[
                    "pay_id" =>"required",
                    "emp_id" =>"required",
                    "type_id" =>"required",
                    "month" =>"required",
                    "year" =>"required",
                    "paid_date" =>"required",
                    "total_days" =>"required",
                    "basic" =>"required",
                    "medical" =>"required",
                    "house_rent" =>"required",
                    "bonus" =>"required",
                    "bima" =>"required",
                    "tax" =>"required",
                    "provident_fund" =>"required",
                    "loan" =>"required",
                    "total_pay" =>"required",
                    "addition" =>"required",
                    "diduction" =>"required",
                    "status" =>"required",
                    "paid_type" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "pay_id" =>$this->request->getVar("pay_id"),
                        "emp_id" =>$this->request->getVar("emp_id"),
                        "type_id" =>$this->request->getVar("type_id"),
                        "month" =>$this->request->getVar("month"),
                        "year" =>$this->request->getVar("year"),
                        "paid_date" =>$this->request->getVar("paid_date"),
                        "total_days" =>$this->request->getVar("total_days"),
                        "basic" =>$this->request->getVar("basic"),
                        "medical" =>$this->request->getVar("medical"),
                        "house_rent" =>$this->request->getVar("house_rent"),
                        "bonus" =>$this->request->getVar("bonus"),
                        "bima" =>$this->request->getVar("bima"),
                        "tax" =>$this->request->getVar("tax"),
                        "provident_fund" =>$this->request->getVar("provident_fund"),
                        "loan" =>$this->request->getVar("loan"),
                        "total_pay" =>$this->request->getVar("total_pay"),
                        "addition" =>$this->request->getVar("addition"),
                        "diduction" =>$this->request->getVar("diduction"),
                        "status" =>$this->request->getVar("status"),
                        "paid_type" =>$this->request->getVar("paid_type"),
                    ];  
                    if($pay_salary_obj->insert($data))
                    {
                        $response=[
                            "status" =>200,
                            "message" =>"Payroll list added Successfull",
                            "error" =>false,
                            "data" =>[],
                        ];
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Missing Token",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updatePayroll_list($salary_type_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $salary_type_obj=new SalaryTypeModel();
                $rules=[
                    "pay_id" =>"required",
                    "emp_id" =>"required",
                    "type_id" =>"required",
                    "month" =>"required",
                    "year" =>"required",
                    "paid_date" =>"required",
                    "total_days" =>"required",
                    "basic" =>"required",
                    "medical" =>"required",
                    "house_rent" =>"required",
                    "bonus" =>"required",
                    "bima" =>"required",
                    "tax" =>"required",
                    "provident_fund" =>"required",
                    "loan" =>"required",
                    "total_pay" =>"required",
                    "addition" =>"required",
                    "diduction" =>"required",
                    "status" =>"required",
                    "paid_type" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "pay_id" =>$this->request->getVar("pay_id"),
                        "emp_id" =>$this->request->getVar("emp_id"),
                        "type_id" =>$this->request->getVar("type_id"),
                        "month" =>$this->request->getVar("month"),
                        "year" =>$this->request->getVar("year"),
                        "paid_date" =>$this->request->getVar("paid_date"),
                        "total_days" =>$this->request->getVar("total_days"),
                        "basic" =>$this->request->getVar("basic"),
                        "medical" =>$this->request->getVar("medical"),
                        "house_rent" =>$this->request->getVar("house_rent"),
                        "bonus" =>$this->request->getVar("bonus"),
                        "bima" =>$this->request->getVar("bima"),
                        "tax" =>$this->request->getVar("tax"),
                        "provident_fund" =>$this->request->getVar("provident_fund"),
                        "loan" =>$this->request->getVar("loan"),
                        "total_pay" =>$this->request->getVar("total_pay"),
                        "addition" =>$this->request->getVar("addition"),
                        "diduction" =>$this->request->getVar("diduction"),
                        "status" =>$this->request->getVar("status"),
                        "paid_type" =>$this->request->getVar("paid_type"),
                    ];   
                    
                    if($salary_type_obj->find($salary_type_id))
                    {
                        if($salary_type_obj->update($salary_type_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Salary update Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to update the Salary",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Salary id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }


    public function deletePayroll_list($payroll_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $pay_salary_obj=new PaySalaryModel();

                if($pay_salary_obj->find($payroll_id))
                {
                    if($pay_salary_obj->delete($payroll_id))
                    {
                         $response=[
                            "status" =>200,
                            "message" =>"Salary delete Successfull",
                            "error" =>false,
                            "data" =>[],
                            ];
                    }
                    else
                      {
                        $response=[
                             "status" =>500,
                              "message" =>"Failed to delete Salary data",
                              "error" =>true,
                              "data" =>[],
                         ];
                     }
                }
                else
                {
                    $response=[
                        "status" =>500,
                         "message" =>"Salary ID did not match",
                         "error" =>true,
                         "data" =>[],
                    ];
                }  
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }


  //----------------------- Quatation -----------------------------------

    public function listQuatation()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $quote_obj=new QuotationsModel();
                  $quote_item_obj=new QuotationItemModel();
                  $quote_data=$quote_obj->findAll();
                  $builder=$this->db->table("quotations");
                  $builder->select("quotations.*,clients.name,clients.email");
                  $builder->join("clients","clients.id=quotations.client_id");
                  $data=$builder->orderBy('id',"DESC")->get()->getResult();
                  $quote_item_data=$quote_item_obj->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Quatations Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "quotation_data"=>$data,
                          "quotation_item" =>$quote_item_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function listQuatation_last()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $quote_obj=new QuotationsModel();
                  $quote_data=$this->db->table('quotations')->orderBy('id','desc')->limit(1)->get()->getResult();
                  $response=[
                      "status" =>200,
                      "message" =>"Quatations Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "quotation_data"=>$quote_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }


    public function listQuotation_item($quotation_id)
    {
        $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $quote_obj=new QuotationsModel();
                  $quote_item_obj=new QuotationItemModel();
                  $quote_data=$quote_item_obj->where('quotation_id',$quotation_id)->find();
                  $response=[
                      "status" =>200,
                      "message" =>"Quatations item data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "quotation_item" =>$quote_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function listQuotation_item_service($item_id)
    {
        $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $service_obj=new ServiceModel();
                  $quote_data=$service_obj->where('id',$item_id)->find();
                  $response=[
                      "status" =>200,
                      "message" =>"Quatations item data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "service_item" =>$quote_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addQuatation()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $quote_obj=new QuotationsModel();
                $client_obj=new ClientModel();
                $quote_item_obj=new QuotationItemModel();

                $rules=[
                    "quote_no" =>"required",
                    "client_id" =>"required",
                    "quote_date" =>"required",
                    "valid_till" =>"required",
                    "sub_total" =>"required",
                    "total" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "quote_no" =>$this->request->getVar("quote_no"),
                        "client_id" =>$this->request->getVar("client_id"),
                        "quote_date" =>$this->request->getVar("quote_date"),
                        "valid_till" =>$this->request->getVar("valid_till"),
                        "sub_total" =>$this->request->getVar("sub_total"),
                        "gst" =>$this->request->getVar("gst"),
                        "discount" =>$this->request->getVar("discount"),
                        "total" =>$this->request->getVar("total"),
                        "status" =>$this->request->getVar("status"),
                        "remarks" =>$this->request->getVar("remarks"),
                        "is_deleted" =>$this->request->getVar("is_deleted"),
                        "ref_invoice_id" =>$this->request->getVar("ref_invoice_id"),
                    ];  
                    $client_id=$this->request->getVar("client_id");
                    if($client_obj->find($client_id))
                    {
                        $this->db->transStart();
                        $item_array =json_decode($this->request->getVar('array')); 
                        $this->db->table("quotations")->insert($data);
                        $quotation_id=$this->db->insertID();
                        $arr = array();
                        foreach ($item_array as $results) {
                          $arr[] = array(
                               'quotation_id' => $quotation_id,
                               'item_id' => $results->item_id,
                               'descr' => $results->descr,
                               'price' => $results->price,
                               'qty' => $results->qty,
                           );
                        }
                        if($quote_item_obj->insertBatch($arr))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Quotations added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Please add the quotation",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                         $this->db->transComplete();
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Client id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateQuatation($quote_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $quote_obj=new QuotationsModel();
                $quote_item_obj=new QuotationItemModel();
                $rules=[
                    "quote_no" =>"required",
                    "client_id" =>"required",
                    "quote_date" =>"required",
                    "valid_till" =>"required",
                    "sub_total" =>"required",
                    "total" =>"required",
                    "status" =>"required",
                    "is_deleted" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "quote_no" =>$this->request->getVar("quote_no"),
                        "client_id" =>$this->request->getVar("client_id"),
                        "quote_date" =>$this->request->getVar("quote_date"),
                        "valid_till" =>$this->request->getVar("valid_till"),
                        "sub_total" =>$this->request->getVar("sub_total"),
                        "discount" =>$this->request->getVar("discount"),
                        "total" =>$this->request->getVar("total"),
                        "status" =>$this->request->getVar("status"),
                        "remarks" =>$this->request->getVar("remarks"),
                    ];  
                    $item_array =json_decode($this->request->getVar('array')); 
                    $client_id=$this->request->getVar("client_id");
                    $this->db->transStart();
                    if($quote_obj->find($quote_id))
                    {
                        if($quote_obj->update($quote_id,$data))
                        {
                            $arr_update= array();
                            $arr_insert=array();
                            foreach ($item_array as $results) {
                                if($results->id=="")
                                {
                                    $arr_insert[] = array(
                                        'quotation_id' =>$results->quotation_id, 
                                        'descr' => $results->descr,
                                        'item_id' => $results->item_id,
                                        'price' => $results->price,
                                        'qty' => $results->qty,
                                   );
                                }
                                else
                                {
                                    $arr_update[] = array(
                                        'id' => $results->id,  
                                        'descr' => $results->descr,
                                        'item_id' => $results->item_id,
                                        'price' => $results->price,
                                        'qty' => $results->qty,
                                   );
                                }
                            }

                            if(!empty($arr_insert))
                            {
                                $quote_item_obj->insertBatch($arr_insert);
                            } 
                                if($quote_item_obj->updateBatch($arr_update,'id'))
                                {
                                    $response=[
                                        "status" =>200,
                                        "message" =>"Quotations updated Successfull",
                                        "error" =>false,
                                        "data" =>[],
                                    ];
                                }
                                else
                                {
                                    $response=[
                                        "status" =>500,
                                        "message" =>"Please change the any quotation",
                                        "error" =>true,
                                        "data" =>[],
                                    ];
                                }
                            
                         }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to Quotations update successfull",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Quotations id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                   $this->db->transComplete();
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteQuatation($quote_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $quote_obj=new QuotationsModel();
                $quote_item_obj=new QuotationItemModel();
                $this->db->transStart();
                if($quote_obj->find($quote_id))
                {
                        $arr = array();
                        $builder = $this->db->table('quotations');
                        $builder->select('quotation_item.*');
                        $builder->join('quotation_item', "quotation_item.quotation_id =$quote_id");
                        $query = $builder->get()->getResult();
                     
                        $quote_obj->delete($quote_id);
                        $arr=array();
                        foreach ($query as $results) {
                          $arr[]= array(
                               'id' => $results->id,
                        );
                          $quote_item_obj->delete($results->id);
                       }
                     
                        $response=[
                            "status" =>200,
                            "message" =>"Quoatations delete successfull",
                            "error" =>false,
                            "data" =>[],
                        ];  
                }
                else
                {
                    $response=[
                        "status" =>500,
                         "message" =>"Quoatations ID did not match",
                         "error" =>true,
                         "data" =>[],
                    ];
                }  
                $this->db->transComplete();
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }



    //------------------------ Payment ----------------------------------

    public function listPayment()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $builder=$this->db->table("clients");
                  $builder->select("client_payments.*,clients.name");
                  $builder->join("client_payments","clients.id=client_payments.client_id");
                  $data=$builder->get()->getResult();
                  $response=[
                      "status" =>200,
                      "message" =>"Client Payment Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "payment_data"=>$data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function invoice_client($client_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $invoice_obj=new InvoiceModel();
                $invoice_data=$invoice_obj->where('client_id',$client_id)->find();
                $response=[
                    "status" =>200,
                    "message" =>"Invoice Data",
                    "error" =>false,
                    "data" =>[
                        //"user" =>$decoded_data,
                        "Invoice_data"=>$invoice_data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing Token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function listpayment_last()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $quote_obj=new ClientPaymentsModel();
                  $payment_data=$this->db->table('client_payments')->orderBy('id','desc')->limit(1)->get()->getResult();
                  $response=[
                      "status" =>200,
                      "message" =>"Payment Data",
                      "error" =>false,
                      "data" =>[
                          "payment_data"=>$payment_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addPayment()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $client_pay_obj=new ClientPaymentsModel();
                $client_obj=new ClientModel();
                $invoice_obj=new InvoiceModel();
                $invoice_no=$this->request->getVar("invoice_id");
                $invoice_data=$invoice_obj->where('inv_no',$invoice_no)->find();
                $rules=[
                    "receipt_no" =>"required",
                    "client_id" =>"required",
                    "amount" =>"required",
                    "payment_date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=
                    [
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $client_id=$this->request->getVar("client_id");
                    $this->db->transStart();
                    if($client_obj->find($client_id))
                    {
                        $data=[
                            "receipt_no" =>$this->request->getVar("receipt_no"),
                            "client_id" =>$this->request->getVar("client_id"),
                            "amount" =>$this->request->getVar("amount"),
                            "invoice_id" =>$this->request->getVar("invoice_id"),
                            "payment_date" =>$this->request->getVar("payment_date"),
                            "remarks" =>$this->request->getVar("remarks"),
                        ];  
                        
                        $pay_amount=$this->request->getVar("amount");
                        $final_paid=$pay_amount+$invoice_data[0]['total_paid'];
                        $final_total=$invoice_data[0]['total'];
                        $final_due=$final_total-$final_paid;
                        $final_id=$invoice_data[0]['id'];

                        $invoice_data=[
                            "total" => $final_total,
                            "total_paid" => $final_paid,
                            "total_due" =>$final_due,
                        ];  

                        $invoice_obj->update($final_id,$invoice_data);
                        if($client_pay_obj->insert($data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Payment added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Client ID did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    $this->db->transComplete();
                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updatePayment($client_pay_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $client_pay_obj=new ClientPaymentsModel();
                $rules=[
                    "receipt_no" =>"required",
                    "amount" =>"required",
                    "client_id" =>"required",
                    "invoice_id" =>"required",
                    "payment_date" =>"required",
                    "remarks" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "receipt_no" =>$this->request->getVar("receipt_no"),
                        "amount" =>$this->request->getVar("amount"),
                        "client_id" =>$this->request->getVar("client_id"),
                        "invoice_id" =>$this->request->getVar("invoice_id"),
                        "payment_date" =>$this->request->getVar("payment_date"),
                        "remarks" =>$this->request->getVar("remarks"),
                    ];   
                    if($client_pay_obj->find($client_pay_id))
                    {
                        if($client_pay_obj->update($client_pay_id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Payment update Successfull",
                                "error" =>false,
                                "data" =>[],
                             ];
                          }
                        else
                         {
                              $response=[
                                 "status" =>500,
                                 "message" =>"Failed to Payment",
                                 "error" =>true,
                                  "data" =>[],
                             ];
                          }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Payment ID not found",
                            "error" =>true,
                             "data" =>[],
                        ];
                    }
                   
                  
                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deletePayment($client_pay_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $client_pay_obj=new ClientPaymentsModel();

                if($client_pay_obj->find($client_pay_id))
                {
                    if($client_pay_obj->delete($client_pay_id))
                    {
                         $response=[
                            "status" =>200,
                            "message" =>"Payment delete Successfull",
                            "error" =>false,
                            "data" =>[],
                            ];
                    }
                    else
                      {
                        $response=[
                             "status" =>500,
                              "message" =>"Failed to delete Payment data",
                              "error" =>true,
                              "data" =>[],
                         ];
                     }
                }
                else
                {
                    $response=[
                        "status" =>500,
                         "message" =>"Payment ID not found",
                         "error" =>true,
                         "data" =>[],
                    ];
                }  
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    //--------------------- Sales Report --------------------------------------

    public function listSales()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $builder=$this->db->table("clients");
                  $builder->select("invoice.*,clients.name");
                  $builder->join("invoice","clients.id=invoice.client_id");
                  $data=$builder->get()->getResult();
                  $response=[
                      "status" =>200,
                      "message" =>"Sales Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "sales_data"=>$data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    //--------------------- Invoice --------------------------------------
    
    public function listInvoice()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $invoice_obj=new InvoiceModel();
                  $invoice_item_obj=new InvoiceItemModel();
                  $invoice_data=$invoice_obj->findAll();
                  $builder=$this->db->table("invoice");
                  $builder->select("invoice.*,clients.name,clients.contact_no,clients.email");
                  $builder->join("clients","clients.id=invoice.client_id AND invoice.total<>invoice.total_paid");
                  $data=$builder->orderBy('id',"DESC")->get()->getResult();
                  $invoice_item_data=$invoice_item_obj->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Invoice Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "Invoice_data"=>$data,
                          "Invoice_item" =>$invoice_item_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addInvoice()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $invoice_obj=new InvoiceModel();
                $client_obj=new ClientModel();
                $invoice_item_obj=new InvoiceItemModel();

                $rules=[
                    "inv_no" =>"required",
                    "client_id" =>"required",
                    "inv_date" =>"required",
                    "total" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "inv_no" =>$this->request->getVar("inv_no"),
                        "client_id" =>$this->request->getVar("client_id"),
                        "inv_date" =>$this->request->getVar("inv_date"),
                        "sub_total" =>$this->request->getVar("sub_total"),
                        "gst" =>$this->request->getVar("gst"),
                        "total" =>$this->request->getVar("total"),
                        "total_paid" =>$this->request->getVar("total_paid"),
                        "total_due" =>$this->request->getVar("total_due"),
                        "due_date" =>$this->request->getVar("due_date"),
                        "remarks" =>$this->request->getVar("remarks"),
                        "is_deleted" =>$this->request->getVar("is_deleted"),
                    ];  
                    $client_id=$this->request->getVar("client_id");
                    if($client_obj->find($client_id))
                    {
                        $this->db->transStart();
                        $item_array =json_decode($this->request->getVar('array')); 
                        $this->db->table("invoice")->insert($data);
                        $invoice_id=$this->db->insertID();
                      
                        $arr = array();
                        foreach ($item_array as $results) {
                          $arr[] = array(
                               'invoice_id' => $invoice_id,
                               'item_id' => $results->item_id,
                               'descr' => $results->descr,
                               'price' => $results->price,
                               'qty' => $results->qty,
                           );
                        }
                        if($invoice_item_obj->insertBatch($arr))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Invoice added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                         $this->db->transComplete();
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Invoice id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }

                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateInvoice($invoice_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $invoice_obj=new InvoiceModel();
                $invoice_item_obj=new InvoiceItemModel();
                $rules=[
                    "inv_no" =>"required",
                    "client_id" =>"required",
                    "inv_date" =>"required",
                    "sub_total" =>"required",
                    "total" =>"required",
                
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "inv_no" =>$this->request->getVar("inv_no"),
                        "client_id" =>$this->request->getVar("client_id"),
                        "inv_date" =>$this->request->getVar("inv_date"),
                        "due_date" =>$this->request->getVar("due_date"),
                        "sub_total" =>$this->request->getVar("sub_total"),
                        "total" =>$this->request->getVar("total"),
                        "remarks" =>$this->request->getVar("remarks"),
                        "is_deleted" =>$this->request->getVar("is_deleted"),
                        "total_paid" =>$this->request->getVar("total_paid"),
                        "total_due" =>$this->request->getVar("total_due"),
                        "ref_quotation_id" =>$this->request->getVar("ref_quotation_id"),
                    ];  
                    $item_array =json_decode($this->request->getVar('array')); 
                    $client_id=$this->request->getVar("client_id");
                    $this->db->transStart();
                    if($invoice_obj->find($invoice_id))
                    {
                        if($invoice_obj->update($invoice_id,$data))
                        {
                            $arr_update= array();
                            $arr_insert=array();
                            foreach ($item_array as $results) {
                                if($results->id=="")
                                {
                                    $arr_insert[] = array(
                                        'invoice_id' =>$results->invoice_id, 
                                        'descr' => $results->descr,
                                        'item_id' => $results->item_id,
                                        'price' => $results->price,
                                        'qty' => $results->qty,
                                   );
                                }
                                else
                                {
                                    $arr_update[] = array(
                                        'id' => $results->id,  
                                        'descr' => $results->descr,
                                        'item_id' => $results->item_id,
                                        'price' => $results->price,
                                        'qty' => $results->qty,
                                   );
                                }
                               
                            }

                            if(!empty($arr_insert))
                            {
                                $invoice_item_obj->insertBatch($arr_insert);
                            } 
                            if($invoice_item_obj->updateBatch($arr_update,'id'))
                            {
                                $response=[
                                    "status" =>200,
                                    "message" =>"Invoice updated Successfull",
                                    "error" =>false,
                                    "data" =>[],
                                ];
                            }
                            else
                            {
                                $response=[
                                    "status" =>500,
                                    "message" =>"Please update the any invoice item",
                                    "error" =>true,
                                    "data" =>[],
                                ];
                            }

                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Failed to Quotations update successfull",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                    }
                    else
                    {
                        $response=[
                            "status" =>500,
                            "message" =>"Quotations id did not match",
                            "error" =>true,
                            "data" =>[],
                        ];
                    }
                    $this->db->transComplete();
                   
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteInvoice($invoice_id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $invoice_obj=new InvoiceModel();
                $invoice_item_obj=new InvoiceItemModel();
                $this->db->transStart();
                if($invoice_obj->find($invoice_id))
                {
                    $arr = array();
                        $builder = $this->db->table('invoice');
                        $builder->select('invoice_item.*');
                        $builder->join('invoice_item', 'invoice_item.invoice_id = invoice.id');
                        $query = $builder->get()->getResult();
                     
                        $invoice_obj->delete($invoice_id);
                        $arr=array();
                        foreach ($query as $results) {
                          $arr[]= array(
                               'id' => $results->id,
                        );
                          $invoice_item_obj->delete($results->id);
                       }
                     
                        $response=[
                            "status" =>200,
                            "message" =>"Invoice delete successfull",
                            "error" =>false,
                            "data" =>[],
                        ];  
                }
                else
                {
                    $response=[
                        "status" =>500,
                         "message" =>"Quoatations ID did not match",
                         "error" =>true,
                         "data" =>[],
                    ];
                }  
                $this->db->transComplete();
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function listInvoice_item($inv_id)
    {
        $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $inv_obj=new InvoiceModel();
                  $inv_item_obj=new InvoiceItemModel();
                  $inv_data=$inv_item_obj->where('invoice_id',$inv_id)->find();
                  $response=[
                      "status" =>200,
                      "message" =>"Invoice item data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "invoice_item" =>$inv_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function listInvoice_last()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $invoice_obj=new InvoiceModel();
                  $invoice_data=$this->db->table('invoice')->orderBy('id','desc')->limit(1)->get()->getResult();
                  $response=[
                      "status" =>200,
                      "message" =>"Invoice Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "invoice_data"=>$invoice_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function listfinal_Invoice()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $invoice_obj=new InvoiceModel();
                  $invoice_item_obj=new InvoiceItemModel();
                  $invoice_data=$invoice_obj->findAll();
                  $builder=$this->db->table("invoice");
                  $builder->select("invoice.*,clients.name");
                  $builder->join("clients","clients.id=invoice.client_id AND invoice.total=invoice.total_paid");
                  $data=$builder->orderBy('id',"DESC")->get()->getResult();
                  $invoice_item_data=$invoice_item_obj->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Invoice Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "Invoice_data"=>$data,
                          "Invoice_item" =>$invoice_item_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function showInvoice()
    {
        return view('backend/showInvoice.php');
    }


    //-------------------- Summary -------------------------------

    public function listSummary()
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $client_obj=new ClientModel();
                  $client_data=$client_obj->orderBy('id',"DESC")->findAll();
                  $response=[
                      "status" =>200,
                      "message" =>"Summary Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "summary_data"=>$client_data,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function listService_Summary($client_id)
    {
          $auth=$this->request->getHeader("Authorization");
          try{
              if(isset($auth))
              {
                  $token=$auth->getValue();
                  $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                  $billed=$this->request->getVar("is_billed");
                 // $summary=$this->db->table("summary")->where('client_id',$client_id)->where('is_billed',$billed)->get()->getResult();
               
               
                  $builder=$this->db->table("summary");
                  $builder->select("services.name,services.price,summary.*");
                  $builder->join("services","summary.client_id=$client_id AND summary.is_billed=$billed AND summary.item_id=services.id");
                  $summary=$builder->orderBy('id',"DESC")->get()->getResult();
                 
                                   
                  $response=[
                      "status" =>200,
                      "message" =>"Service summary Data",
                      "error" =>false,
                      "data" =>[
                          //"user" =>$decoded_data,
                          "Service summary_data"=>$summary,
                      ],
                  ];
              }
              else
              {
                  $response=[
                      "status" =>500,
                      "message" =>"Missing Token",
                      "error" =>true,
                      "data" =>[],
                  ];
              }
          }catch(Exception $ex)
          {
              $response=[
                  "status" =>500,
                  "message" =>$ex->getMessage(),
                  "error" =>true,
                  "data" =>[],
              ];
          }
          return $this->respondCreated($response);
    }

    public function addService_summary()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $summary_obj=new SummaryModel();
                $rules=[
                    "item_id" =>"required",
                    "descr" =>"required",
                    "qty" =>"required",
                    "is_billed" =>"required",
                    "date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                  
                        $data=[
                            "descr" =>$this->request->getVar("descr"),
                            "client_id" =>$this->request->getVar("client_id"),
                            "item_id" =>$this->request->getVar("item_id"),
                            "qty" =>$this->request->getVar("qty"),
                            "is_billed" =>$this->request->getVar("is_billed"),
                            "date" =>$this->request->getVar("date"),
                        ];  
                        if($summary_obj->insert($data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Summary added Successfull",
                                "error" =>false,
                                "data" =>[],
                            ];
                        }
                        else
                        {
                            $response=[
                                "status" =>500,
                                "message" =>"Missing Token",
                                "error" =>true,
                                "data" =>[],
                            ];
                        }
                
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateSummary($id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $summary_obj=new SummaryModel();
                $rules=[
                    "item_id" =>"required",
                    "descr" =>"required",
                    "qty" =>"required",
                    "is_billed" =>"required",
                    "date" =>"required",
                ];
                if(!$this->validate($rules))
                {
                    $response=[
                        "status" =>500,
                        "message" =>$this->validator->getErrors(),
                        "error" =>true,
                        "data" =>[],
                    ];
                }
                else
                {
                    $data=[
                        "descr" =>$this->request->getVar("descr"),
                        "client_id" =>$this->request->getVar("client_id"),
                        "item_id" =>$this->request->getVar("item_id"),
                        "qty" =>$this->request->getVar("qty"),
                        "is_billed" =>$this->request->getVar("is_billed"),
                        "date" =>$this->request->getVar("date"),
                    ];  
                  
                        if($summary_obj->update($id,$data))
                        {
                            $response=[
                                "status" =>200,
                                "message" =>"Summary update Successfull",
                                "error" =>false,
                                "data" =>[],
                             ];
                          }
                        else
                         {
                              $response=[
                                 "status" =>500,
                                 "message" =>"Failed to Update",
                                 "error" =>true,
                                  "data" =>[],
                             ];
                          }
                 
                }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteSummary($id)
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $summary_obj=new SummaryModel();
             
                    if($summary_obj->delete($id))
                    {
                         $response=[
                            "status" =>200,
                            "message" =>"Summary delete Successfull",
                            "error" =>false,
                            "data" =>[],
                            ];
                    }
                    else
                      {
                        $response=[
                             "status" =>500,
                              "message" =>"Failed to delete Summary data",
                              "error" =>true,
                              "data" =>[],
                         ];
                     }
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"Missing token",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

 }
