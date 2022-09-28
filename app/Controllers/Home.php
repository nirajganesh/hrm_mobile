<?php

namespace App\Controllers;
use App\Models\ClientModel;
use App\Models\ClientPaymentsModel;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\EmployeeModel;
use App\Models\EmpSalaryModel;
use App\Models\InvoiceItemModel;
use App\Models\InvoiceModel;
use App\Models\PaySalaryModel;
use App\Models\QuotationItemModel;
use App\Models\QuotationsModel;
use App\Models\SettingModel;
use Faker\Provider\ar_EG\Payment;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function invoice_service()
    {
        return view('backend/invoice_service.php');
    }

    public function invoice_payment($id)
    {
        $payment_obj=new ClientPaymentsModel();
        $payment_data=$payment_obj->find($id);
        $data['id']=$payment_data['id'];
        $data['receipt_no']=$payment_data['receipt_no'];
        $data['client_id']=$payment_data['client_id'];
        $data['amount']=$payment_data['amount'];
        $data['remarks']=$payment_data['remarks'];
        $data['payment_date']=$payment_data['payment_date'];

        $setting_obj=new SettingModel();
        $setting_data=$setting_obj->findAll();
        $data['copyright']=$setting_data[0]['copyright'];
        $data['sitelogo']=$setting_data[0]['sitelogo'];
        $data['contact']=$setting_data[0]['contact'];
        $data['system_email']=$setting_data[0]['system_email'];

        $client_obj=new ClientModel();
        $client_id_value=$payment_data['client_id'];
        $client_data=$client_obj->where('id',$client_id_value)->find();
        $client['name']=$client_data[0]['name'];
        $client['contact_no']=$client_data[0]['contact_no'];
        $client['email']=$client_data[0]['email'];  
        
        return view('backend/showPayment.php',array_merge($data,$client));
    }

    public function payslip_invoice($emp_id,$pay_id)
    {
        $emp_obj=new EmployeeModel();
        $emp_data=$emp_obj->where("em_id",$emp_id)->find();
        $dep_id=$emp_data[0]['dep_id'];
        $des_id=$emp_data[0]['des_id'];

        $dep_obj=new DepartmentModel();
        $dep_data=$dep_obj->find($dep_id);

        $des_obj=new DesignationModel();
        $des_data=$des_obj->find($des_id);

        $emp_sal_obj=new PaySalaryModel();
        $emp_sal_data=$emp_sal_obj->where("emp_id",$emp_id)->find();

        $setting_obj=new SettingModel();
        $setting_data=$setting_obj->findAll();


        $data['emp_id']=$emp_id;
        $data['emp_name']=$emp_data[0]['first_name'];
        $data['des_name']=$des_data['des_name'];
        $data['dep_name']=$dep_data['dep_name'];
        $data['em_joining_date']=$emp_data[0]['em_joining_date'];
        $data['email']=$emp_data[0]['em_email'];
        $data['phone']=$emp_data[0]['em_phone'];
        $data['paid_date']=$emp_sal_data[0]['paid_date'];
        $data['basic']=$emp_sal_data[0]['basic'];
        $data['bonus']=$emp_sal_data[0]['bonus'];
        $data['month']=$emp_sal_data[0]['month'];
        $data['year']=$emp_sal_data[0]['year'];
        $data['paid_type']=$emp_sal_data[0]['paid_type'];
        $data['total_pay']=$emp_sal_data[0]['total_pay'];
        $data['address']=$setting_data[0]['address'];
        
        return view('backend/invoice.php',$data);
    }

    public function quotation_invoice($quo_id,$client_id)
    {
        $setting_obj=new SettingModel();
        $setting_data=$setting_obj->findAll();
        $data['copyright']=$setting_data[0]['copyright'];
        $data['sitelogo']=$setting_data[0]['sitelogo'];
        $data['symbol']=$setting_data[0]['symbol'];
        $data['sitetitle']=$setting_data[0]['sitetitle'];
        $data['contact']=$setting_data[0]['contact'];
        $data['system_email']=$setting_data[0]['system_email'];
        $data['address']=$setting_data[0]['address'];
        $data['bank_name']=$setting_data[0]['bank_name'];
        $data['account_name']=$setting_data[0]['account_name'];
        $data['account_number']=$setting_data[0]['account_number'];
        $data['ifsc']=$setting_data[0]['ifsc'];
        $data['upi_id']=$setting_data[0]['upi_id'];

        
        $quotation_obj=new QuotationsModel();
        $quotation_data=$quotation_obj->find($quo_id);
        $quotation['quo_no']=$quotation_data['quote_no'];
        $quotation['quo_date']=$quotation_data['quote_date'];
        $quotation['valid_till']=$quotation_data['valid_till'];
        $quotation['sub_total']=$quotation_data['sub_total'];
        $quotation['discount']=$quotation_data['discount'];
        $quotation['total']=$quotation_data['total'];

        $client_obj=new ClientModel();
        $client_id_value=$quotation_data['client_id'];
        $client_data=$client_obj->where('id',$client_id_value)->find();
        $client['name']=$client_data[0]['name'];
        $client['contact_no']=$client_data[0]['contact_no'];
        $client['email']=$client_data[0]['email'];  

        
       $quotation_obj=new QuotationItemModel();
       $quotation_data=$quotation_obj->where('quotation_id',$quo_id)->findall();
       $quotation_item=array();
        foreach ($quotation_data as $results) {
            $quotation_item[] = array(
                 'item_id' => $results['item_id'],
                 'descr' => $results['descr'],
                 'price' => $results['price'],
                 'qty' => $results['qty'],
                 'subtotal'=>$results['price']*$results['qty'],
             );
        }
       $data['quotation_item']=$quotation_item;

        return view('backend/showQuotation.php',array_merge($data,$client,$quotation));
    }

    public function showinvoice($inv_id,$client_id)
    {
        $setting_obj=new SettingModel();
        $setting_data=$setting_obj->findAll();
        $data['copyright']=$setting_data[0]['copyright'];
        $data['sitelogo']=$setting_data[0]['sitelogo'];
        $data['sitetitle']=$setting_data[0]['sitetitle'];
        $data['contact']=$setting_data[0]['contact'];
        $data['system_email']=$setting_data[0]['system_email'];
        $data['address']=$setting_data[0]['address'];
        $data['bank_name']=$setting_data[0]['bank_name'];
        $data['account_name']=$setting_data[0]['account_name'];
        $data['account_number']=$setting_data[0]['account_number'];
        $data['ifsc']=$setting_data[0]['ifsc'];
        $data['upi_id']=$setting_data[0]['upi_id'];
      

        $invoice_obj=new InvoiceModel();
        $invoice_data=$invoice_obj->find($inv_id);
        $invoice['inv_no']=$invoice_data['inv_no'];
        $invoice['inv_date']=$invoice_data['inv_date'];
        $invoice['total_paid']=$invoice_data['total_paid'];
        $invoice['total_due']=$invoice_data['total_due'];
        $invoice['due_date']=$invoice_data['due_date'];
        $invoice['total']=$invoice_data['total'];


        $client_obj=new ClientModel();
        $client_id_value=$invoice_data['client_id'];
        $client_data=$client_obj->where('id',$client_id_value)->find();
       
    
        $client['name']=$client_data[0]['name'];
        $client['contact_no']=$client_data[0]['contact_no'];
        $client['email']=$client_data[0]['email'];  


       $invoice_obj=new InvoiceItemModel();
       $invoice_data=$invoice_obj->where('invoice_id',$inv_id)->findall();
       $invoice_item=array();
        foreach ($invoice_data as $results) {
            $invoice_item[] = array(
                 'item_id' => $results['item_id'],
                 'descr' => $results['descr'],
                 'price' => $results['price'],
                 'qty' => $results['qty'],
                 'subtotal'=>$results['price']*$results['qty'],
             );
        }
       $data['invoice_item']=$invoice_item;
       return view('backend/showInvoice',array_merge($data,$client,$invoice));
    }




}
