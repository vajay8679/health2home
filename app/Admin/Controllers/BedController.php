<?php
namespace App\Admin\Controllers;

use App\Models\Bed;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Hospital;
use Admin; 

class BedController extends AdminController
{
  

    protected $title = 'Bed';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bed());
        
        if(!Admin::user()->isAdministrator()){
            $grid->model()->where('hospital_id', Hospital::where('admin_user_id',Admin::user()->id)->value('id'));
        }
        $grid->column('id', __('Id'));
        $grid->column('hospital_id', __('Hospital'))->display(function($hospitals){
            $hospitals = Hospital::where('id',$hospitals)->value('hospital_name');
            return $hospitals;
        });
        $grid->column('bed_type', __('Bed Type'));
        $grid->column('bed_count', __('Bed Count'));
        
        if(Admin::user()->isAdministrator()){
            $grid->disableExport();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        }else{
            $grid->disableExport();
            $grid->disableCreation();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
        }

        $grid->filter(function ($filter) {
        if(Admin::user()->isAdministrator()){
            //Get All status
            $hospitals = Hospital::pluck('hospital_name','id');
            
            $filter->equal('hospital_id', __('Hospital'))->select($hospitals);
        }
        });  
        

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Bed::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('bed_count', __('bed_count'));
        $show->field('bed_type', __('bed_type'));
        $show->field('hospital_id', __('hospital_id'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Bed());
        $hospitals = Hospital::pluck('hospital_name','id');

        
        if(!Admin::user()->isAdministrator()){
            $form->hidden('hospital_id')->value($hospital_id);
        }else{
            $form->select('hospital_id', __('Hospital'))->options($hospitals)->rules(function ($form) {
            return 'required';
        });
        }
        if (!Admin::user()->isAdministrator()) {
            $form->hidden('bed_type')->value('Empty');
        } else {
            $form->select('bed_type', __('Bed Type'))
                 ->options(['General Ward Beds' => 'General Ward Beds', 'ICU Beds' => 'ICU Beds','NICU Beds' => 'NICU Beds','PICU Beds' => 'PICU Beds','Maternity Beds' => 'Maternity Beds','Emergency Room Beds' => 'Emergency Room Beds','Surgical Beds' => 'Surgical Beds','Isolation Beds' => 'Isolation Beds','Psychiatric Beds' => 'Psychiatric Beds','Hospice Beds' => 'Hospice Beds'])
                 ->rules('required'); 
        } 
        $form->number('bed_count', __('Bed Count'))->rules('required');
        
        $form->tools(function (Form\Tools $tools) {
           $tools->disableDelete(); 
           $tools->disableView();
        });
        $form->footer(function ($footer) {
           $footer->disableViewCheck();
           $footer->disableEditingCheck();
           $footer->disableCreatingCheck();
        });

        return $form;

    }

}
